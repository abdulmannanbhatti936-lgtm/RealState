-- Database Tables and Data
-- Users Table (Admin)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Properties Table
CREATE TABLE IF NOT EXISTS `properties` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(100) NOT NULL,
    `location` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `status` ENUM('Available', 'Sold', 'Rent') DEFAULT 'Available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Contacts / Leads Table
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `property_title` VARCHAR(255),
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Initial Admin User (password: admin123)
-- Hash generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO `users` (`name`, `email`, `password`)
VALUES (
        'Admin',
        'admin@example.com',
        '$2y$10$8W3Wb/F1UvY.89Fw0XvBaeG8xI6z4L2ZkE/u2wY/cE4zGvqg9X8y.'
    ) ON DUPLICATE KEY
UPDATE name =
VALUES(name);
-- Initial Dummy Properties
INSERT INTO `properties` (
        `title`,
        `location`,
        `price`,
        `image`,
        `description`,
        `status`
    )
VALUES (
        'Luxury Villa in DHA Phase 6',
        'Karachi, Sindh',
        75000000.00,
        'img1.jpg',
        'Exclusive 500 sq yard bungalow featuring ultra-modern architecture and premium finishes in one of Karachi\'s most elite neighborhoods.',
        'Available'
    ),
    (
        'Modern Apartment in Gulberg III',
        'Lahore, Punjab',
        25000000.00,
        'img2.jpg',
        'High-rise living with a view. Fully equipped with modern amenities and designated parking in the heart of Lahore.',
        'Available'
    ),
    (
        'Executive Office in Blue Area',
        'Islamabad, ICT',
        45000000.00,
        'img3.jpg',
        'Prime commercial space in the heart of the capital city, perfect for corporate headquarters and high-profile businesses.',
        'Available'
    );