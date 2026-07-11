<?php $pageTitle = 'Contact Us'; ?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Get in Touch</span>
            <h2>Contact Us</h2>
            <p>Have a question or feedback? We'd love to hear from you!</p>
        </div>

        <div class="contact-layout">
            <!-- Contact Form -->
            <div>
                <div class="checkout-form-card">
                    <h3 style="font-family:var(--font-main);margin-bottom:var(--space-xl);">
                        <i class="fas fa-paper-plane" style="color:var(--primary);"></i> Send us a Message
                    </h3>
                    <form id="contactForm" method="POST" action="<?php echo BASE_URL; ?>actions/admin-actions.php">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="action" value="contact">
                        <div class="form-group">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="How can we help?">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Write your message..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Our Address</h4>
                        <p style="margin:0;">123 Book Street, Anna Nagar<br>Chennai, Tamil Nadu 600040</p>
                    </div>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <h4>Phone</h4>
                        <p style="margin:0;">+91 98765 43210<br>Mon - Sat, 9AM - 6PM</p>
                    </div>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p style="margin:0;">hello@bookhaven.com<br>support@bookhaven.com</p>
                    </div>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <h4>Working Hours</h4>
                        <p style="margin:0;">Monday - Saturday: 9AM - 6PM<br>Sunday: Closed</p>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div style="height:220px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                    <div style="text-align:center;">
                        <i class="fas fa-map-marked-alt" style="font-size:2.5rem;margin-bottom:8px;"></i>
                        <p style="margin:0;">Google Maps Integration</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
