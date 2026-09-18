USE borjino;

CREATE TABLE IF NOT EXISTS personnel (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    person_type ENUM('individual','company') NOT NULL DEFAULT 'individual',
    role ENUM('building_owner','manager','security','cleaning','repair') NOT NULL,
    first_name VARCHAR(100) DEFAULT NULL,
    last_name VARCHAR(100) DEFAULT NULL,
    company_name VARCHAR(200) DEFAULT NULL,
    national_code VARCHAR(30) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    mobile VARCHAR(50) DEFAULT NULL,
    address VARCHAR(500) DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(role),
    INDEX(person_type)
) ENGINE=InnoDB;
