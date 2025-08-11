-- Intern Connect Database Schema

CREATE DATABASE IF NOT EXISTS internconnect;
USE internconnect;

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    cv_filename VARCHAR(255) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    admin_status ENUM('pending', 'reviewed', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data for testing
INSERT INTO applications (full_name, email, phone, duration, cv_filename, payment_status, admin_status) VALUES
('John Doe', 'john@example.com', '+1234567890', '3 months', 'sample_cv.pdf', 'completed', 'pending'),
('Jane Smith', 'jane@example.com', '+0987654321', '6 months', 'sample_cv2.pdf', 'pending', 'pending');
