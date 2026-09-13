CREATE TABLE buildings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    postal_code VARCHAR(20) NULL,
    building_type VARCHAR(50) NULL,
    construction_date DATE NULL,
    parking_count INT UNSIGNED NOT NULL DEFAULT 0,
    storage_count INT UNSIGNED NOT NULL DEFAULT 0,
    province VARCHAR(100) NULL,
    city VARCHAR(100) NULL,
    address VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_buildings_city (city),
    INDEX idx_buildings_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
