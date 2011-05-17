-- Defaults to not hidden.
CREATE TABLE intern_grad_prog (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT DEFAULT NULL,
       PRIMARY KEY(id)
);

ALTER TABLE intern_student DROP COLUMN grad_prog;
ALTER TABLE intern_student ADD COLUMN grad_prog INT DEFAULT NULL REFERENCES intern_grad_prog(id);

-- Add Graduate Programs
INSERT INTO intern_grad_prog VALUES (1, 'Accounting');
INSERT INTO intern_grad_prog VALUES (2, 'Appalachian Studies');
INSERT INTO intern_grad_prog VALUES (3, 'Biology');
INSERT INTO intern_grad_prog VALUES (4, 'Business Administration');
INSERT INTO intern_grad_prog VALUES (5, 'Child Development – Birth through K');
INSERT INTO intern_grad_prog VALUES (6, 'Clinical Mental Health Counseling');
INSERT INTO intern_grad_prog VALUES (7, 'Addictions Counseling');
INSERT INTO intern_grad_prog VALUES (8, 'Expressive Arts Therapy');
INSERT INTO intern_grad_prog VALUES (9, 'College Student Development');
INSERT INTO intern_grad_prog VALUES (10, 'Computer Science');
INSERT INTO intern_grad_prog VALUES (11, 'Criminal Justice');
INSERT INTO intern_grad_prog VALUES (12, 'Curriculum Specialist');
INSERT INTO intern_grad_prog VALUES (13, 'Educational Leadership');
INSERT INTO intern_grad_prog VALUES (14, 'Educational Media');
INSERT INTO intern_grad_prog VALUES (15, 'Education Media – Web-based Distance Learning');
INSERT INTO intern_grad_prog VALUES (16, 'Media Literacy');
INSERT INTO intern_grad_prog VALUES (17, 'Elementary Education');
INSERT INTO intern_grad_prog VALUES (18, 'English');
INSERT INTO intern_grad_prog VALUES (19, 'English Education');
INSERT INTO intern_grad_prog VALUES (20, 'Rhetoric and Composition');
INSERT INTO intern_grad_prog VALUES (21, 'Exercise Science');
INSERT INTO intern_grad_prog VALUES (22, 'Family & Consumer Sciences – Education');
INSERT INTO intern_grad_prog VALUES (23, 'Geography');
INSERT INTO intern_grad_prog VALUES (24, 'GIS Science');
INSERT INTO intern_grad_prog VALUES (25, 'Planning');
INSERT INTO intern_grad_prog VALUES (26, 'Gerontology');
INSERT INTO intern_grad_prog VALUES (27, 'Higher Education');
INSERT INTO intern_grad_prog VALUES (28, 'History');
INSERT INTO intern_grad_prog VALUES (29, 'History Education');
INSERT INTO intern_grad_prog VALUES (30, 'Public History');
INSERT INTO intern_grad_prog VALUES (31, 'Library Science');
INSERT INTO intern_grad_prog VALUES (32, 'Marriage & Family Therapy');
INSERT INTO intern_grad_prog VALUES (33, 'Mathematics');
INSERT INTO intern_grad_prog VALUES (34, 'Mathematics Education');
INSERT INTO intern_grad_prog VALUES (35, 'Middle Grades Education');
INSERT INTO intern_grad_prog VALUES (36, 'Music Performance');
INSERT INTO intern_grad_prog VALUES (37, 'Music Education');
INSERT INTO intern_grad_prog VALUES (38, 'Music Therapy');
INSERT INTO intern_grad_prog VALUES (39, 'Nutrition');
INSERT INTO intern_grad_prog VALUES (40, 'Engineering Physics');
INSERT INTO intern_grad_prog VALUES (41, 'Political Science');
INSERT INTO intern_grad_prog VALUES (42, 'Psychology, General Experimental');
INSERT INTO intern_grad_prog VALUES (43, 'Clinical Health Psychology');
INSERT INTO intern_grad_prog VALUES (44, 'Industrial-Organizational Psychology and HRM');
INSERT INTO intern_grad_prog VALUES (45, 'School Psychology');
INSERT INTO intern_grad_prog VALUES (46, 'Public Administration');
INSERT INTO intern_grad_prog VALUES (47, 'Reading Education');
INSERT INTO intern_grad_prog VALUES (48, 'Romance Languages');
INSERT INTO intern_grad_prog VALUES (49, 'French');
INSERT INTO intern_grad_prog VALUES (50, 'Spanish');
INSERT INTO intern_grad_prog VALUES (51, 'School Administration');
INSERT INTO intern_grad_prog VALUES (52, 'Educational Administration');
INSERT INTO intern_grad_prog VALUES (53, 'School Counseling');
INSERT INTO intern_grad_prog VALUES (54, 'Social Work');
INSERT INTO intern_grad_prog VALUES (55, 'Sociology');
INSERT INTO intern_grad_prog VALUES (56, 'Special Education');
INSERT INTO intern_grad_prog VALUES (57, 'Speech Language Pathology');
INSERT INTO intern_grad_prog VALUES (58, 'Technology');
INSERT INTO intern_grad_prog VALUES (59, 'Women’s Studies');
-- End Grad. Programs

CREATE SEQUENCE intern_grad_prog_seq;
SELECT SETVAL('intern_grad_prog_seq', MAX(id)) FROM intern_grad_prog;
