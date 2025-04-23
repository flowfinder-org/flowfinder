SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `collections` (
  `id_collection` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(64) NOT NULL,
  `traceur_uuid` VARCHAR(64) NULL DEFAULT NULL,
  `date_creation` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_collection`),
  INDEX `IDX_TRACEUR_UUID` (`traceur_uuid` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `collections_utilisateurs` (
  `id_collection_utilisateur` INT(11) NOT NULL AUTO_INCREMENT,
  `id_collection` INT(11) NOT NULL,
  `id_utilisateur` INT(11) NOT NULL,
  `date_ajout` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_collection_utilisateur`))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `declencheurs` (
  `id_declencheur` INT(11) NOT NULL AUTO_INCREMENT,
  `id_collection` INT(11) NOT NULL,
  `type_declencheur` TINYINT(4) NOT NULL,
  `id_page_configuration` INT(11) NOT NULL,
  `type_page` INT(11) NOT NULL,
  `url_regexp` VARCHAR(128) NOT NULL,
  `seconds_delay` INT(11) NOT NULL,
  `inject_into_elem_id` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_declencheur`))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `geo_ip_cache` (
  `id_geo_ip_cache` INT(11) NOT NULL AUTO_INCREMENT,
  `date_updated` DATETIME NOT NULL,
  `ip` VARCHAR(64) NOT NULL,
  `geo_country` VARCHAR(255) NULL DEFAULT NULL,
  `geo_region` VARCHAR(255) NULL DEFAULT NULL,
  `geo_city` VARCHAR(255) NULL DEFAULT NULL,
  `geo_latitude` DECIMAL(10,6) NULL DEFAULT NULL,
  `geo_longitude` DECIMAL(10,6) NULL DEFAULT NULL,
  `geo_timezone` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id_geo_ip_cache`),
  INDEX `IDX_IP` (`ip` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `page_configuration` (
  `id_page_configuration` INT(11) NOT NULL,
  `actif_page_configuration` TINYINT(4) NOT NULL,
  `id_utilisateur` INT(11) NOT NULL,
  `type_page` INT(11) NOT NULL,
  `json_configuration_page` TEXT NOT NULL,
  `date_modification_page` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `date_creation_page` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  UNIQUE INDEX `index multiple` USING BTREE (`id_utilisateur`, `id_page_configuration`, `type_page`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `page_elements` (
  `id_element` INT(11) NOT NULL,
  `id_utilisateur` INT(11) NOT NULL,
  `type_page` TINYINT(4) NOT NULL COMMENT '1 - Portail | 2 - tunnel prospection | 3 tunnel ..',
  `actif_element` TINYINT(1) NOT NULL,
  `titre_element` VARCHAR(200) NULL DEFAULT NULL,
  `donnee_json_element` TEXT NOT NULL,
  `date_modification_element` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `date_creation_element` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `id_page_configuration` INT(11) NOT NULL,
  UNIQUE INDEX `unique_utilisateur_lien` USING BTREE (`id_utilisateur`, `id_element`, `id_page_configuration`, `type_page`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `page_formdonnees` (
  `id_formdonnees` INT(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` INT(11) NOT NULL,
  `type_page` INT(11) NOT NULL DEFAULT 2,
  `id_page_configuration` INT(11) NOT NULL,
  `json_formdonnees` TEXT NOT NULL,
  `date_soumission` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id_formdonnees`))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `theme` (
  `id_utilisateur` INT(11) NOT NULL,
  `theme_page` VARCHAR(50) NOT NULL,
  `theme_nom` VARCHAR(255) NULL DEFAULT NULL,
  `theme_css` TEXT NOT NULL,
  `date_modification` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  UNIQUE INDEX `unique_user_theme` (`id_utilisateur` ASC, `theme_page` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

INSERT INTO theme(id_utilisateur, theme_page, theme_nom, theme_css, date_modification) VALUES
(0, 'feedback', 'default', 
'{"label":{"color":"#000000"},"nextBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"prevBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"sendBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"page":{"backgroundColor":"#ffffff"},"inputCssPersonnalise":{"inputCssPersonnalise":""}}',
'2025-01-01 00:00:01'
);

INSERT INTO theme(id_utilisateur, theme_page, theme_nom, theme_css, date_modification) VALUES
(0, 'formulaire', 'default', 
'{"label":{"color":"#000000"},"nextBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"prevBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"sendBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"page":{"backgroundColor":"#ffffff"},"inputCssPersonnalise":{"inputCssPersonnalise":""}}',
'2025-01-01 00:00:01'
);

INSERT INTO theme(id_utilisateur, theme_page, theme_nom, theme_css, date_modification) VALUES
(0, 'sondage', 'default', 
'{"label":{"color":"#000000"},"nextBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"prevBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"sendBtn":{"color":"#ffffff","backgroundColor":"#00c4cc"},"page":{"backgroundColor":"#ffffff"},"inputCssPersonnalise":{"inputCssPersonnalise":""}}',
'2025-01-01 00:00:01'
);

CREATE TABLE IF NOT EXISTS `visiteur_sessions` (
  `id_visiteur_session` INT(11) NOT NULL AUTO_INCREMENT,
  `id_visiteur` INT(11) NOT NULL,
  `visiteur_session_uuid` VARCHAR(64) NOT NULL,
  `sequence_index` SMALLINT(6) NOT NULL,
  `date_creation` DATETIME NULL DEFAULT NULL,
  `url` VARCHAR(1024) NULL DEFAULT NULL,
  `user_agent` VARCHAR(2048) NULL DEFAULT NULL,
  `user_ip` VARCHAR(64) NULL DEFAULT NULL,
  `seconds_active` INT(11) NULL DEFAULT NULL,
  `utm_source` VARCHAR(64) NULL DEFAULT NULL,
  `utm_medium` VARCHAR(64) NULL DEFAULT NULL,
  `utm_campaign` VARCHAR(64) NULL DEFAULT NULL,
  `utm_term` VARCHAR(128) NULL DEFAULT NULL,
  `utm_content` VARCHAR(64) NULL DEFAULT NULL,
  `fbclid` VARCHAR(255) NULL DEFAULT NULL,
  `os_family` VARCHAR(100) NULL DEFAULT NULL,
  `os_version` VARCHAR(100) NULL DEFAULT NULL,
  `device_type` VARCHAR(50) NULL DEFAULT NULL,
  `browser_family` VARCHAR(50) NULL DEFAULT NULL,
  `browser_version` VARCHAR(50) NULL DEFAULT NULL,
  `device_brand` VARCHAR(100) NULL DEFAULT NULL,
  `device_model` VARCHAR(100) NULL DEFAULT NULL,
  `geo_country` VARCHAR(255) NULL DEFAULT NULL,
  `geo_region` VARCHAR(255) NULL DEFAULT NULL,
  `geo_city` VARCHAR(255) NULL DEFAULT NULL,
  `geo_latitude` DECIMAL(10,6) NULL DEFAULT NULL,
  `geo_longitude` DECIMAL(10,6) NULL DEFAULT NULL,
  `geo_timezone` VARCHAR(255) NULL DEFAULT NULL,
  `is_bot` TINYINT(1) NULL DEFAULT 0,
  `is_team` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`id_visiteur_session`),
  INDEX `IDX_VISITEUR_SESSION_UUID` (`visiteur_session_uuid` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

ALTER TABLE `visiteur_sessions` 
ADD COLUMN `referer` VARCHAR(1024) NULL DEFAULT NULL AFTER `fbclid`,
ADD COLUMN `screen_width` INT(11) NULL DEFAULT 0 AFTER `geo_timezone`,
ADD COLUMN `screen_height` INT(11) NULL DEFAULT 0 AFTER `screen_width`,
ADD COLUMN `window_width` INT(11) NULL DEFAULT 0 AFTER `screen_height`,
ADD COLUMN `window_height` INT(11) NULL DEFAULT 0 AFTER `window_width`,
ADD COLUMN `pixel_ratio` DECIMAL(6,2) NULL DEFAULT 1.00 AFTER `window_height`,
ADD COLUMN `orientation` VARCHAR(10) NULL DEFAULT '' AFTER `pixel_ratio`,
ADD COLUMN `is_ip_hashed` TINYINT NULL DEFAULT 0 AFTER `is_team`;

CREATE TABLE IF NOT EXISTS `visiteur_sessions_events` (
  `id_visiteur_sessions_events` int(11) NOT NULL AUTO_INCREMENT,
  `id_visiteur_session` int(11) NOT NULL,
  `id_collection` int(11) NOT NULL,
  `event_name` varchar(32) NOT NULL,
  `event_value` varchar(255) NOT NULL DEFAULT '',
  `date_event` datetime NOT NULL,
  PRIMARY KEY (`id_visiteur_sessions_events`))
ENGINE=InnoDB
AUTO_INCREMENT=0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `visiteur_sessions_formdonnees` (
  `id_visiteur_session_formdonnee` INT(11) NOT NULL AUTO_INCREMENT,
  `id_visiteur_session` INT(11) NOT NULL,
  `id_formdonnees` INT(11) NOT NULL,
  `id_collection` INT(11) NOT NULL,
  PRIMARY KEY (`id_visiteur_session_formdonnee`))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS `visiteurs` (
  `id_visiteur` INT(11) NOT NULL AUTO_INCREMENT,
  `id_collection` INT(11) NOT NULL,
  `visiteur_uuid` VARCHAR(64) NOT NULL,
  `date_creation` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_visiteur`),
  UNIQUE INDEX `IDX_VISITEUR_UUID` (`visiteur_uuid` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8mb4;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

