CREATE TABLE intern_student_level(
  code varchar NOT NULL,
  description varchar,
  level varchar NOT NULL,
  PRIMARY KEY(code)
);

CREATE SEQUENCE intern_student_level_seq;

ALTER TABLE intern_internship ADD CONSTRAINT code FOREIGN KEY (level) REFERENCES intern_student_level(code);

INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('U', 'undergraduate', 'ugrad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('G', 'Graduate', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('G2', 'Graduate 2', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('P', 'Postdoctoral', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('D', 'Doctoral', 'grad');
