# Database migrations

The project uses MySQL 8+ and PDO from raw PHP.

Migration files will be versioned and executed in order. The existing `docs/database/schema.mysql.sql` remains the design/source baseline until the schema is split into executable migrations.

Rules:
- Every schema change gets a new migration.
- Never edit an already-applied migration in place.
- Primary and foreign keys must be explicit.
- Use InnoDB and utf8mb4.
- Monetary values use DECIMAL, never floating point.
