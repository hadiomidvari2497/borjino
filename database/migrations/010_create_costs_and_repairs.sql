CREATE TABLE common_bills (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    cost_type_id BIGINT UNSIGNED NOT NULL,
    bill_date DATE NOT NULL,
    amount DECIMAL(18,2) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_common_bills_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_common_bills_cost_type FOREIGN KEY (cost_type_id) REFERENCES charge_cost_types(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE repairs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    unit_id BIGINT UNSIGNED NULL,
    repair_date DATE NOT NULL,
    amount DECIMAL(18,2) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_repairs_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_repairs_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
