-- Borjino / PostgreSQL baseline schema
-- Version: 0.1
-- Scope: requirements baseline from Borjino technical specification.

CREATE EXTENSION IF NOT EXISTS pgcrypto;

CREATE TABLE buildings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(150) NOT NULL,
    postal_code VARCHAR(20),
    building_type VARCHAR(30) NOT NULL CHECK (building_type IN ('residential','commercial','office','educational','other')),
    construction_date DATE,
    total_parking_count INTEGER NOT NULL DEFAULT 0 CHECK (total_parking_count >= 0),
    total_storage_count INTEGER NOT NULL DEFAULT 0 CHECK (total_storage_count >= 0),
    province VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE blocks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    block_number INTEGER NOT NULL,
    name VARCHAR(150),
    floor_count INTEGER NOT NULL CHECK (floor_count > 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (building_id, block_number)
);

CREATE TABLE units (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    block_id UUID NOT NULL REFERENCES blocks(id) ON DELETE RESTRICT,
    unit_number VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20),
    floor_number INTEGER NOT NULL,
    area_sqm NUMERIC(12,2) NOT NULL CHECK (area_sqm > 0),
    status VARCHAR(30) NOT NULL DEFAULT 'vacant' CHECK (status IN ('sold','rented','vacant','under_repair')),
    financial_status VARCHAR(20) NOT NULL DEFAULT 'settled' CHECK (financial_status IN ('debtor','creditor','settled')),
    direction VARCHAR(20) CHECK (direction IN ('north','south','east','west')),
    notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (block_id, unit_number),
    CHECK (floor_number >= 0)
);

CREATE TABLE unit_parkings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    unit_id UUID NOT NULL REFERENCES units(id) ON DELETE RESTRICT,
    parking_number VARCHAR(50) NOT NULL,
    UNIQUE (unit_id, parking_number)
);

CREATE TABLE unit_storages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    unit_id UUID NOT NULL REFERENCES units(id) ON DELETE RESTRICT,
    storage_number VARCHAR(50) NOT NULL,
    UNIQUE (unit_id, storage_number)
);

CREATE TABLE persons (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    legal_name VARCHAR(200),
    person_type VARCHAR(20) NOT NULL DEFAULT 'individual' CHECK (person_type IN ('individual','legal')),
    national_id VARCHAR(30),
    phone VARCHAR(30),
    birth_or_establishment_date DATE,
    address TEXT,
    secondary_address TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    CHECK ((person_type = 'individual' AND first_name IS NOT NULL AND last_name IS NOT NULL)
        OR (person_type = 'legal' AND legal_name IS NOT NULL))
);
CREATE UNIQUE INDEX ux_persons_national_id ON persons(national_id) WHERE national_id IS NOT NULL;

CREATE TABLE personnel_roles (
    id SMALLSERIAL PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO personnel_roles(code, title) VALUES
('building_owner','مالک ساختمان'),('manager','مدیر ساختمان'),('security','حراست'),('cleaning','نظافت'),('maintenance','تعمیرات')
ON CONFLICT (code) DO NOTHING;

CREATE TABLE building_personnel (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    person_id UUID NOT NULL REFERENCES persons(id) ON DELETE RESTRICT,
    role_id SMALLINT NOT NULL REFERENCES personnel_roles(id) ON DELETE RESTRICT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (building_id, person_id, role_id)
);

CREATE TABLE unit_memberships (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    unit_id UUID NOT NULL REFERENCES units(id) ON DELETE RESTRICT,
    person_id UUID NOT NULL REFERENCES persons(id) ON DELETE RESTRICT,
    membership_type VARCHAR(20) NOT NULL CHECK (membership_type IN ('owner','tenant')),
    start_date DATE,
    end_date DATE,
    is_current BOOLEAN NOT NULL DEFAULT TRUE,
    notes TEXT,
    CHECK (end_date IS NULL OR start_date IS NULL OR end_date >= start_date)
);
CREATE INDEX ix_unit_memberships_unit ON unit_memberships(unit_id);
CREATE INDEX ix_unit_memberships_person ON unit_memberships(person_id);

CREATE TABLE contracts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    unit_id UUID NOT NULL REFERENCES units(id) ON DELETE RESTRICT,
    contract_type VARCHAR(20) NOT NULL CHECK (contract_type IN ('rental','sale')),
    party_person_id UUID NOT NULL REFERENCES persons(id) ON DELETE RESTRICT,
    deposit_amount NUMERIC(18,2) CHECK (deposit_amount >= 0),
    monthly_rent NUMERIC(18,2) CHECK (monthly_rent >= 0),
    sale_amount NUMERIC(18,2) CHECK (sale_amount >= 0),
    contract_date DATE NOT NULL,
    end_date DATE,
    CHECK (end_date IS NULL OR end_date >= contract_date),
    CHECK (contract_type = 'rental' OR (sale_amount IS NOT NULL AND deposit_amount IS NULL AND monthly_rent IS NULL)),
    CHECK (contract_type = 'sale' OR (sale_amount IS NULL))
);
CREATE INDEX ix_contracts_unit ON contracts(unit_id);

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    person_id UUID REFERENCES persons(id) ON DELETE SET NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    phone VARCHAR(30),
    last_login_at TIMESTAMPTZ,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    is_system_admin BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE permission_groups (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(100) NOT NULL UNIQUE,
    is_system BOOLEAN NOT NULL DEFAULT FALSE,
    is_editable BOOLEAN NOT NULL DEFAULT TRUE,
    is_deletable BOOLEAN NOT NULL DEFAULT TRUE
);

