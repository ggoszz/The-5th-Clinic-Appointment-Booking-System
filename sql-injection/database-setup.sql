-- SQL Injection Assignment Database Setup
-- This script creates the users table and sample data as specified in the assignment

-- Use the clinic database
USE clinicDB;

-- Create users table for SQL injection demonstration
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data as specified in the assignment
INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('john', 'password1'),
('alice', 'qwerty');

-- Display the created data
SELECT * FROM users;

-- Example queries for demonstration:

-- Normal login (works)
-- SELECT * FROM users WHERE username = 'john' AND password = 'password1';

-- SQL injection examples (vulnerable queries):
-- SELECT * FROM users WHERE username = '' OR '1' = '1' AND password = 'anything';
-- SELECT * FROM users WHERE username = 'admin' --' AND password = 'anything';
-- SELECT * FROM users WHERE username = '' UNION SELECT id, username, password FROM users --' AND password = 'anything';

-- For testing UPDATE vulnerabilities, you might also want a patients table:
CREATE TABLE IF NOT EXISTS patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    date_of_birth DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample patient data
INSERT INTO patients (first_name, last_name, phone, email, date_of_birth) VALUES
('John', 'Smith', '555-0101', 'john.smith@email.com', '1985-03-15'),
('Jane', 'Doe', '555-0102', 'jane.doe@email.com', '1990-07-22'),
('Bob', 'Johnson', '555-0103', 'bob.johnson@email.com', '1978-11-30');

-- Display patient data
SELECT * FROM patients;
