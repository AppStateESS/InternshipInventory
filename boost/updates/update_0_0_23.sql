-- Add new internship types.
ALTER TABLE intern_internship ADD COLUMN student_teaching SMALLINT NOT NULL;
ALTER TABLE intern_internship ADD COLUMN clinical_practica SMALLINT NOT NULL;
ALTER TABLE intern_internship ADD COLUMN special_topics SMALLINT NOT NULL;