-- ============================================================
-- BOUTIQUE EN LIGNE — Script SQL (Auth)
-- Base de données : MySQL
-- Les autres tables seront ajoutées au fur et à mesure
-- ============================================================

-- ============================================================
-- TABLE : users (admin uniquement)
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin') NOT NULL DEFAULT 'admin',
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



-- ============================================================
-- Section COMMANDES — Wilayas, Codes promo, Commandes
-- ============================================================

DROP TABLE IF EXISTS `order_status_history`;
DROP TABLE IF EXISTS `shipments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `promo_codes`;
DROP TABLE IF EXISTS `wilayas`;

-- ============================================================
-- TABLE : wilayas (58 wilayas algériennes)
-- ============================================================
CREATE TABLE `wilayas` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code`          VARCHAR(3) NOT NULL,
    `name`          VARCHAR(100) NOT NULL,
    `shipping_cost` DECIMAL(10,2) NOT NULL DEFAULT 400.00,
    `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP NULL DEFAULT NULL,
    `updated_at`    TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `wilayas_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED : 58 Wilayas
-- ============================================================
INSERT INTO `wilayas` (`code`, `name`, `shipping_cost`) VALUES
('01', 'Adrar',              600.00),
('02', 'Chlef',              400.00),
('03', 'Laghouat',           500.00),
('04', 'Oum El Bouaghi',     450.00),
('05', 'Batna',              450.00),
('06', 'Béjaïa',             400.00),
('07', 'Biskra',             500.00),
('08', 'Béchar',             600.00),
('09', 'Blida',              350.00),
('10', 'Bouira',             400.00),
('11', 'Tamanrasset',        700.00),
('12', 'Tébessa',            500.00),
('13', 'Tlemcen',            500.00),
('14', 'Tiaret',             450.00),
('15', 'Tizi Ouzou',         400.00),
('16', 'Alger',              300.00),
('17', 'Djelfa',             450.00),
('18', 'Jijel',              450.00),
('19', 'Sétif',              450.00),
('20', 'Saïda',              500.00),
('21', 'Skikda',             450.00),
('22', 'Sidi Bel Abbès',     500.00),
('23', 'Annaba',             450.00),
('24', 'Guelma',             450.00),
('25', 'Constantine',        450.00),
('26', 'Médéa',              400.00),
('27', 'Mostaganem',         450.00),
('28', 'Msila',              450.00),
('29', 'Mascara',            500.00),
('30', 'Ouargla',            550.00),
('31', 'Oran',               400.00),
('32', 'El Bayadh',          600.00),
('33', 'Illizi',             700.00),
('34', 'Bordj Bou Arreridj', 450.00),
('35', 'Boumerdès',          350.00),
('36', 'El Tarf',            450.00),
('37', 'Tindouf',            700.00),
('38', 'Tissemsilt',         450.00),
('39', 'El Oued',            550.00),
('40', 'Khenchela',          500.00),
('41', 'Souk Ahras',         450.00),
('42', 'Tipaza',             350.00),
('43', 'Mila',               450.00),
('44', 'Aïn Defla',          400.00),
('45', 'Naâma',              600.00),
('46', 'Aïn Témouchent',     450.00),
('47', 'Ghardaïa',           550.00),
('48', 'Relizane',           450.00),
('49', 'Timimoun',           650.00),
('50', 'Bordj Badji Mokhtar',750.00),
('51', 'Ouled Djellal',      550.00),
('52', 'Béni Abbès',         650.00),
('53', 'In Salah',           700.00),
('54', 'In Guezzam',         750.00),
('55', 'Touggourt',          550.00),
('56', 'Djanet',             700.00),
('57', 'El M\'Ghair',        550.00),
('58', 'El Meniaa',          600.00);

