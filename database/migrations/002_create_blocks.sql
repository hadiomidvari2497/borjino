CREATE TABLE blocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    building_id BIGINT UNSIGNED NOT NULL,
    block_number VARCHAR(30) NOT NULL,
    name VARCHAR(150) NULL,
    floor_count SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_blocks_building
        FOREIGN KEY (building_id) REFERENCES buildings(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE KEY uq_blocks_building_number (building_id, block_number),
    INDEX idx_blocks_building (building_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
