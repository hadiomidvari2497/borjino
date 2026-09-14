CREATE TABLE unit_parkings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    parking_number VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_unit_parkings (unit_id, parking_number),
    CONSTRAINT fk_unit_parkings_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE unit_storages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    storage_number VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_unit_storages (unit_id, storage_number),
    CONSTRAINT fk_unit_storages_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
