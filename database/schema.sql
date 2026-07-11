-- Online Book Store database schema and sample data

CREATE DATABASE IF NOT EXISTS online_bookstore;
USE online_bookstore;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100),
    description TEXT
);

CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    cover_image VARCHAR(255),
    stock INT DEFAULT 0,
    isbn VARCHAR(20),
    pages INT,
    publisher VARCHAR(100),
    rating DECIMAL(2,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('processing','shipped','delivered','cancelled') DEFAULT 'processing',
    payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    book_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    book_id INT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@bookhaven.com', '$2y$10$6bnb2W6.fZrzQBYgRnJlZuAnU2.oEGihD.7mL.ZXgnC40Jks9fa2q', 'admin'),
('User', 'user@bookhaven.com', '$2y$10$ryae/2BD71ytNQO3ZUkzde2dTB2BeBWYiOBfCEgej9r.5i9kBTw/K', 'user');

INSERT INTO categories (name, slug) VALUES
('Fiction','fiction'),
('Non-Fiction','non-fiction'),
('Science & Technology','science-technology'),
('Business & Economics','business-economics'),
('Self-Help','self-help'),
('Children','children'),
('Mystery & Thriller','mystery-thriller'),
('Romance','romance');

INSERT INTO books (title, author, description, price, category_id, cover_image, stock, isbn, pages, publisher, rating, created_at) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'A story of the fabulously wealthy Jay Gatsby and his love for a beautiful woman during the Roaring Twenties.', 299.00, 1, 'great_gatsby.jpg', 25, '978-0743273565', 180, 'Scribner', 4.5, NOW()),
('To Kill a Mockingbird', 'Harper Lee', 'The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it.', 350.00, 1, 'to_kill_a_mockingbird.jpg', 18, '978-0061120084', 281, 'Harper Perennial', 4.8, NOW()),
('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'A groundbreaking narrative of humanity''s creation and evolution that explores how biology and history have defined us.', 499.00, 2, 'sapiens_a_brief_history_of_humankind.jpg', 30, '978-0062316097', 464, 'Harper', 4.7, NOW()),
('Atomic Habits', 'James Clear', 'An easy and proven way to build good habits and break bad ones. Transform your life with tiny changes.', 399.00, 5, 'atomic_habits.jpg', 45, '978-0735211292', 320, 'Avery', 4.9, NOW()),
('The Alchemist', 'Paulo Coelho', 'A magical story about Santiago, an Andalusian shepherd boy, who yearns to travel in search of a worldly treasure.', 275.00, 1, 'the_alchemist.jpg', 35, '978-0062315007', 197, 'HarperOne', 4.6, NOW()),
('Clean Code', 'Robert C. Martin', 'A handbook of agile software craftsmanship. Even bad code can function, but clean code is the mark of a true professional.', 599.00, 3, 'clean_code.jpg', 20, '978-0132350884', 464, 'Prentice Hall', 4.7, NOW()),
('Dune', 'Frank Herbert', 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides, who is destined for a great purpose.', 450.00, 1, 'dune.jpg', 15, '978-0441013593', 688, 'Ace', 4.6, NOW()),
('Rich Dad Poor Dad', 'Robert T. Kiyosaki', 'What the rich teach their kids about money — that the poor and middle class do not! A timeless personal finance classic.', 350.00, 4, 'rich_dad_poor_dad.jpg', 40, '978-1612680194', 336, 'Plata Publishing', 4.5, NOW()),
('A Brief History of Time', 'Stephen Hawking', 'From the Big Bang to black holes, a landmark volume in science writing by one of the great minds of our time.', 425.00, 3, 'a_brief_history_of_time.jpg', 12, '978-0553380163', 212, 'Bantam', 4.6, NOW()),
('It Ends with Us', 'Colleen Hoover', 'A brave and heartbreaking novel that digs its claws into you and doesn''t let go.', 310.00, 8, 'it_ends_with_us.jpg', 33, '978-1501110368', 384, 'Atria Books', 4.5, NOW());
