USE borjino;

CREATE TABLE IF NOT EXISTS access_groups (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL,
  is_system TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_id INT UNSIGNED NOT NULL,
  resource VARCHAR(100) NOT NULL,
  can_view TINYINT(1) NOT NULL DEFAULT 0,
  can_create TINYINT(1) NOT NULL DEFAULT 0,
  can_edit TINYINT(1) NOT NULL DEFAULT 0,
  can_delete TINYINT(1) NOT NULL DEFAULT 0,
  UNIQUE KEY uq_group_resource(group_id,resource),
  FOREIGN KEY(group_id) REFERENCES access_groups(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activity_log (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT NULL,
  action VARCHAR(50) NOT NULL,
  resource VARCHAR(100) DEFAULT NULL,
  resource_id INT UNSIGNED DEFAULT NULL,
  details TEXT,
  ip_address VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(user_id), INDEX(resource),
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(50) DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS access_group_id INT UNSIGNED DEFAULT NULL;
ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login_at DATETIME DEFAULT NULL;

INSERT INTO access_groups(name,description,is_system)
SELECT 'administrators','دسترسی کامل سامانه',1
WHERE NOT EXISTS (SELECT 1 FROM access_groups WHERE name='administrators');

UPDATE users SET access_group_id=(SELECT id FROM access_groups WHERE name='administrators' LIMIT 1)
WHERE username='admin' AND (access_group_id IS NULL OR access_group_id=0);

INSERT INTO permissions(group_id,resource,can_view,can_create,can_edit,can_delete)
SELECT g.id,r.resource,1,1,1,1
FROM access_groups g
CROSS JOIN (
 SELECT 'dashboard' resource UNION ALL SELECT 'buildings' UNION ALL SELECT 'blocks'
 UNION ALL SELECT 'units' UNION ALL SELECT 'persons' UNION ALL SELECT 'memberships'
 UNION ALL SELECT 'personnel' UNION ALL SELECT 'contracts' UNION ALL SELECT 'costs'
 UNION ALL SELECT 'charges' UNION ALL SELECT 'charge_settings' UNION ALL SELECT 'payments' UNION ALL SELECT 'reports'
 UNION ALL SELECT 'users' UNION ALL SELECT 'access_groups' UNION ALL SELECT 'settings'
) r
WHERE g.name='administrators'
ON DUPLICATE KEY UPDATE can_view=1,can_create=1,can_edit=1,can_delete=1;
