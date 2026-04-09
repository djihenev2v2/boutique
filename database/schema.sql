-- ============================================================
-- BOUTIQUE EN LIGNE — Script SQL (Auth)
-- Base de données : MySQL
-- Les autres tables seront ajoutées au fur et à mesure
-- ============================================================

-- ============================================================
-- TABLE : users (admin + clients)
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    `phone` VARCHAR(20) NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `wilaya_id` INT UNSIGNED NULL DEFAULT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : password_reset_tokens
-- ============================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : sessions (pour le driver session "database")
-- ============================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED : Admin par défaut
-- Email : admin@boutique.com
-- Mot de passe : password
-- ============================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `email_verified_at`, `created_at`, `updated_at`)
VALUES (
    'Admin Boutique',
    'admin@boutique.com',
    '$2y$10$JJX8xXEabyaY5ZSFPLOQPudQZhZnx7LeD0Xi4/gBiUMSkZ6xTaqDK',
    'admin',
    NOW(),
    NOW(),
    NOW()
);
