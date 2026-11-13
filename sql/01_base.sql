-- Mise a jour de la base concours_db (peut etre execute plusieurs fois sans danger)

UPDATE roles SET libelle = 'controleur' WHERE libelle = 'controlleur';

CREATE TABLE IF NOT EXISTS `jurys` (
  `id_jury`   int(11)      NOT NULL AUTO_INCREMENT,
  `nom_jury`  varchar(100) NOT NULL,
  `fonction`  varchar(100) DEFAULT NULL,
  `id_salle`  int(11)      DEFAULT NULL,
  `id_annee`  int(11)      NOT NULL,
  `niveau`    varchar(20)  DEFAULT NULL,
  `type_jury` varchar(20)  DEFAULT NULL,
  `capacite`  int(11)      DEFAULT NULL,
  `actif`     tinyint(1)   DEFAULT 1,
  PRIMARY KEY (`id_jury`),
  KEY `idx_jurys_salle` (`id_salle`),
  KEY `idx_jurys_annee` (`id_annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `journal_activite` (
  `id`              bigint(20)   NOT NULL AUTO_INCREMENT,
  `id_utilisateur`  int(11)      DEFAULT NULL,
  `nom_utilisateur` varchar(150) NOT NULL,
  `action`          varchar(40)  NOT NULL,
  `entite`          varchar(40)  DEFAULT NULL,
  `id_entite`       int(11)      DEFAULT NULL,
  `details`         text         DEFAULT NULL,
  `ip`              varchar(45)  DEFAULT NULL,
  `created_at`      datetime     NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_journal_action` (`action`),
  KEY `idx_journal_user` (`id_utilisateur`),
  KEY `idx_journal_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id`             int(11)      NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11)      NOT NULL,
  `message`        varchar(255) NOT NULL,
  `lien`           varchar(255) DEFAULT NULL,
  `lu`             tinyint(1)   NOT NULL DEFAULT 0,
  `created_at`     datetime     NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notif_dest` (`id_utilisateur`, `lu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
