-- Tracking module database schema for FrontAccounting

CREATE TABLE IF NOT EXISTS `0_tracking_events` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `visitor_id` VARCHAR(100) NOT NULL,
    `contact_id` INT(11) DEFAULT NULL,
    `event_type` VARCHAR(50) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `visitor_id` (`visitor_id`),
    KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `0_tracking_visitors` (
    `id` VARCHAR(100) NOT NULL,
    `first_visit` DATETIME NOT NULL,
    `last_visit` DATETIME NOT NULL,
    `visit_count` INT(4) NOT NULL DEFAULT 1,
    `contact_id` INT(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

