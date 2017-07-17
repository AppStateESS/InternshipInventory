CREATE TABLE intern_courses(
      id INT NOT NULL,
      subject_id integer REFERENCES intern_subject(id),
      course_num INT NOT NULL,
      primary key (id)
);

CREATE SEQUENCE intern_courses_seq;
