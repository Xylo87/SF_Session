-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour sf_session
CREATE DATABASE IF NOT EXISTS `sf_session` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sf_session`;

-- Listage de la structure de table sf_session. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.categorie : ~4 rows (environ)
INSERT INTO `categorie` (`id`, `nom`) VALUES
	(1, 'Virologie'),
	(2, 'Combat'),
	(3, 'Survie'),
	(4, 'Médical');

-- Listage de la structure de table sf_session. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table sf_session.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20250128103125', '2025-01-28 10:32:36', 679);

-- Listage de la structure de table sf_session. formateur
CREATE TABLE IF NOT EXISTS `formateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.formateur : ~0 rows (environ)
INSERT INTO `formateur` (`id`, `nom`, `prenom`, `email`) VALUES
	(1, 'Valentine', 'Jill', 'j.valentine@umbrella-edu.com'),
	(2, 'Burton', 'Barry', 'b.burton@umbrella-edu.com'),
	(3, 'Chambers', 'Rebecca', 'r.chambers@umbrella-edu.com');

-- Listage de la structure de table sf_session. formation
CREATE TABLE IF NOT EXISTS `formation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.formation : ~0 rows (environ)
INSERT INTO `formation` (`id`, `nom`) VALUES
	(1, 'Sécurité Biologique Avancée'),
	(2, 'Protocoles d\'Intervention Tactique'),
	(3, 'Gestion des Risques Viraux');

-- Listage de la structure de table sf_session. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table sf_session. module
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C242628BCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_C242628BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.module : ~0 rows (environ)
INSERT INTO `module` (`id`, `categorie_id`, `nom`) VALUES
	(1, 1, 'Introduction aux virus-T'),
	(2, 2, 'Techniques de combat rapproché'),
	(3, 4, 'Premiers secours en zone contaminée'),
	(4, 3, 'Survie en milieu hostile'),
	(5, 2, 'Maniement des armes à feu');

-- Listage de la structure de table sf_session. programme
CREATE TABLE IF NOT EXISTS `programme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` int NOT NULL,
  `module_id` int NOT NULL,
  `nb_jours_module` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3DDCB9FF613FECDF` (`session_id`),
  KEY `IDX_3DDCB9FFAFC2B591` (`module_id`),
  CONSTRAINT `FK_3DDCB9FF613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`),
  CONSTRAINT `FK_3DDCB9FFAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.programme : ~0 rows (environ)
INSERT INTO `programme` (`id`, `session_id`, `module_id`, `nb_jours_module`) VALUES
	(1, 1, 1, 5),
	(2, 1, 3, 3),
	(3, 1, 4, 4),
	(4, 2, 2, 7),
	(5, 2, 4, 4),
	(6, 2, 5, 6),
	(7, 3, 1, 5),
	(8, 3, 3, 3),
	(9, 4, 1, 5),
	(10, 4, 3, 3),
	(11, 4, 4, 4),
	(12, 5, 2, 7),
	(13, 5, 4, 4),
	(14, 5, 5, 6);

-- Listage de la structure de table sf_session. session
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `formation_id` int NOT NULL,
  `formateur_id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_places` int NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D044D5D45200282E` (`formation_id`),
  KEY `IDX_D044D5D4155D8F51` (`formateur_id`),
  CONSTRAINT `FK_D044D5D4155D8F51` FOREIGN KEY (`formateur_id`) REFERENCES `formateur` (`id`),
  CONSTRAINT `FK_D044D5D45200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.session : ~0 rows (environ)
INSERT INTO `session` (`id`, `formation_id`, `formateur_id`, `nom`, `nb_places`, `date_debut`, `date_fin`) VALUES
	(1, 1, 1, 'SBA-2025-09', 12, '2025-09-15 00:00:00', '2025-12-15 00:00:00'),
	(2, 2, 2, 'PIT-2024-11', 8, '2024-11-15 00:00:00', '2025-04-15 00:00:00'),
	(3, 3, 3, 'GRV-2024-09', 15, '2024-09-01 00:00:00', '2024-12-01 00:00:00'),
	(4, 1, 3, 'SBA-2024-12', 10, '2024-12-01 00:00:00', '2025-03-01 00:00:00'),
	(5, 2, 2, 'PIT-2025-03', 8, '2025-03-01 00:00:00', '2025-06-01 00:00:00');

-- Listage de la structure de table sf_session. session_stagiaire
CREATE TABLE IF NOT EXISTS `session_stagiaire` (
  `session_id` int NOT NULL,
  `stagiaire_id` int NOT NULL,
  PRIMARY KEY (`session_id`,`stagiaire_id`),
  KEY `IDX_C80B23B613FECDF` (`session_id`),
  KEY `IDX_C80B23BBBA93DD6` (`stagiaire_id`),
  CONSTRAINT `FK_C80B23B613FECDF` FOREIGN KEY (`session_id`) REFERENCES `session` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C80B23BBBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.session_stagiaire : ~0 rows (environ)
INSERT INTO `session_stagiaire` (`session_id`, `stagiaire_id`) VALUES
	(1, 1),
	(1, 3),
	(1, 6),
	(2, 2),
	(2, 5),
	(3, 1),
	(3, 4),
	(3, 7),
	(4, 2),
	(4, 4),
	(4, 6),
	(5, 3),
	(5, 5),
	(5, 7);

-- Listage de la structure de table sf_session. stagiaire
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_nais` datetime NOT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sf_session.stagiaire : ~0 rows (environ)
INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `date_nais`, `ville`, `email`, `tel`) VALUES
	(1, 'Redfield', 'Claire', '1979-10-15 00:00:00', 'Raccoon City', 'claire.redfield@gmail.com', '555-0123'),
	(2, 'Kennedy', 'Leon', '1977-03-27 00:00:00', 'Washington DC', 'leon.kennedy@protonmail.com', '555-0124'),
	(3, 'Wong', 'Ada', '1974-08-11 00:00:00', 'Chicago', 'ada.wong@outlook.com', '555-0125'),
	(4, 'Birkin', 'Sherry', '1986-04-03 00:00:00', 'Raccoon City', 'sherry.birkin@gmail.com', '555-0126'),
	(5, 'Oliveira', 'Carlos', '1977-12-22 00:00:00', 'New York', 'carlos.oliveira@yahoo.com', '555-0127'),
	(6, 'Graham', 'Ashley', '1984-09-08 00:00:00', 'Washington DC', 'ashley.graham@gmail.com', '555-0128'),
	(7, 'Aiken', 'Richard', '1973-05-19 00:00:00', 'Boston', 'richard.aiken@hotmail.com', '555-0129');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
