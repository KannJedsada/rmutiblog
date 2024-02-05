-- Create a database
CREATE DATABASE IF NOT EXISTS rmutib;

-- Use the database
USE rmutib;

-- Create a table users
CREATE TABLE IF NOT EXISTS rmutib.users (
    user_id VARCHAR(20) PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    profile_img VARCHAR(255) NULL,
    role_id INT(5) DEFAULT 100,
    isActive INT(1) DEFAULT 1
);

-- Create user_id trigger
DELIMITER //
CREATE TRIGGER before_insert_users BEFORE 
INSERT 
    ON rmutib.users FOR EACH ROW
BEGIN
SET 
    NEW.user_id = CONCAT('r-', LPAD((SELECT IFNULL(MAX(SUBSTRING(user_id, 3)) + 1, 1) FROM rmutib.users),8, '0'));
END;
//
DELIMITER ;

-- Create a table posts
CREATE TABLE IF NOT EXISTS rmutib.posts (
    post_id VARCHAR(20) PRIMARY KEY,
    post_by VARCHAR(20),
    post_title VARCHAR(255),
    post_description TEXT NULL,
    post_img VARCHAR(255) NULL,
    date DATE,
    time TIME
);