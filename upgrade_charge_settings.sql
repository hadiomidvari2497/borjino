USE borjino;

CREATE TABLE IF NOT EXISTS charge_settings (
    id TINYINT UNSIGNED PRIMARY KEY,
    calculation_method TINYINT UNSIGNED NOT NULL DEFAULT 1,
    fixed_unit_amount DECIMAL(18,2) NOT NULL DEFAULT 0,
    area_rate DECIMAL(18,4) NOT NULL DEFAULT 0,
    person_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    area_percent DECIMAL(5,2) NOT NULL DEFAULT 80,
    person_percent DECIMAL(5,2) NOT NULL DEFAULT 20,
    parking_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    storage_rate DECIMAL(18,2) NOT NULL DEFAULT 0,
    issue_day TINYINT UNSIGNED NOT NULL DEFAULT 1,
    warning_days TINYINT UNSIGNED NOT NULL DEFAULT 3,
    emergency_sms VARCHAR(50) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO charge_settings(id)
SELECT 1
WHERE NOT EXISTS (SELECT 1 FROM charge_settings WHERE id=1);

ALTER TABLE charges ADD COLUMN calculation_method TINYINT UNSIGNED NOT NULL DEFAULT 1;
ALTER TABLE charges ADD COLUMN calculation_details JSON DEFAULT NULL;
ALTER TABLE charges ADD UNIQUE KEY uq_charge_unit_period(unit_id, period);
ALTER TABLE charges ADD INDEX idx_charges_period(period);
