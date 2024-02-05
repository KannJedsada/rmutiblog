USE rmutib;

-- Create a table comments
CREATE TABLE IF NOT EXISTS rmutib.comments (
    comment_id VARCHAR(20) PRIMARY KEY,
    comment_in VARCHAR(20),
    comment_by VARCHAR(20),
    comment_img VARCHAR(255) NULL,
    comment_desc TEXT NULL,
    date DATE,
    time TIME
);

-- Create comment_id trigger
DELIMITER //
CREATE TRIGGER before_insert_comments
BEFORE INSERT ON rmutib.comments
FOR EACH ROW
BEGIN
    SET NEW.comment_id = CONCAT('c-', LPAD((SELECT IFNULL(MAX(SUBSTRING(comment_id, 3)) + 1, 1) FROM rmutib.comments), 8, '0'));
END;
//
DELIMITER ;