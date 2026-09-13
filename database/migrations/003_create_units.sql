CREATE TABLE units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    building_id BIGINT UNSIGNED NOT NULL,
    block_id BIGINT UNSIGNED NULL,
    unit_number VARCHAR(30) NOT NULL,
    postal_code VARCHAR(20) NULL,
    floor_number SMALLINT NULL,
    area DECIMAL(12,2) NULL,
    status ENUM('sold','rented','vacant','under_repair') NOT NULL DEFAULT 'vacant',
    financial_status ENUM('debtor','creditor','settled') NOT NULL DEFAULT 'settled',
    direction VARCHAR(50) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_units_building
        FOREIGN KEY (building_id) REFERENCES buildings(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_units_block
        FOREIGN KEY (block_id) REFERENCES blocks(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY uq_units_building_number (building_id, unit_number),
    INDEX idx_units_building (building_id),
    INDEX idx_units_block (block_id),
    INDEX idx_units_status (status),
    INDEX idx_units_financial_status (financial_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
