CREATE TABLE intern_local_student_data (
    student_id          character varying not null,
    user_name           character varying not null,
    email               character varying not null,

    first_name          character varying,
    middle_name         character varying,
    last_name           character varying,
    preferred_name      character varying,
    confidential        character varying,

    birth_date          character varying,
    gender              character varying,

    level               character varying,
    campus              character varying,
    gpa                 double precision,
    credit_hours        integer default 0,
    major_code          character varying,
    major_description   character varying,
    grad_date           character varying,

    phone               character varying,
    address             character varying,
    address2            character varying,
    city                character varying,
    state               character varying,
    zip                 character varying,

    primary key(student_id)
);

alter table intern_internship drop constraint intern_internship_term_fkey;

alter table intern_term drop constraint intern_term_term_key;

alter table intern_term alter column term TYPE character varying;
alter table intern_term alter column term drop default;
alter table intern_term alter column term set not null;

alter table intern_internship alter column term TYPE character varying;

alter table intern_internship add constraint intern_internship_term_fkey FOREIGN KEY (term) REFERENCES intern_term(term);

alter table intern_term add column description character varying;
alter table intern_term add column available_on_timestamp integer;
alter table intern_term add column census_date_timestamp integer;
alter table intern_term add column start_timestamp integer;
alter table intern_term add column end_timestamp integer;
alter table intern_term add column semester_type integer;

alter table intern_term drop column id;
alter table intern_term add primary key (term);


alter table intern_major add column level character varying;
update intern_major set level = 'U';

alter table intern_major add column code character varying unique;
alter table intern_major rename column name to description;


alter table intern_major drop constraint intern_major_name_key;
alter table intern_major add constraint intern_major_description_level_key UNIQUE (description, level);
