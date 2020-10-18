-- db: watchnow

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  -- `first_name` VARCHAR(30) NOT NULL,
  -- `last_name` VARCHAR(30) NOT NULL,
  -- `image` ,
  -- `user_birthdate` date DEFAULT NULL,
  -- `user_address` text DEFAULT NULL,
  -- `user_city` varchar(250) DEFAULT NULL,
  -- `user_country` varchar(250) DEFAULT NULL
  -- `user_zipcode` varchar(30) DEFAULT NULL,

  `user_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `gender` VARCHAR(10) CHECK (`gender` in ('female','male')),
  `address` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT '0',
  `token` VARCHAR(255) DEFAULT NULL,
  `confirm_code` INT(11),
  `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `role` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY(`id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8;



CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `file_type` VARCHAR(30) NOT NULL,
  `public_id` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `tags` VARCHAR(255) DEFAULT NULL,
  `date_uploaded` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `like` INT(15) NOT NULL DEFAULT 0,
  `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
);

ALTER TABLE `videos`
ADD FOREIGN KEY (`user_id`) REFERENCES users(`id`);

CREATE TABLE `users_videos_like_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `video_id` INT(11) NOT NULL,
  `date_liked` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`),
  CONSTRAINT UC_UserVideo UNIQUE (`user_id`, `video_id`)
);

ALTER TABLE `users_videos_like_map`
ADD FOREIGN KEY (`user_id`) REFERENCES users(`id`);

ALTER TABLE `users_videos_like_map`
ADD FOREIGN KEY (`video_id`) REFERENCES videos(`id`);

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `video_id` INT(11) NOT NULL,
  `content` TEXT NOT NULL,
  `date_commented` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`)
);

ALTER TABLE `comments`
ADD FOREIGN KEY (`user_id`) REFERENCES users(`id`);

ALTER TABLE `comments`
ADD FOREIGN KEY (`video_id`) REFERENCES videos(`id`);

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `reply_content` TEXT NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_replied` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id`,`comment_id`,`user_id`)
);

ALTER TABLE `replies`
ADD FOREIGN KEY `comment_id` REFERENCES `comments`(`id`);

ALTER TABLE `replies`
ADD FOREIGN KEY `user_id` REFERENCES `users`(`id`);
