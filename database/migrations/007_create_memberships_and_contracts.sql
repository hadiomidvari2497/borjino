CREATE TABLE unit_memberships (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    person_id BIGINT UNSIGNED NOT NULL,
    membership_type ENUM('owner','tenant') NOT NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 1,
    notes TEXT NULL,
    PRIMARY KEY (id),
    KEY ix_unit_memberships_unit (unit_id),
    KEY ix_unit_memberships_person (person_id),
    CONSTRAINT fk_unit_memberships_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_unit_memberships_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contracts (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    contract_type ENUM('rental','sale') NOT NULL,
    party_person_id BIGINT UNSIGNED NOT NULL,
    deposit_amount DECIMAL(18,2) NULL,
    monthly_rent DECIMAL(18,2) NULL,
    sale_amount DECIMAL(18,2) NULL,
    contract_date DATE NOT NULL,
    end_date DATE NULL,
    PRIMARY KEY (id),
    KEY ix_contracts_unit (unit_id),
    CONSTRAINT fk_contracts_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contracts_party FOREIGN KEY (party_person_id) REFERENCES persons(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
