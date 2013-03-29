create table intern_faculty (
	id 			integer NOT NULL,
	banner_id 		varchar NOT NULL,
	username		varchar NOT NULL,
	first_name 		varchar NOT NULL,
	last_name 		varchar NOT NULL,
	phone 			varchar,
	fax 			varchar,
	street_address1 varchar,
	street_address2 varchar,
	city 			varchar,
	state 			char(2),
	zip 			varchar,
	PRIMARY KEY(id)
);

ALTER TABLE intern_department ADD COLUMN corequisite smallint NOT NULL DEFAULT 0;
ALTER TABLE intern_internship ADD COLUMN corequisite_number character varying;
ALTER TABLE intern_internship ADD COLUMN corequisite_section character varying;
