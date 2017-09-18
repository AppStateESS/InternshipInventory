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

alter table intern_term drop column id;
alter table intern_term add primary key (term);
