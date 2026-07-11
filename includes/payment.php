<?php
/**
 * Simple payment abstraction
 * In a production system you'd integrate with a real gateway
 * (Stripe, Razorpay, PayPal, etc.) using their SDK or REST API.
 *
 * Here we provide a stub that simulates success and generates the kind of transaction IDs such services return.
 */

/**
 * Process a payment request.
 *
 * @param string $method 'card'|'upi'|'cod'
 * @param array  $details card/upi details
 * @param float  $amount  total amount to charge
 * @return array ['success'=>bool,'transaction_id'=>string,'message'=>string]
 */
function processPayment($method, $details, $amount)
{
    // basic sanity checks
    switch ($method) {
        case 'card':
            // card details expected: number/expiry/cvv
            if (empty($details['number']) || empty($details['expiry']) || empty($details['cvv'])) {
                return ['success' => false, 'message' => 'Incomplete card details'];
            }
            // simulate network call delay
            usleep(200000);
            return [
                'success' => true,
                'transaction_id' => 'CARD' . strtoupper(bin2hex(random_bytes(5))),
                'message' => 'Card payment approved'
            ];

        case 'upi':
            if (empty($details['upi_id'])) {
                return ['success' => false, 'message' => 'UPI ID required'];
            }
            usleep(100000);
            return [
                'success' => true,
                'transaction_id' => 'UPI' . strtoupper(bin2hex(random_bytes(5))),
                'message' => 'UPI transaction successful'
            ];

        case 'cod':
            return ['success' => true, 'transaction_id' => '', 'message' => 'Cash on delivery'];

        case 'netbanking':
            if (empty($details['bank_name'])) {
                return ['success' => false, 'message' => 'Bank selection required'];
            }
            usleep(150000);
            return [
                'success' => true,
                'transaction_id' => 'NB' . strtoupper(bin2hex(random_bytes(5))),
                'message' => 'Net Banking transaction successful'
            ];

        case 'wallet':
            if (empty($details['wallet_name']) || empty($details['wallet_phone'])) {
                return ['success' => false, 'message' => 'Wallet details required'];
            }
            usleep(100000);
            return [
                'success' => true,
                'transaction_id' => 'WAL' . strtoupper(bin2hex(random_bytes(5))),
                'message' => 'Wallet transaction successful'
            ];

        default:
            return ['success' => false, 'message' => 'Unsupported payment method'];
    }
}
