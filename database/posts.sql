USE rmutib;

-- Create a table posts
CREATE TABLE IF NOT EXISTS rmutib.posts (
  post_id VARCHAR(20) PRIMARY KEY,
  post_by VARCHAR(20) ,
  post_title VARCHAR(255),
  post_description TEXT NULL,
  post_img VARCHAR(255) NULL,
  date DATE,
  time TIME
);

-- Create post_id
DELIMITER //
CREATE TRIGGER before_insert_posts BEFORE
INSERT
  ON rmutib.posts FOR EACH ROW BEGIN
SET
  NEW.post_id = CONCAT('p-',LPAD((SELECT IFNULL(MAX(SUBSTRING(post_id, 3)) + 1, 1)FROM posts),8,'0'));
END;
//
DELIMITER ;