-- ============================================================
-- TABLE : promo_codes
-- ============================================================
CREATE TABLE `promo_codes` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code`          VARCHAR(50) NOT NULL,
    `discount`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `min_order`     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `is_percentage` TINYINT(1) NOT NULL DEFAULT 0,
    `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
    `used_count`    INT UNSIGNED NOT NULL DEFAULT 0,
    `max_uses`      INT UNSIGNED NULL DEFAULT NULL,
    `expires_at`    TIMESTAMP NULL DEFAULT NULL,
    `created_at`    TIMESTAMP NULL DEFAULT NULL,
    `updated_at`    TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `promo_codes_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : orders
-- ============================================================
CREATE TABLE `orders` (
    `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number`    VARCHAR(20) NOT NULL,
    -- Snapshot client au moment de la commande
    `customer_name`   VARCHAR(255) NOT NULL,
    `customer_phone`  VARCHAR(20) NOT NULL,
    `customer_email`  VARCHAR(255) NULL DEFAULT NULL,
    `wilaya_id`       INT UNSIGNED NOT NULL,
    `address`         TEXT NOT NULL,
    -- Montants
    `subtotal`        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `shipping_cost`   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `discount`        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `total`           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `promo_code_id`   INT UNSIGNED NULL DEFAULT NULL,
    -- Statut & paiement
    `status`          ENUM('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    `payment_method`  ENUM('cod','baridimob','cib') NOT NULL DEFAULT 'cod',
    -- Notes & timestamps statuts
    `notes`          TEXT NULL DEFAULT NULL,
    `confirmed_at`   TIMESTAMP NULL DEFAULT NULL,
    `shipped_at`     TIMESTAMP NULL DEFAULT NULL,
    `delivered_at`   TIMESTAMP NULL DEFAULT NULL,
    `cancelled_at`   TIMESTAMP NULL DEFAULT NULL,
    `created_at`     TIMESTAMP NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `orders_order_number_unique` (`order_number`),
    KEY `orders_wilaya_id_index` (`wilaya_id`),
    KEY `orders_status_index` (`status`),
    KEY `orders_created_at_index` (`created_at`),
    CONSTRAINT `orders_wilaya_id_foreign`
        FOREIGN KEY (`wilaya_id`) REFERENCES `wilayas` (`id`),
    CONSTRAINT `orders_promo_code_id_foreign`
        FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : order_items
-- ============================================================
CREATE TABLE `order_items` (
    `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`           BIGINT UNSIGNED NOT NULL,
    `product_variant_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    -- Snapshots au moment de la commande
    `product_name`       VARCHAR(255) NOT NULL,
    `variant_label`      VARCHAR(255) NULL DEFAULT NULL,
    `sku`                VARCHAR(100) NULL DEFAULT NULL,
    `unit_price`         DECIMAL(10,2) NOT NULL,
    `quantity`           INT UNSIGNED NOT NULL DEFAULT 1,
    `subtotal`           DECIMAL(10,2) NOT NULL,
    `created_at`         TIMESTAMP NULL DEFAULT NULL,
    `updated_at`         TIMESTAMP NULL DEFAULT NULL,
    KEY `order_items_order_id_index` (`order_id`),
    KEY `order_items_variant_id_index` (`product_variant_id`),
    CONSTRAINT `order_items_order_id_foreign`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `order_items_variant_id_foreign`
        FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE : shipments
-- ============================================================
CREATE TABLE `shipments` (
    `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`        BIGINT UNSIGNED NOT NULL,
    `carrier`         VARCHAR(100) NULL DEFAULT NULL,
    `tracking_number` VARCHAR(100) NULL DEFAULT NULL,
    `status`          ENUM('pending','shipped','delivered') NOT NULL DEFAULT 'pending',
    `shipped_at`      TIMESTAMP NULL DEFAULT NULL,
    `delivered_at`    TIMESTAMP NULL DEFAULT NULL,
    `created_at`      TIMESTAMP NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `shipments_order_id_unique` (`order_id`),
    CONSTRAINT `shipments_order_id_foreign`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- [Tables carts et cart_items supprimées — l'espace client sera refait à zéro]

-- ============================================================
-- TABLE : settings (paramètres de la boutique)
-- ============================================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key`        VARCHAR(255) NOT NULL,
    `value`      LONGTEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`) VALUES
    ('terms',              NULL),
    ('shop_name',          'Ma Boutique'),
    ('shop_phone',         ''),
    ('shop_email',         ''),
    ('shop_address',       ''),
    ('primary_color',      '#18396e'),
    ('cod_enabled',        '1'),
    ('baridimob_enabled',  '0'),
    ('cib_enabled',        '0');

-- ============================================================
-- TABLE : order_status_history
-- ============================================================
CREATE TABLE `order_status_history` (
    `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id`    BIGINT UNSIGNED NOT NULL,
    `from_status` ENUM('pending','confirmed','shipped','delivered','cancelled') NULL DEFAULT NULL,
    `to_status`   ENUM('pending','confirmed','shipped','delivered','cancelled') NOT NULL,
    `note`        TEXT NULL DEFAULT NULL,
    `changed_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `order_status_history_order_id_index` (`order_id`),
    CONSTRAINT `order_status_history_order_id_foreign`
        FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- [Table favorites supprimée — l'espace client sera refait à zéro]

