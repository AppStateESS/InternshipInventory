create table intern_faculty (
    id              integer NOT NULL,
    username        character varying NOT NULL,
    first_name      character varying NOT NULL,
    last_name       character varying NOT NULL,
    phone           character varying,
    fax             character varying,
    street_address1 character varying,
    street_address2 character varying,
    city            character varying,
    state           character varying,
    zip             character varying,
    PRIMARY KEY(id)
);

create table intern_faculty_department (
    faculty_id      integer NOT NULL REFERENCES intern_faculty(id),
    department_id   integer NOT NULL REFERENCES intern_department(id)
);

alter table intern_internship add column faculty_id integer REFERENCES intern_faculty(id);

-- TODO: Drop faculty_supervisor_id column from intern_internship table
-- TODO: Drop intern_faculty_supervisor table (after converting data over to above table)