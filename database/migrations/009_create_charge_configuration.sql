CREATE TABLE charge_settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    calculation_method TINYINT UNSIGNED NOT NULL,
    unit_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    area_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    person_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    person_area_ratio DECIMAL(5,4) NOT NULL DEFAULT 0.2000,
    issue_day TINYINT UNSIGNED NOT NULL,
    warning_days TINYINT UNSIGNED NOT NULL DEFAULT 3,
    emergency_phone VARCHAR(30) NULL,
    sms_provider_settings JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_charge_settings_building (building_id),
    CONSTRAINT fk_charge_settings_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE charge_cost_types (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    cost_kind ENUM('fixed','variable') NOT NULL,
    allocation_method ENUM('equal','area','person','area_person_mix') NOT NULL,
    default_amount DECIMAL(18,2) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uq_charge_cost_types (building_id, name),
    CONSTRAINT fk_charge_cost_types_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
