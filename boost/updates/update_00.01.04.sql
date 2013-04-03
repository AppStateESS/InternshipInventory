ALTER TABLE intern_department ADD COLUMN corequisite smallint NOT NULL DEFAULT 0;
ALTER TABLE intern_internship ADD COLUMN corequisite_number character varying;
ALTER TABLE intern_internship ADD COLUMN corequisite_section character varying;
