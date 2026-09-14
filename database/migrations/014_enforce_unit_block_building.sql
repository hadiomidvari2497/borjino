ALTER TABLE blocks
    ADD UNIQUE KEY uq_blocks_id_building (id, building_id);

ALTER TABLE units
    DROP FOREIGN KEY fk_units_block,
    ADD CONSTRAINT fk_units_block_building
        FOREIGN KEY (block_id, building_id) REFERENCES blocks(id, building_id)
        ON UPDATE CASCADE ON DELETE RESTRICT;
