-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 14 jan. 2023 à 21:59
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tamagotchi_bcl`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `BEDTIME` (IN `tamagotchi_id` INT)  BEGIN
    IF (SELECT sleep FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('bedtime', tamagotchi_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CREATE_ACCOUNT` (IN `name` VARCHAR(255))  BEGIN
    INSERT INTO accounts (name) VALUES (name);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CREATE_TAMAGOTCHI` (IN `name` VARCHAR(255), IN `account_id` INT)  BEGIN
    INSERT INTO tamagotchis (name, account_id) VALUES (name, account_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DRINK` (IN `tamagotchi_id` INT)  BEGIN
    IF (SELECT thirst FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('drink', tamagotchi_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EAT` (IN `tamagotchi_id` INT)  BEGIN
    IF (SELECT hunger FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('eat', tamagotchi_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ENJOY` (IN `tamagotchi_id` INT)  BEGIN
    IF (SELECT boredom FROM tamagotchis WHERE tamagotchis.id = tamagotchi_id) <= 80 THEN
        INSERT INTO actions (name, tamagotchi_id) VALUES ('enjoy', tamagotchi_id);
    END IF;
END$$

--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `IS_ALIVE` (`tamagotchi_id` INT) RETURNS INT(11) BEGIN
    DECLARE alive INT;
        SELECT COUNT(deaths.id) INTO alive  FROM deaths WHERE deaths.tamagotchi_id = tamagotchi_id ;
        RETURN alive;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `LEVEL_CHECK` (`tamagotchi_id` INT) RETURNS INT(11) BEGIN
    DECLARE level INT;
    SELECT FLOOR(COUNT(actions.id)/10)+1 INTO level FROM actions WHERE actions.tamagotchi_id = tamagotchi_id;
    RETURN level;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `accounts`
--

INSERT INTO `accounts` (`id`, `name`) VALUES
(1, 'jean'),
(2, 'marc assin');

-- --------------------------------------------------------

--
-- Structure de la table `actions`
--

CREATE TABLE `actions` (
  `id` int(10) UNSIGNED NOT NULL,
  `tamagotchi_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `actions`
--

INSERT INTO `actions` (`id`, `tamagotchi_id`, `name`) VALUES
(1, 1, 'eat'),
(2, 1, 'bedtime'),
(3, 1, 'drink'),
(4, 1, 'enjoy'),
(5, 1, 'enjoy'),
(6, 1, 'eat'),
(7, 1, 'drink'),
(8, 1, 'bedtime'),
(9, 1, 'drink'),
(10, 1, 'eat'),
(11, 1, 'enjoy'),
(12, 1, 'enjoy'),
(13, 1, 'enjoy'),
(14, 1, 'drink'),
(15, 1, 'bedtime'),
(16, 1, 'eat'),
(17, 1, 'drink'),
(18, 1, 'eat'),
(19, 1, 'bedtime'),
(20, 1, 'drink'),
(21, 1, 'eat'),
(22, 1, 'enjoy'),
(23, 1, 'bedtime'),
(24, 1, 'drink'),
(25, 1, 'eat'),
(26, 1, 'enjoy'),
(27, 1, 'bedtime'),
(28, 1, 'drink');

--
-- Déclencheurs `actions`
--
DELIMITER $$
CREATE TRIGGER `actions_trigger` AFTER INSERT ON `actions` FOR EACH ROW BEGIN
    DECLARE liveforce INT;
    SET liveforce = LEVEL_CHECK(NEW.tamagotchi_id)-1;
    UPDATE tamagotchis 
    SET 
        hunger = CASE NEW.name 
                    WHEN 'eat' THEN IF((hunger + 30 + liveforce) > 100, 100, (hunger + 30 + liveforce))
                    WHEN 'drink' THEN IF( (hunger < (10 + liveforce)), 0, (hunger - 10 - liveforce))
                    WHEN 'bedtime' THEN IF((hunger < (10  + liveforce)), 0, (hunger - 10 - liveforce))
                    WHEN 'enjoy' THEN IF((hunger < (5 + liveforce)), 0, (hunger - 5 - liveforce))
                    ELSE hunger
                END,
        thirst = CASE NEW.name 
                    WHEN 'eat' THEN IF((thirst < (10 + liveforce)), 0, (thirst - 10 - liveforce))
                    WHEN 'drink' THEN IF((thirst + 30 + liveforce) > 100, 100, (thirst + 30 + liveforce))
                    WHEN 'bedtime' THEN IF((thirst < (15 + liveforce)) , 0, (thirst - 15 - liveforce))
                    WHEN 'enjoy' THEN IF((thirst < (5 + liveforce)), 0, (thirst - 5 - liveforce))
                    ELSE thirst
                END,
        sleep = CASE NEW.name 
                    WHEN 'eat' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'drink' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'enjoy' THEN IF((sleep < (5 + liveforce)), 0, (sleep - 5 - liveforce))
                    WHEN 'bedtime' THEN IF((sleep + 30 + liveforce) > 100, 100, (sleep + 30 + liveforce))
                    ELSE sleep
                END,
        boredom = CASE NEW.name 
                    WHEN 'enjoy' THEN IF((boredom + 15 + liveforce) < 0, 0, (boredom + 15 + liveforce))
                    WHEN 'eat' THEN IF((boredom < (5 + liveforce)), 0, (boredom - 5 - liveforce))
                    WHEN 'drink' THEN IF((boredom < (5 + liveforce)), 0, (boredom - 5 - liveforce))
                    WHEN 'bedtime' THEN IF((boredom < (15 + liveforce)), 0, (boredom - 15 - liveforce))
                    ELSE boredom
                END
    WHERE id = NEW.tamagotchi_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `deaths`
--

CREATE TABLE `deaths` (
  `id` int(10) UNSIGNED NOT NULL,
  `tamagotchi_id` int(10) UNSIGNED NOT NULL,
  `deathdate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `deaths`
--

INSERT INTO `deaths` (`id`, `tamagotchi_id`, `deathdate`) VALUES
(1, 1, '2023-01-14 21:47:43');

-- --------------------------------------------------------

--
-- Structure de la table `tamagotchis`
--

CREATE TABLE `tamagotchis` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `hunger` int(10) UNSIGNED NOT NULL DEFAULT 70,
  `thirst` int(10) UNSIGNED NOT NULL DEFAULT 70,
  `sleep` int(10) UNSIGNED NOT NULL DEFAULT 70,
  `boredom` int(10) UNSIGNED NOT NULL DEFAULT 70,
  `birthdate` datetime NOT NULL DEFAULT current_timestamp(),
  `account_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tamagotchis`
--

INSERT INTO `tamagotchis` (`id`, `name`, `hunger`, `thirst`, `sleep`, `boredom`, `birthdate`, `account_id`) VALUES
(1, 'tama1', 69, 92, 93, 0, '2023-01-14 19:09:12', 1),
(2, 'tama2', 70, 70, 70, 70, '2023-01-14 19:09:18', 1),
(3, 'bill', 70, 70, 70, 70, '2023-01-14 21:44:55', 1),
(4, 'jhon', 70, 70, 70, 70, '2023-01-14 21:45:01', 1),
(5, 'sarah croche', 70, 70, 70, 70, '2023-01-14 21:45:17', 1),
(6, 'paul aroid', 70, 70, 70, 70, '2023-01-14 21:45:38', 1),
(7, 'jean phil', 70, 70, 70, 70, '2023-01-14 21:45:57', 1),
(8, 'justin bridou', 70, 70, 70, 70, '2023-01-14 21:49:47', 2);

--
-- Déclencheurs `tamagotchis`
--
DELIMITER $$
CREATE TRIGGER `tamagotchi_deaths_trigger` AFTER UPDATE ON `tamagotchis` FOR EACH ROW BEGIN
    DECLARE death_inserted BOOLEAN DEFAULT FALSE;
    IF (NEW.hunger = 0 OR NEW.thirst = 0 OR NEW.sleep = 0 OR NEW.boredom = 0) THEN
    INSERT INTO deaths (tamagotchi_id) 
    SELECT NEW.id
    WHERE NOT EXISTS (SELECT 1 FROM deaths WHERE tamagotchi_id = NEW.id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `tamagotchi_life`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `tamagotchi_life` (
`id` int(10) unsigned
,`deathdate` datetime
,`birthdate` datetime
,`eat_count` bigint(21)
,`drink_count` bigint(21)
,`bedtime_count` bigint(21)
,`enjoy_count` bigint(21)
,`level` int(11)
);

-- --------------------------------------------------------

--
-- Structure de la vue `tamagotchi_life`
--
DROP TABLE IF EXISTS `tamagotchi_life`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tamagotchi_life`  AS SELECT `tamagotchis`.`id` AS `id`, `deaths`.`deathdate` AS `deathdate`, `tamagotchis`.`birthdate` AS `birthdate`, count(case when `actions`.`name` = 'eat' then 1 end) AS `eat_count`, count(case when `actions`.`name` = 'drink' then 1 end) AS `drink_count`, count(case when `actions`.`name` = 'bedtime' then 1 end) AS `bedtime_count`, count(case when `actions`.`name` = 'enjoy' then 1 end) AS `enjoy_count`, (select `LEVEL_CHECK`(`tamagotchis`.`id`)) AS `level` FROM ((`tamagotchis` left join `deaths` on(`tamagotchis`.`id` = `deaths`.`tamagotchi_id`)) join `actions` on(`tamagotchis`.`id` = `actions`.`tamagotchi_id`)) GROUP BY `tamagotchis`.`id`, `deaths`.`deathdate`, `tamagotchis`.`birthdate` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deaths`
--
ALTER TABLE `deaths`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tamagotchi_id` (`tamagotchi_id`);

--
-- Index pour la table `tamagotchis`
--
ALTER TABLE `tamagotchis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `deaths`
--
ALTER TABLE `deaths`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tamagotchis`
--
ALTER TABLE `tamagotchis`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