INSERT INTO permission_groups(name, is_system, is_editable, is_deletable)
VALUES ('administrators', TRUE, FALSE, FALSE)
ON CONFLICT (name) DO NOTHING;

CREATE TABLE permissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    resource VARCHAR(100) NOT NULL,
    action VARCHAR(50) NOT NULL,
    UNIQUE (resource, action)
);

CREATE TABLE group_permissions (
    group_id UUID NOT NULL REFERENCES permission_groups(id) ON DELETE CASCADE,
    permission_id UUID NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
    PRIMARY KEY (group_id, permission_id)
);

CREATE TABLE user_groups (
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    group_id UUID NOT NULL REFERENCES permission_groups(id) ON DELETE RESTRICT,
    PRIMARY KEY (user_id, group_id)
);

CREATE TABLE charge_settings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL UNIQUE REFERENCES buildings(id) ON DELETE RESTRICT,
    calculation_method SMALLINT NOT NULL CHECK (calculation_method BETWEEN 1 AND 10),
    unit_rate NUMERIC(18,2) NOT NULL DEFAULT 0 CHECK (unit_rate >= 0),
    area_rate NUMERIC(18,2) NOT NULL DEFAULT 0 CHECK (area_rate >= 0),
    person_rate NUMERIC(18,2) NOT NULL DEFAULT 0 CHECK (person_rate >= 0),
    person_area_ratio NUMERIC(5,4) NOT NULL DEFAULT 0.20 CHECK (person_area_ratio BETWEEN 0 AND 1),
    issue_day SMALLINT NOT NULL CHECK (issue_day BETWEEN 1 AND 31),
    warning_days SMALLINT NOT NULL DEFAULT 3 CHECK (warning_days >= 0),
    emergency_phone VARCHAR(30),
    sms_provider_settings JSONB,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE charge_cost_types (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    name VARCHAR(150) NOT NULL,
    cost_kind VARCHAR(20) NOT NULL CHECK (cost_kind IN ('fixed','variable')),
    allocation_method VARCHAR(30) NOT NULL CHECK (allocation_method IN ('equal','area','person','area_person_mix')),
    default_amount NUMERIC(18,2) CHECK (default_amount >= 0),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE (building_id, name)
);

CREATE TABLE common_bills (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    cost_type_id UUID NOT NULL REFERENCES charge_cost_types(id) ON DELETE RESTRICT,
    bill_date DATE NOT NULL,
    amount NUMERIC(18,2) NOT NULL CHECK (amount >= 0),
    description TEXT
);

CREATE TABLE repairs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    unit_id UUID REFERENCES units(id) ON DELETE RESTRICT,
    repair_date DATE NOT NULL,
    amount NUMERIC(18,2) NOT NULL CHECK (amount >= 0),
    description TEXT
);

CREATE TABLE charge_periods (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    building_id UUID NOT NULL REFERENCES buildings(id) ON DELETE RESTRICT,
    year SMALLINT NOT NULL,
    month SMALLINT NOT NULL CHECK (month BETWEEN 1 AND 12),
    calculated_at TIMESTAMPTZ,
    issued_at TIMESTAMPTZ,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' CHECK (status IN ('draft','calculated','issued','closed')),
    UNIQUE (building_id, year, month)
);

CREATE TABLE charges (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    charge_period_id UUID NOT NULL REFERENCES charge_periods(id) ON DELETE RESTRICT,
    unit_id UUID NOT NULL REFERENCES units(id) ON DELETE RESTRICT,
    membership_id UUID REFERENCES unit_memberships(id) ON DELETE RESTRICT,
    amount NUMERIC(18,2) NOT NULL CHECK (amount >= 0),
    paid_amount NUMERIC(18,2) NOT NULL DEFAULT 0 CHECK (paid_amount >= 0 AND paid_amount <= amount),
    calculation_snapshot JSONB NOT NULL,
    issued_at TIMESTAMPTZ,
    due_at TIMESTAMPTZ,
    UNIQUE (charge_period_id, unit_id)
);

CREATE TABLE charge_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    charge_id UUID NOT NULL REFERENCES charges(id) ON DELETE CASCADE,
    cost_type_id UUID REFERENCES charge_cost_types(id) ON DELETE RESTRICT,
    description VARCHAR(250) NOT NULL,
    basis VARCHAR(50),
    amount NUMERIC(18,2) NOT NULL CHECK (amount >= 0)
);

CREATE TABLE payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    charge_id UUID NOT NULL REFERENCES charges(id) ON DELETE RESTRICT,
    paid_at TIMESTAMPTZ NOT NULL,
    amount NUMERIC(18,2) NOT NULL CHECK (amount > 0),
    reference VARCHAR(100),
    notes TEXT
);

CREATE TABLE system_settings (
    key VARCHAR(100) PRIMARY KEY,
    value JSONB NOT NULL,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE notifications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE SET NULL,
    person_id UUID REFERENCES persons(id) ON DELETE SET NULL,
    type VARCHAR(30) NOT NULL,
    channel VARCHAR(20) NOT NULL CHECK (channel IN ('sms','system')),
    payload JSONB NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','sent','failed')),
    sent_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE audit_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id UUID REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(50) NOT NULL,
    resource VARCHAR(100) NOT NULL,
    resource_id UUID,
    metadata JSONB,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Safety rule for the required non-removable system admin user.
INSERT INTO users(username, password_hash, is_system_admin)
VALUES ('admin', 'REPLACE_WITH_ARGON2ID_HASH_DURING_SEEDING', TRUE)
ON CONFLICT (username) DO NOTHING;

-- Note: the application layer must prevent changing/deleting the admin user and
-- administrators group, as required by the specification. Password must never be stored plaintext.
