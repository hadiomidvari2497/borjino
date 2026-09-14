CREATE TABLE charge_periods (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    year SMALLINT UNSIGNED NOT NULL,
    month TINYINT UNSIGNED NOT NULL,
    calculated_at DATETIME NULL,
    issued_at DATETIME NULL,
    status ENUM('draft','calculated','issued','closed') NOT NULL DEFAULT 'draft',
    PRIMARY KEY (id),
    UNIQUE KEY uq_charge_periods (building_id, year, month),
    CONSTRAINT fk_charge_periods_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE charges (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    charge_period_id BIGINT UNSIGNED NOT NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    membership_id BIGINT UNSIGNED NULL,
    amount DECIMAL(18,2) NOT NULL,
    paid_amount DECIMAL(18,2) NOT NULL DEFAULT 0,
    calculation_snapshot JSON NOT NULL,
    issued_at DATETIME NULL,
    due_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_charges_period_unit (charge_period_id, unit_id),
    CONSTRAINT fk_charges_period FOREIGN KEY (charge_period_id) REFERENCES charge_periods(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_charges_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_charges_membership FOREIGN KEY (membership_id) REFERENCES unit_memberships(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE charge_items (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    charge_id BIGINT UNSIGNED NOT NULL,
    cost_type_id BIGINT UNSIGNED NULL,
    description VARCHAR(250) NOT NULL,
    basis VARCHAR(50) NULL,
    amount DECIMAL(18,2) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_charge_items_charge FOREIGN KEY (charge_id) REFERENCES charges(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_charge_items_cost_type FOREIGN KEY (cost_type_id) REFERENCES charge_cost_types(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE payments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    charge_id BIGINT UNSIGNED NOT NULL,
    paid_at DATETIME NOT NULL,
    amount DECIMAL(18,2) NOT NULL,
    reference VARCHAR(100) NULL,
    notes TEXT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_payments_charge FOREIGN KEY (charge_id) REFERENCES charges(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
