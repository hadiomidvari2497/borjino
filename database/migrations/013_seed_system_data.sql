INSERT IGNORE INTO personnel_roles(code, title) VALUES
('building_owner','مالک ساختمان'),
('manager','مدیر ساختمان'),
('security','حراست'),
('cleaning','نظافت'),
('maintenance','تعمیرات');

INSERT IGNORE INTO permission_groups(name, is_system, is_editable, is_deletable)
VALUES ('administrators', 1, 0, 0);

-- The admin password must be provisioned by the application using password_hash().
-- Do not place a plaintext password or a reusable placeholder hash in migrations.
