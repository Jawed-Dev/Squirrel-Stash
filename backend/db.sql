--
-- Structure de la table `threshold`
--

DROP TABLE IF EXISTS `threshold`;
CREATE TABLE IF NOT EXISTS `threshold` (
  `threshold_id` int NOT NULL AUTO_INCREMENT,
  `threshold_user_id` int NOT NULL DEFAULT '0',
  `threshold_amount` int NOT NULL,
  `threshold_date` date NOT NULL,
  PRIMARY KEY (`threshold_id`),
  KEY `threshold_user_id` (`threshold_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Structure de la table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `transaction_user_id` int NOT NULL DEFAULT '0',
  `transaction_amount` decimal(8,2) NOT NULL,
  `transaction_type_money` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Euro',
  `transaction_category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `transaction_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`transaction_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Structure de la table `update_email`
--

DROP TABLE IF EXISTS `update_email`;
CREATE TABLE IF NOT EXISTS `update_email` (
  `update_email_id` int NOT NULL AUTO_INCREMENT,
  `update_email_token` varchar(255) NOT NULL,
  `update_email_createdAt` datetime NOT NULL,
  `update_email_expireAt` datetime NOT NULL,
  `update_email_user_id` int NOT NULL,
  `update_email_new_email` varchar(255) NOT NULL,
  PRIMARY KEY (`update_email_id`),
  KEY `update_email_user_id` (`update_email_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



--
-- Structure de la table `update_password`
--

DROP TABLE IF EXISTS `update_password`;
CREATE TABLE IF NOT EXISTS `update_password` (
  `update_pass_id` int NOT NULL AUTO_INCREMENT,
  `update_pass_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `update_pass_createdAt` datetime NOT NULL,
  `update_pass_expireAt` datetime NOT NULL,
  `update_pass_user_id` int NOT NULL,
  `update_pass_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`update_pass_id`),
  KEY `update_pass_user_id` (`update_pass_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_email` varchar(254) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_birthday` date NOT NULL,
  `user_gender` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Non défini',
  `user_role_level` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour la table `threshold`
--
ALTER TABLE `threshold`
  ADD CONSTRAINT `threshold_ibfk_1` FOREIGN KEY (`threshold_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`transaction_user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `update_email`
--
ALTER TABLE `update_email`
  ADD CONSTRAINT `update_email_ibfk_1` FOREIGN KEY (`update_email_user_id`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `update_password`
--
ALTER TABLE `update_password`
  ADD CONSTRAINT `update_password_ibfk_1` FOREIGN KEY (`update_pass_user_id`) REFERENCES `user` (`user_id`);
COMMIT;