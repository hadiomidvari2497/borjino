USE borjino;

ALTER TABLE buildings
  DROP COLUMN total_floors,
  DROP COLUMN units_per_floor,
  ADD COLUMN postal_code VARCHAR(20) DEFAULT NULL AFTER name,
  ADD COLUMN construction_date DATE DEFAULT NULL AFTER building_type,
  ADD COLUMN province VARCHAR(100) DEFAULT NULL AFTER storage_count,
  ADD COLUMN city VARCHAR(100) DEFAULT NULL AFTER province;

ALTER TABLE blocks ADD COLUMN block_no VARCHAR(50) NOT NULL DEFAULT '' AFTER building_id;

ALTER TABLE units
  ADD COLUMN unit_postal_code VARCHAR(20) DEFAULT NULL AFTER unit_no,
  ADD COLUMN parking_count INT NOT NULL DEFAULT 0 AFTER area,
  ADD COLUMN parking_numbers VARCHAR(500) DEFAULT NULL AFTER parking_count,
  ADD COLUMN storage_count INT NOT NULL DEFAULT 0 AFTER parking_no,
  ADD COLUMN storage_numbers VARCHAR(500) DEFAULT NULL AFTER storage_count,
  ADD COLUMN financial_status ENUM('debtor','creditor','settled') NOT NULL DEFAULT 'settled' AFTER status,
  ADD COLUMN direction ENUM('north','south','east','west') DEFAULT NULL AFTER financial_status,
  ADD COLUMN notes TEXT AFTER direction;

UPDATE units SET parking_count = CASE WHEN parking_no IS NULL OR parking_no = '' THEN 0 ELSE 1 END,
                 parking_numbers = NULLIF(parking_no,''),
                 storage_count = CASE WHEN storage_no IS NULL OR storage_no = '' THEN 0 ELSE 1 END,
                 storage_numbers = NULLIF(storage_no,'');

ALTER TABLE units DROP COLUMN bedrooms, DROP COLUMN parking_no, DROP COLUMN storage_no;
