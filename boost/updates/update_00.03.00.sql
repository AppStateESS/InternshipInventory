CREATE TABLE intern_student_level(
  code varchar NOT NULL,
  description varchar,
  level varchar NOT NULL,
  PRIMARY KEY(code)
);

CREATE SEQUENCE intern_student_level_seq;

INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('U', 'Undergraduate', 'ugrad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('G', 'Graduate', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('G2', 'Graduate 2', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('P', 'Postdoctoral', 'grad');
INSERT INTO "intern_student_level" ("code", "description", "level") VALUES ('D', 'Doctoral', 'grad');

UPDATE "intern_internship" SET level = 'U' WHERE level = 'ugrad';
UPDATE "intern_internship" SET level = 'G' WHERE level = 'grad';
UPDATE "intern_internship" SET level = 'G2' WHERE level = 'grad2';
UPDATE "intern_internship" SET level = 'D' WHERE level = 'doctoral';
UPDATE "intern_internship" SET level = 'P' WHERE level = 'postdoc';

ALTER TABLE intern_internship ADD CONSTRAINT code FOREIGN KEY (level) REFERENCES intern_student_level(code);
