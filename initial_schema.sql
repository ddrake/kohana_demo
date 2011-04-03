CREATE TABLE `albums` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_bin NOT NULL,
  `artist` varchar(50) collate utf8_bin NOT NULL,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `genre_id` (`genre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

INSERT INTO `albums` (`id`, `name`, `artist`, `genre_id`) VALUES
(2, 'Lines, Vines And Trying Times', 'Jonas Brothers', 16),
(3, 'The E.N.D.', 'The Black Eyed Peas', 16),
(4, 'Relapse', 'Eminem', 18),
(5, 'Monuments And Melodies', 'Incubus', 1),
(6, 'Thriller', 'Michael Jackson', 16),
(7, 'Back in Black', 'AC/DC', 4),
(8, 'The Dark Side of the Moon', 'Pink Floyd', 4),
(9, 'Bat out of Hell', 'Meat Loaf', 4),
(10, 'Backstreet Boys', 'Millennium', 16),
(11, 'Rumours', 'Fleetwood Mac', 4),
(12, 'Come on Over', 'Shania Twain', 16),
(13, 'Led Zeppelin IV', 'Led Zeppelin', 4),
(14, 'Jagged Little Pill', 'Alanis Morissette', 4),
(15, 'Sgt. Pepper''s Lonely Hearts Club Band', 'The Beatles', 16),
(16, 'Falling into You', 'Celine Dion', 16),
(17, 'Music Box', 'Mariah Carey', 16),
(18, 'Born in the U.S.A.', 'Bruce Springsteen', 4);

CREATE TABLE `genres` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=22 ;

INSERT INTO `genres` (`id`, `name`) VALUES
(1, 'Alternative Rock'),
(2, 'Blues'),
(3, 'Classical'),
(4, 'Rock'),
(5, 'Country'),
(6, 'Dance'),
(7, 'Folk'),
(8, 'Metal'),
(9, 'Hawaiian'),
(10, 'Imports'),
(11, 'Indie Music'),
(12, 'Jazz'),
(13, 'Latin'),
(14, 'New Age'),
(15, 'Opera'),
(16, 'Pop'),
(17, 'Soul'),
(18, 'Rap'),
(20, 'Soundtracks'),
(21, 'World Music');

ALTER TABLE `albums`
  ADD CONSTRAINT `genre_inter_relational_constraint` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 ;

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'login', 'Login privileges, granted after account confirmation'),
(2, 'admin', 'Administrative user, has access to everything.');

CREATE TABLE IF NOT EXISTS `roles_users` (
  `user_id` INT(10) UNSIGNED NOT NULL,
  `role_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_id` (`role_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(127) NOT NULL,
  `username` VARCHAR(32) NOT NULL DEFAULT '',
  `password` CHAR(64) NOT NULL,
  `logins` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` INT(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `user_tokens` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `user_agent` VARCHAR(40) NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  `created` INT(10) UNSIGNED NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 ;


ALTER TABLE `roles_users`
  ADD CONSTRAINT `roles_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
