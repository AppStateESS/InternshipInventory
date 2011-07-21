--BEGIN;

-- Defaults to not hidden.
CREATE TABLE intern_major (
       id INT NOT NULL,
       name VARCHAR(50) NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

-- Defaults to not hidden.
CREATE TABLE intern_grad_prog (
       id INT NOT NULL,
       name varchar(50)NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_student (
       id INT NOT NULL,
       banner INT NOT NULL UNIQUE,
       first_name varchar(30)NOT NULL,
       middle_name VARCHAR(30),
       last_name varchar(30)NOT NULL,
       phone varchar(30)NOT NULL,
       email varchar(40)NOT NULL,
       ugrad_major INT NULL REFERENCES intern_major(id),
       grad_prog INT NULL REFERENCES intern_grad_prog(id), 
       PRIMARY KEY(id)
);
-- Below table is loaded with departments after CREATE TABLE statements.
CREATE TABLE intern_department (
       id INT NOT NULL,
       name varchar(50)NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_agency (
       id INT NOT NULL,
       name varchar(50)NOT NULL,  
       address varchar(80)NOT NULL,
       city varchar(30)NOT NULL DEFAULT '',
       state varchar(30)NULL,
       zip INT NULL,
       country varchar(30)NOT NULL,
       phone varchar(30)NOT NULL,
       supervisor_first_name varchar(30)NOT NULL,
       supervisor_last_name varchar(30)NOT NULL,
       supervisor_phone varchar(30)NOT NULL,
       supervisor_email varchar(50)NOT NULL,
       supervisor_fax varchar(50)NOT NULL,
       supervisor_address varchar(50)NOT NULL,
       supervisor_city varchar(50)NOT NULL DEFAULT '',
       supervisor_state varchar(50)NOT NULL DEFAULT 'XX',
       supervisor_zip INT NOT NULL DEFAULT 00000,
       supervisor_country varchar(50)NOT NULL DEFAULT 'United States',
       address_same_flag BOOLEAN NOT NULL DEFAULT TRUE,
       PRIMARY KEY(id)
);

CREATE TABLE intern_faculty_supervisor (
       id INT NOT NULL,
       first_name varchar(30)NOT NULL,
       last_name varchar(30)NOT NULL,
       phone varchar(30)NOT NULL,
       email varchar(50)NOT NULL,
       department_id INT NOT NULL REFERENCES intern_department(id),
       PRIMARY KEY(id)              
);

-- Term format YYYY# (e.g. 20111 is Spring 2011, 20113 is Fall 2011)
CREATE TABLE intern_term (
       id INT NOT NULL,
       term INT NOT NULL UNIQUE, 
       PRIMARY KEY (id)
);

CREATE TABLE intern_internship (
       id INT NOT NULL,
       term INT NOT NULL REFERENCES intern_term(term),
       student_id INT NOT NULL REFERENCES intern_student(id),
       agency_id INT NOT NULL REFERENCES intern_agency(id),
       faculty_supervisor_id INT NOT NULL REFERENCES intern_faculty_supervisor(id),
       department_id INT NOT NULL REFERENCES intern_department(id),
       start_date INT NOT NULL,
       end_date INT NOT NULL,
       internship SMALLINT NOT NULL,
       service_learn SMALLINT NOT NULL,
       independent_study SMALLINT NOT NULL,
       research_assist SMALLINT NOT NULL,
       student_teaching SMALLINT NOT NULL,
       clinical_practica SMALLINT NOT NULL,
       special_topics SMALLINT NOT NULL,
       other_type TEXT,
       credits INT NULL,
       avg_hours_week INT NULL,
       domestic SMALLINT NOT NULL, 
       international SMALLINT NOT NULL,
       paid SMALLINT NOT NULL,    
       stipend SMALLINT NOT NULL, 
       unpaid SMALLINT NOT NULL,
       notes TEXT,
       PRIMARY KEY(id)
);

CREATE TABLE intern_document (
    id int NOT NULL,
    internship_id int NOT NULL REFERENCES intern_internship(id) ,
    document_fc_id int NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE intern_admin (
    id INT NOT NULL,
    username varchar(50)NOT NULL,
    department_id INT NOT NULL REFERENCES intern_department(id),
    PRIMARY KEY (id)
);

-- Add Departments
INSERT INTO intern_department (id,name) VALUES  (1, 'Accounting');
INSERT INTO intern_department (id, name) VALUES  (2, 'Anthropology');
INSERT INTO intern_department (id, name) VALUES  (3, 'Art');
INSERT INTO intern_department (id, name) VALUES  (4, 'Biology');
INSERT INTO intern_department (id, name) VALUES  (5, 'Chemistry');
INSERT INTO intern_department (id, name) VALUES  (6, 'Communication Sciences & Disorder');
INSERT INTO intern_department (id, name) VALUES  (7, 'Communication');
INSERT INTO intern_department (id, name) VALUES  (8, 'Computer Information Systems');
INSERT INTO intern_department (id, name) VALUES  (9, 'Computer Science');
INSERT INTO intern_department (id, name) VALUES (10, 'Curriculum & Instruction');
INSERT INTO intern_department (id, name) VALUES (11, 'Economics');
INSERT INTO intern_department (id, name) VALUES (12, 'Educational Leadership');
INSERT INTO intern_department (id, name) VALUES (13, 'English');
INSERT INTO intern_department (id, name) VALUES (14, 'Family Languages & Literatures');
INSERT INTO intern_department (id, name) VALUES (15, 'Finance and Banking & Insurance');
INSERT INTO intern_department (id, name) VALUES (16, 'Foreign Languages & Literatures');
INSERT INTO intern_department (id, name) VALUES (17, 'Geography & Planning');
INSERT INTO intern_department (id, name) VALUES (18, 'Geology');
INSERT INTO intern_department (id, name) VALUES (19, 'Government & Justice Studies');
INSERT INTO intern_department (id, name) VALUES (20, 'Health, Leisure & Exercise Science');
INSERT INTO intern_department (id, name) VALUES (21, 'History');
INSERT INTO intern_department (id, name) VALUES (22, 'Hospitality and Tourism Management');
INSERT INTO intern_department (id, name) VALUES (23, 'Human Development & Psychological Counseling');
INSERT INTO intern_department (id, name) VALUES (24, 'Language & Educational Studies');
INSERT INTO intern_department (id, name) VALUES (25, 'Management');
INSERT INTO intern_department (id, name) VALUES (26, 'Marketing');
INSERT INTO intern_department (id, name) VALUES (27, 'Mathematical Sciences');
INSERT INTO intern_department (id, name) VALUES (28, 'Military Science & Leadership');
INSERT INTO intern_department (id, name) VALUES (29, 'Music');
INSERT INTO intern_department (id, name) VALUES (30, 'Nursing');
INSERT INTO intern_department (id, name) VALUES (31, 'Nutrition & Health Care Management');
INSERT INTO intern_department (id, name) VALUES (32, 'Philosophy & Religion');
INSERT INTO intern_department (id, name) VALUES (33, 'Physics & Astronomy');
INSERT INTO intern_department (id, name) VALUES (34, 'Psychology');
INSERT INTO intern_department (id, name) VALUES (35, 'Social Work');
INSERT INTO intern_department (id, name) VALUES (36, 'Sociology');
INSERT INTO intern_department (id, name) VALUES (37, 'Technology');
INSERT INTO intern_department (id, name) VALUES (38, 'Theatre and Dance');
INSERT INTO intern_department (id, name) VALUES (39, 'University College');
-- End departments

-- Add undergraduate majors
INSERT INTO intern_major (id, name) VALUES (1, 'Accounting');
INSERT INTO intern_major (id, name) VALUES (2, 'Actuarial Sciences');
INSERT INTO intern_major (id, name) VALUES (3, 'Anthropology - Applied');
INSERT INTO intern_major (id, name) VALUES (4, 'Anthropology - Archeology');
INSERT INTO intern_major (id, name) VALUES (5, 'Anthropology - Biological');
INSERT INTO intern_major (id, name) VALUES (6, 'Anthropology - General');
INSERT INTO intern_major (id, name) VALUES (7, 'Anthropology - Multidisciplinary');
INSERT INTO intern_major (id, name) VALUES (8, 'Anthropology - Sustainable Development');
INSERT INTO intern_major (id, name) VALUES (9, 'Appalachian Studies');
INSERT INTO intern_major (id, name) VALUES (10, 'Apparel & Textiles');
INSERT INTO intern_major (id, name) VALUES (11, 'Appropriate Technology');
INSERT INTO intern_major (id, name) VALUES (12, 'Art');
INSERT INTO intern_major (id, name) VALUES (13, 'Art Education K-12');
INSERT INTO intern_major (id, name) VALUES (14, 'Art Managemen');
INSERT INTO intern_major (id, name) VALUES (15, 'Athletic Training');
INSERT INTO intern_major (id, name) VALUES (16, 'Biology');
INSERT INTO intern_major (id, name) VALUES (17, 'Biology - Cell/Molecular');
INSERT INTO intern_major (id, name) VALUES (18, 'Biology - Ecology, Evolution and Environmental');
INSERT INTO intern_major (id, name) VALUES (19, 'Biology - Secondary Education');
INSERT INTO intern_major (id, name) VALUES (20, 'Building Science');
INSERT INTO intern_major (id, name) VALUES (21, 'Business Education');
INSERT INTO intern_major (id, name) VALUES (22, 'Chemistry');
INSERT INTO intern_major (id, name) VALUES (23, 'Chemistry Secondary Education');
INSERT INTO intern_major (id, name) VALUES (24, 'Child Development');
INSERT INTO intern_major (id, name) VALUES (25, 'Child Development - Birth - K');
INSERT INTO intern_major (id, name) VALUES (26, 'Communication Disorders');
INSERT INTO intern_major (id, name) VALUES (27, 'Communication Studies');
INSERT INTO intern_major (id, name) VALUES (28, 'Communication - Advertising');
INSERT INTO intern_major (id, name) VALUES (29, 'Communication - Electronic Media, Broadcasting');
INSERT INTO intern_major (id, name) VALUES (30, 'Communication - Journalism');
INSERT INTO intern_major (id, name) VALUES (31, 'Communication - Public Relations');
INSERT INTO intern_major (id, name) VALUES (32, 'Community and Regional Planning');
INSERT INTO intern_major (id, name) VALUES (33, 'Computer Information Systems');
INSERT INTO intern_major (id, name) VALUES (34, 'Computer Science');
INSERT INTO intern_major (id, name) VALUES (35, 'Criminal Justice');
INSERT INTO intern_major (id, name) VALUES (36, 'Dance Studies');
INSERT INTO intern_major (id, name) VALUES (37, 'Economics');
INSERT INTO intern_major (id, name) VALUES (38, 'Elementary Education');
INSERT INTO intern_major (id, name) VALUES (39, 'English');
INSERT INTO intern_major (id, name) VALUES (40, 'English, Secondary Education');
INSERT INTO intern_major (id, name) VALUES (41, 'Environmental Science');
INSERT INTO intern_major (id, name) VALUES (42, 'Exercise Science');
INSERT INTO intern_major (id, name) VALUES (43, 'Family and Consumer Sciences - Secondary Education');
INSERT INTO intern_major (id, name) VALUES (44, 'Finance and Banking');
INSERT INTO intern_major (id, name) VALUES (45, 'French and Francophone Studies');
INSERT INTO intern_major (id, name) VALUES (46, 'French and Francophone Studies - Education');
INSERT INTO intern_major (id, name) VALUES (47, 'Geography');
INSERT INTO intern_major (id, name) VALUES (48, 'Geology');
INSERT INTO intern_major (id, name) VALUES (49, 'Geology, Secondary Education');
INSERT INTO intern_major (id, name) VALUES (50, 'Global Studies');
INSERT INTO intern_major (id, name) VALUES (51, 'Graphic Design');
INSERT INTO intern_major (id, name) VALUES (52, 'Graphic Arts and Imaging Technology');
INSERT INTO intern_major (id, name) VALUES (53, 'Health Care Management');
INSERT INTO intern_major (id, name) VALUES (54, 'Health Education - Secondary Education');
INSERT INTO intern_major (id, name) VALUES (55, 'Health Promotion');
INSERT INTO intern_major (id, name) VALUES (56, 'History');
INSERT INTO intern_major (id, name) VALUES (57, 'History - Social Studies Education');
INSERT INTO intern_major (id, name) VALUES (58, 'Hospitality and Tourism Management');
INSERT INTO intern_major (id, name) VALUES (59, 'Industrial Design');
INSERT INTO intern_major (id, name) VALUES (60, 'Interdisciplinary Studies Program - IDS');
INSERT INTO intern_major (id, name) VALUES (61, 'Interior Design');
INSERT INTO intern_major (id, name) VALUES (62, 'International Business');
INSERT INTO intern_major (id, name) VALUES (63, 'Management');
INSERT INTO intern_major (id, name) VALUES (64, 'Marketing');
INSERT INTO intern_major (id, name) VALUES (65, 'Mathematics');
INSERT INTO intern_major (id, name) VALUES (66, 'Mathematics - Secondary Education');
INSERT INTO intern_major (id, name) VALUES (67, 'Middle Grades Education');
INSERT INTO intern_major (id, name) VALUES (68, 'Music Education');
INSERT INTO intern_major (id, name) VALUES (69, 'Music Industry Studies');
INSERT INTO intern_major (id, name) VALUES (70, 'Music Performance');
INSERT INTO intern_major (id, name) VALUES (71, 'Music Therapy');
INSERT INTO intern_major (id, name) VALUES (72, 'Nursing');
INSERT INTO intern_major (id, name) VALUES (73, 'Nutrition and Foods: Dietetics');
INSERT INTO intern_major (id, name) VALUES (74, 'Nutrition and Foods: Food Systems Management');
INSERT INTO intern_major (id, name) VALUES (75, 'Philosophy');
INSERT INTO intern_major (id, name) VALUES (76, 'Physical Education Teacher Education K-12');
INSERT INTO intern_major (id, name) VALUES (77, 'Physics');
INSERT INTO intern_major (id, name) VALUES (78, 'Physics - Secondary Education');
INSERT INTO intern_major (id, name) VALUES (79, 'Political Science');
INSERT INTO intern_major (id, name) VALUES (80, 'Psychology');
INSERT INTO intern_major (id, name) VALUES (81, 'Recreation Management');
INSERT INTO intern_major (id, name) VALUES (82, 'Religious Studies');
INSERT INTO intern_major (id, name) VALUES (83, 'Risk Management & Insurance');
INSERT INTO intern_major (id, name) VALUES (84, 'Social Work');
INSERT INTO intern_major (id, name) VALUES (85, 'Sociology');
INSERT INTO intern_major (id, name) VALUES (86, 'Spanish');
INSERT INTO intern_major (id, name) VALUES (87, 'Spanish Education');
INSERT INTO intern_major (id, name) VALUES (88, 'Statistics');
INSERT INTO intern_major (id, name) VALUES (89, 'Studio Art');
INSERT INTO intern_major (id, name) VALUES (90, 'Sustainable Development');
INSERT INTO intern_major (id, name) VALUES (91, 'Teaching Theatre Arts - K-12');
INSERT INTO intern_major (id, name) VALUES (92, 'Technical Photography');
INSERT INTO intern_major (id, name) VALUES (93, 'Technical Education');
INSERT INTO intern_major (id, name) VALUES (94, 'Theatre Arts');
INSERT INTO intern_major (id, name) VALUES (95, 'Women''s Studies');
-- End majors

-- Add Graduate Programs
INSERT INTO intern_grad_prog (id, name) VALUES (1, 'Accounting');
INSERT INTO intern_grad_prog (id, name) VALUES (2, 'Appalachian Studies');
INSERT INTO intern_grad_prog (id, name) VALUES (3, 'Biology');
INSERT INTO intern_grad_prog (id, name) VALUES (4, 'Business Administration');
INSERT INTO intern_grad_prog (id, name) VALUES (5, 'Child Development - Birth through K');
INSERT INTO intern_grad_prog (id, name) VALUES (6, 'Clinical Mental Health Counseling');
INSERT INTO intern_grad_prog (id, name) VALUES (7, 'Addictions Counseling');
INSERT INTO intern_grad_prog (id, name) VALUES (8, 'Expressive Arts Therapy');
INSERT INTO intern_grad_prog (id, name) VALUES (9, 'College Student Development');
INSERT INTO intern_grad_prog (id, name) VALUES (10, 'Computer Science');
INSERT INTO intern_grad_prog (id, name) VALUES (11, 'Criminal Justice');
INSERT INTO intern_grad_prog (id, name) VALUES (12, 'Curriculum Specialist');
INSERT INTO intern_grad_prog (id, name) VALUES (13, 'Educational Leadership');
INSERT INTO intern_grad_prog (id, name) VALUES (14, 'Educational Media');
INSERT INTO intern_grad_prog (id, name) VALUES (15, 'Education Media - Web-based Distance Learning');
INSERT INTO intern_grad_prog (id, name) VALUES (16, 'Media Literacy');
INSERT INTO intern_grad_prog (id, name) VALUES (17, 'Elementary Education');
INSERT INTO intern_grad_prog (id, name) VALUES (18, 'English');
INSERT INTO intern_grad_prog (id, name) VALUES (19, 'English Education');
INSERT INTO intern_grad_prog (id, name) VALUES (20, 'Rhetoric and Composition');
INSERT INTO intern_grad_prog (id, name) VALUES (21, 'Exercise Science');
INSERT INTO intern_grad_prog (id, name) VALUES (22, 'Family & Consumer Sciences - Education');
INSERT INTO intern_grad_prog (id, name) VALUES (23, 'Geography');
INSERT INTO intern_grad_prog (id, name) VALUES (24, 'GIS Science');
INSERT INTO intern_grad_prog (id, name) VALUES (25, 'Planning');
INSERT INTO intern_grad_prog (id, name) VALUES (26, 'Gerontology');
INSERT INTO intern_grad_prog (id, name) VALUES (27, 'Higher Education');
INSERT INTO intern_grad_prog (id, name) VALUES (28, 'History');
INSERT INTO intern_grad_prog (id, name) VALUES (29, 'History Education');
INSERT INTO intern_grad_prog (id, name) VALUES (30, 'Public History');
INSERT INTO intern_grad_prog (id, name) VALUES (31, 'Library Science');
INSERT INTO intern_grad_prog (id, name) VALUES (32, 'Marriage & Family Therapy');
INSERT INTO intern_grad_prog (id, name) VALUES (33, 'Mathematics');
INSERT INTO intern_grad_prog (id, name) VALUES (34, 'Mathematics Education');
INSERT INTO intern_grad_prog (id, name) VALUES (35, 'Middle Grades Education');
INSERT INTO intern_grad_prog (id, name) VALUES (36, 'Music Performance');
INSERT INTO intern_grad_prog (id, name) VALUES (37, 'Music Education');
INSERT INTO intern_grad_prog (id, name) VALUES (38, 'Music Therapy');
INSERT INTO intern_grad_prog (id, name) VALUES (39, 'Nutrition');
INSERT INTO intern_grad_prog (id, name) VALUES (40, 'Engineering Physics');
INSERT INTO intern_grad_prog (id, name) VALUES (41, 'Political Science');
INSERT INTO intern_grad_prog (id, name) VALUES (42, 'Psychology, General Experimental');
INSERT INTO intern_grad_prog (id, name) VALUES (43, 'Clinical Health Psychology');
INSERT INTO intern_grad_prog (id, name) VALUES (44, 'Industrial-Organizational Psychology and HRM');
INSERT INTO intern_grad_prog (id, name) VALUES (45, 'School Psychology');
INSERT INTO intern_grad_prog (id, name) VALUES (46, 'Public Administration');
INSERT INTO intern_grad_prog (id, name) VALUES (47, 'Reading Education');
INSERT INTO intern_grad_prog (id, name) VALUES (48, 'Romance Languages');
INSERT INTO intern_grad_prog (id, name) VALUES (49, 'French');
INSERT INTO intern_grad_prog (id, name) VALUES (50, 'Spanish');
INSERT INTO intern_grad_prog (id, name) VALUES (51, 'School Administration');
INSERT INTO intern_grad_prog (id, name) VALUES (52, 'Educational Administration');
INSERT INTO intern_grad_prog (id, name) VALUES (53, 'School Counseling');
INSERT INTO intern_grad_prog (id, name) VALUES (54, 'Social Work');
INSERT INTO intern_grad_prog (id, name) VALUES (55, 'Sociology');
INSERT INTO intern_grad_prog (id, name) VALUES (56, 'Special Education');
INSERT INTO intern_grad_prog (id, name) VALUES (57, 'Speech Language Pathology');
INSERT INTO intern_grad_prog (id, name) VALUES (58, 'Technology');
INSERT INTO intern_grad_prog (id, name) VALUES (59, 'Women''s Studies');
-- End Grad. Programs

-- Create and update sequences
-- CREATE SEQUENCE intern_department_seq;
--SELECT SETVAL('intern_department_seq', MAX(id)) FROM intern_department;

--CREATE SEQUENCE intern_major_seq;
--SELECT SETVAL('intern_major_seq', MAX(id)) FROM intern_major;

--CREATE SEQUENCE intern_grad_prog_seq;
--SELECT SETVAL('intern_grad_prog_seq', MAX(id)) FROM intern_grad_prog;

--CREATE SEQUENCE intern_student_seq;
--CREATE SEQUENCE intern_agency_seq;
--CREATE SEQUENCE intern_faculty_supervisor_seq;
--CREATE SEQUENCE intern_term_seq;
--CREATE SEQUENCE intern_internship_seq;
--CREATE SEQUENCE intern_document_seq;
--CREATE SEQUENCE intern_admin_seq;

--COMMIT;
