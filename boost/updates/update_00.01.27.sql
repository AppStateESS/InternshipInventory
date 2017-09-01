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
