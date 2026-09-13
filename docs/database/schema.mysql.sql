-- Borjino / MySQL 8.0+ baseline schema
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- This is the MySQL source of truth for the raw PHP project.

CREATE DATABASE IF NOT EXISTS borjino CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE borjino;

CREATE TABLE buildings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    postal_code VARCHAR(20) NULL,
    building_type ENUM('residential','commercial','office','educational','other') NOT NULL,
    construction_date DATE NULL,
    total_parking_count INT UNSIGNED NOT NULL DEFAULT 0,
    total_storage_count INT UNSIGNED NOT NULL DEFAULT 0,
    province VARCHAR(100) NULL,
    city VARCHAR(100) NULL,
    address TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE blocks (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    block_number INT UNSIGNED NOT NULL,
    name VARCHAR(150) NULL,
    floor_count INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_blocks_building_number (building_id, block_number),
    CONSTRAINT fk_blocks_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE units (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    block_id BIGINT UNSIGNED NOT NULL,
    unit_number VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NULL,
    floor_number INT NOT NULL,
    area_sqm DECIMAL(12,2) NOT NULL,
    status ENUM('sold','rented','vacant','under_repair') NOT NULL DEFAULT 'vacant',
    financial_status ENUM('debtor','creditor','settled') NOT NULL DEFAULT 'settled',
    direction ENUM('north','south','east','west') NULL,
    notes TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_units_block_number (block_id, unit_number),
    CONSTRAINT fk_units_block FOREIGN KEY (block_id) REFERENCES blocks(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE unit_parkings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    parking_number VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_unit_parkings (unit_id, parking_number),
    CONSTRAINT fk_unit_parkings_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE unit_storages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    unit_id BIGINT UNSIGNED NOT NULL,
    storage_number VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_unit_storages (unit_id, storage_number),
    CONSTRAINT fk_unit_storages_unit FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE persons (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    legal_name VARCHAR(200) NULL,
    person_type ENUM('individual','legal') NOT NULL DEFAULT 'individual',
    national_id VARCHAR(30) NULL,
    phone VARCHAR(30) NULL,
    birth_or_establishment_date DATE NULL,
    address TEXT NULL,
    secondary_address TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_persons_national_id (national_id)
) ENGINE=InnoDB;

CREATE TABLE personnel_roles (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(30) NOT NULL,
    title VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_personnel_roles_code (code),
    UNIQUE KEY uq_personnel_roles_title (title)
) ENGINE=InnoDB;

INSERT IGNORE INTO personnel_roles(code, title) VALUES
('building_owner','مالک ساختمان'),('manager','مدیر ساختمان'),('security','حراست'),('cleaning','نظافت'),('maintenance','تعمیرات');

CREATE TABLE building_personnel (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    building_id BIGINT UNSIGNED NOT NULL,
    person_id BIGINT UNSIGNED NOT NULL,
    role_id SMALLINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_building_personnel (building_id, person_id, role_id),
    CONSTRAINT fk_building_personnel_building FOREIGN KEY (building_id) REFERENCES buildings(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_building_personnel_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_building_personnel_role FOREIGN KEY (role_id) REFERENCES personnel_roles(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    person_id BIGINT UNSIGNED NULL,
    username VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NULL,
    last_login_at DATETIME NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_system_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    KEY ix_users_person (person_id),
    CONSTRAINT fk_users_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE permission_groups (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    is_system TINYINT(1) NOT NULL DEFAULT 0,
    is_editable TINYINT(1) NOT NULL DEFAULT 1,
    is_deletable TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uq_permission_groups_name (name)
) ENGINE=InnoDB;

INSERT IGNORE INTO permission_groups(name, is_system, is_editable, is_deletable)
VALUES ('administrators', 1, 0, 0);

CREATE TABLE permissions (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    resource VARCHAR(100) NOT NULL,
    action VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_permissions_resource_action (resource, action)
) ENGINE=InnoDB;

CREATE TABLE group_permissions (
    group_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (group_id, permission_id),
    CONSTRAINT fk_group_permissions_group FOREIGN KEY (group_id) REFERENCES permission_groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_group_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user_groups (
    user_id BIGINT UNSIGNED NOT NULL,
    group_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, group_id),
    CONSTRAINT fk_user_groups_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_user_groups_group FOREIGN KEY (group_id) REFERENCES permission_groups(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

CREATE TABLE payments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    charge_id BIGINT UNSIGNED NOT NULL,
    paid_at DATETIME NOT NULL,
    amount DECIMAL(18,2) NOT NULL,
    reference VARCHAR(100) NULL,
    notes TEXT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_payments_charge FOREIGN KEY (charge_id) REFERENCES charges(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE system_settings (
    `key` VARCHAR(100) NOT NULL,
    `value` JSON NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    person_id BIGINT UNSIGNED NULL,
    type VARCHAR(30) NOT NULL,
    channel ENUM('sms','system') NOT NULL,
    payload JSON NOT NULL,
    status ENUM('pending','sent','failed') NOT NULL DEFAULT 'pending',
    sent_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_notifications_person FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(50) NOT NULL,
    resource VARCHAR(100) NOT NULL,
    resource_id BIGINT UNSIGNED NULL,
    metadata JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY ix_audit_logs_user (user_id),
    KEY ix_audit_logs_resource (resource, resource_id),
    CONSTRAINT fk_audit_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Application must seed the admin password using password_hash() and never store plaintext.
INSERT IGNORE INTO users(username, password_hash, is_system_admin)
VALUES ('admin', 'REPLACE_WITH_PHP_PASSWORD_HASH', 1);
