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



-- ============================================================
-- TABLE : categories
-- ============================================================
DROP TABLE IF EXISTS `variant_attribute_values`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `product_attribute_values`;
DROP TABLE IF EXISTS `product_attributes`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(255) NOT NULL,
    `slug`       VARCHAR(255) NOT NULL,
    `parent_id`  INT UNSIGNED NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `categories_slug_unique` (`slug`),
    KEY `categories_parent_id_index` (`parent_id`),
    CONSTRAINT `categories_parent_id_foreign`
        FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : products
-- ============================================================
CREATE TABLE `products` (
    `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(255) NOT NULL,
    `slug`        VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `category_id` INT UNSIGNED NULL DEFAULT NULL,
    `brand`       VARCHAR(100) NULL DEFAULT NULL,
    `base_price`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `products_slug_unique` (`slug`),
    KEY `products_category_id_index` (`category_id`),
    CONSTRAINT `products_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : product_images
-- ============================================================
CREATE TABLE `product_images` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `path`       VARCHAR(500) NOT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    KEY `product_images_product_id_index` (`product_id`),
    CONSTRAINT `product_images_product_id_foreign`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : product_attributes  (ex: Couleur, Taille, Pointure)
-- ============================================================
CREATE TABLE `product_attributes` (
    `id`   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    UNIQUE KEY `product_attributes_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : product_attribute_values  (ex: Noir, Blanc, S, M, 42)
-- ============================================================
CREATE TABLE `product_attribute_values` (
    `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `attribute_id` INT UNSIGNED NOT NULL,
    `value`        VARCHAR(100) NOT NULL,
    UNIQUE KEY `pav_attribute_value_unique` (`attribute_id`, `value`),
    KEY `pav_attribute_id_index` (`attribute_id`),
    CONSTRAINT `pav_attribute_id_foreign`
        FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : product_variants
-- ============================================================
CREATE TABLE `product_variants` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `sku`        VARCHAR(100) NULL DEFAULT NULL,
    `price`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `stock`      INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `product_variants_sku_unique` (`sku`),
    KEY `product_variants_product_id_index` (`product_id`),
    CONSTRAINT `product_variants_product_id_foreign`
        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : variant_attribute_values  (table pivot)
-- ============================================================
CREATE TABLE `variant_attribute_values` (
    `variant_id`         BIGINT UNSIGNED NOT NULL,
    `attribute_value_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`variant_id`, `attribute_value_id`),
    CONSTRAINT `vav_variant_id_foreign`
        FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `vav_attribute_value_id_foreign`
        FOREIGN KEY (`attribute_value_id`) REFERENCES `product_attribute_values` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED : Attributs par défaut
-- ============================================================
INSERT INTO `product_attributes` (`name`) VALUES ('Couleur'), ('Taille'), ('Pointure');

