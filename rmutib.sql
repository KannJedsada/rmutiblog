
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
CREATE DATABASE IF NOT EXISTS `rmuti_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `rmuti_db`;


CREATE TABLE `comments` (
  `comment_id` varchar(20) NOT NULL,
  `post_id` varchar(20) DEFAULT NULL,
  `users_id` varchar(20) DEFAULT NULL,
  `comment_img` varchar(255) DEFAULT NULL,
  `comment_desc` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER $$
CREATE TRIGGER `before_insert_comments` BEFORE INSERT ON `comments` FOR EACH ROW BEGIN
    SET NEW.comment_id = CONCAT('c-', LPAD((SELECT IFNULL(MAX(SUBSTRING(comment_id, 3)) + 1, 1) FROM rmutib.comments), 8, '0'));
END
$$
DELIMITER ;

CREATE TABLE `isactive` (
  `isActiveid` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `isactive` (`isActiveid`, `status`) VALUES
(0, 'No Actice'),
(1, 'Active');

CREATE TABLE `posts` (
  `post_id` varchar(20) NOT NULL,
  `users_id` varchar(20) DEFAULT NULL,
  `post_title` varchar(255) DEFAULT NULL,
  `post_description` text DEFAULT NULL,
  `post_img` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER $$
CREATE TRIGGER `before_insert_posts` BEFORE INSERT ON `posts` FOR EACH ROW BEGIN
SET
  NEW.post_id = CONCAT('p-',LPAD((SELECT IFNULL(MAX(SUBSTRING(post_id, 3)) + 1, 1)FROM posts),8,'0'));
END
$$
DELIMITER ;

CREATE TABLE `roleid` (
  `role_id` int(11) NOT NULL PRIMARY KEY,
  `role_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roleid` (`role_id`, `role_status`) VALUES
(100, 'user'),
(900, 'admin'),
(999, 'super admin');

CREATE TABLE `users` (
  `user_id` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `role_id` int(5) DEFAULT 100,
  `isActive` int(1) DEFAULT 1,
  `dateCreate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `profile_img`, `role_id`, `isActive`, `dateCreate`) VALUES
('r-00000001', 'superadmin', '12345678', 'admin@g.com', NULL, 999, 1, '2024-01-15');
-- ('r-00000009', 'kann2', '123456', 'teste12@gma.com', NULL, 900, 1, '2024-02-03'),
-- ('r-00000010', 'asdf', '12345', 'ede@gm.com', NULL, 100, 0, '2024-02-12'),
-- ('r-00000011', 'adsf', '12345', 'ws@fd.com', NULL, 100, 1, '2024-02-12'),
-- ('r-00000013', 'name2', '12345', 'name2@gmail.com', NULL, 100, 1, '2024-02-13'),
-- ('r-00000014', 'ert', '12345', 'jed@g.com', NULL, 100, 1, '2024-02-13'),
-- ('r-00000015', 'tefs', '12345', '1s@g.com', NULL, 100, 1, '2024-02-13'),
-- ('r-00000016', 'fsa', '12345', 'fsc@g.com', NULL, 100, 1, '2024-02-13'),
-- ('r-00000017', 'ggh', '12345', 'vfx@g.com', NULL, 100, 1, '2024-02-13'),
-- ('r-00000018', 'test', '12345', 'de2@g.com', '1204713215.png', 100, 1, '2024-02-17'),
-- ('r-00000020', 'as1', '12345', 'jde@gm.com', NULL, 100, 1, '2024-02-20'),
-- ('r-00000021', 'kjk', '12345', 'jed!@g.com', NULL, 100, 1, '2024-02-26'),
-- ('r-00000022', 'ws1', '12345', 'jde@a.com', NULL, 100, 1, '2024-02-27'),
-- ('r-00000023', 'ad', '12345', 'de@f.com', NULL, 100, 1, '2024-02-27');

-- --
-- -- Triggers `users`
-- --
DELIMITER $$
CREATE TRIGGER `before_insert_users` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    SET NEW.user_id = CONCAT('r-', LPAD((SELECT IFNULL(MAX(SUBSTRING(user_id, 3)) + 1, 1) FROM rmutib.users), 8, '0'));
END
$$DELIMITER ;

ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_comments_posts` (`post_id`),
  ADD KEY `fk_comments_users` (`users_id`);

ALTER TABLE `isactive`
  ADD PRIMARY KEY (`isActiveid`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_posts_users` (`users_id`);

ALTER TABLE `roleid`
  ADD PRIMARY KEY (`role_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_roleid` (`role_id`),
  ADD KEY `active_id` (`isActive`);

--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
ALTER TABLE `users`
  ADD CONSTRAINT `active_id` FOREIGN KEY (`isActive`) REFERENCES `isactive` (`isActiveid`),
  ADD CONSTRAINT `fk_roleid` FOREIGN KEY (`role_id`) REFERENCES `roleid` (`role_id`);
COMMIT;

