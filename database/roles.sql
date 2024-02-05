USE rmutib;

-- Create a table for roles
CREATE TABLE IF NOT EXISTS rmutib.roles (
    id_role INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role_id INT(5) NOT NULL,
    role_name VARCHAR(100) NULL
);

-- Insert roles into the roles table
INSERT INTO rmutib.roles (role_id, role_name) VALUES (100, 'User');
INSERT INTO rmutib.roles (role_id, role_name) VALUES (900, 'Admin');