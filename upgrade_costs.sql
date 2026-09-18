USE borjino;

CREATE TABLE IF NOT EXISTS costs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  building_id INT UNSIGNED NOT NULL,
  cost_type ENUM('fixed','variable') NOT NULL,
  category ENUM('general','utility','repair','other') NOT NULL DEFAULT 'general',
  title VARCHAR(200) NOT NULL,
  amount DECIMAL(18,2) NOT NULL DEFAULT 0,
  period VARCHAR(20) DEFAULT NULL,
  allocation_method ENUM('equal','area','person','combination') DEFAULT NULL,
  allocation_area_percent DECIMAL(5,2) DEFAULT NULL,
  allocation_person_percent DECIMAL(5,2) DEFAULT NULL,
  is_common TINYINT(1) NOT NULL DEFAULT 0,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(building_id) REFERENCES buildings(id) ON DELETE CASCADE,
  INDEX(building_id),
  INDEX(cost_type),
  INDEX(period)
) ENGINE=InnoDB;