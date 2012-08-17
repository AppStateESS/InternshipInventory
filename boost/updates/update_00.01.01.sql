ALTER TABLE intern_internship ADD COLUMN multi_part SMALLINT;
ALTER TABLE intern_internship ADD COLUMN secondary_part SMALLINT;

UPDATE intern_internship SET multi_part = 0, secondary_part = 0;

ALTER TABLE intern_internship ALTER COLUMN multi_part SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN secondary_part SET NOT NULL;