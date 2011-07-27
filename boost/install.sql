BEGIN;

-- Defaults to not hidden.
CREATE TABLE intern_major (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

-- Defaults to not hidden.
CREATE TABLE intern_grad_prog (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_student (
       id INT NOT NULL,
       banner VARCHAR NOT NULL UNIQUE,
       first_name VARCHAR NOT NULL,
       middle_name VARCHAR,
       last_name VARCHAR NOT NULL,
       phone VARCHAR NOT NULL,
       email VARCHAR NOT NULL,
       ugrad_major INT NULL REFERENCES intern_major(id),
       grad_prog INT NULL REFERENCES intern_grad_prog(id), 
       PRIMARY KEY(id)
);
-- Below table is loaded with departments after CREATE TABLE statements.
CREATE TABLE intern_department (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_state (
       abbr varchar NOT NULL UNIQUE,
       full_name VARCHAR NOT NULL UNIQUE,
       active SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(abbr)
);

insert into intern_state (abbr, full_name, active) values
('AL', 'Alabama', 0),
('AK', 'Alaska', 0),
('AZ', 'Arizona', 0),
('AR', 'Arkansas', 0),
('CA', 'California', 0),
('CO', 'Colorado', 0),
('CT', 'Connecticut', 0),
('DE', 'Delaware', 0),
('DC', 'District Of Columbia', 0),
('FL', 'Florida', 0),
('GA', 'Georgia', 0),
('HI', 'Hawaii', 0),
('ID', 'Idaho', 0),
('IL', 'Illinois', 0),
('IN', 'Indiana', 0),
('IA', 'Iowa', 0),
('KS', 'Kansas', 0),
('KY', 'Kentucky', 0),
('LA', 'Louisiana', 0),
('ME', 'Maine', 0),
('MD', 'Maryland', 0),
('MA', 'Massachusetts', 0),
('MI', 'Michigan', 0),
('MN', 'Minnesota', 0),
('MS', 'Mississippi', 0),
('MO', 'Missouri', 0),
('MT', 'Montana', 0),
('NE', 'Nebraska', 0),
('NV', 'Nevada', 0),
('NH', 'New Hampshire', 0),
('NJ', 'New Jersey', 0),
('NM', 'New Mexico', 0),
('NY', 'New York', 0),
('NC', 'North Carolina', 0),
('ND', 'North Dakota', 0),
('OH', 'Ohio', 0),
('OK', 'Oklahoma', 0),
('OR', 'Oregon', 0),
('PA', 'Pennsylvania', 0),
('RI', 'Rhode Island', 0),
('SC', 'South Carolina', 0),
('SD', 'South Dakota', 0),
('TN', 'Tennessee', 0),
('TX', 'Texas', 0),
('UT', 'Utah', 0),
('VT', 'Vermont', 0),
('VA', 'Virginia', 0),
('WA', 'Washington', 0),
('WV', 'West Virginia', 0),
('WI', 'Wisconsin', 0),
('WY', 'Wyoming', 0);



CREATE TABLE intern_agency (
       id INT NOT NULL,
       name VARCHAR NOT NULL,  
       address VARCHAR NULL,
       city VARCHAR NULL,
       state VARCHAR NOT NULL,
       zip VARCHAR NULL,
       country VARCHAR NOT NULL,
       phone VARCHAR NOT NULL,
       supervisor_first_name VARCHAR NULL,
       supervisor_last_name VARCHAR NULL,
       supervisor_phone VARCHAR NULL,
       supervisor_email VARCHAR NULL,
       supervisor_fax VARCHAR NULL,
       supervisor_address VARCHAR NULL,
       supervisor_city VARCHAR NULL,
       supervisor_state VARCHAR NULL,
       supervisor_zip VARCHAR NULL,
       supervisor_country VARCHAR NOT NULL DEFAULT 'United States',
       address_same_flag BOOLEAN NOT NULL DEFAULT TRUE,
       PRIMARY KEY(id)
);

CREATE TABLE intern_faculty_supervisor (
       id INT NOT NULL,
       first_name VARCHAR NOT NULL,
       last_name VARCHAR NOT NULL,
       phone VARCHAR NOT NULL,
       email VARCHAR NOT NULL,
       department_id INT NOT NULL,
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
       department_id INT NOT NULL,
       start_date INT NOT NULL default 0,
       end_date INT NOT NULL default 0,
       internship SMALLINT NOT NULL,
       service_learn SMALLINT NOT NULL,
       independent_study SMALLINT NOT NULL,
       research_assist SMALLINT NOT NULL,
       student_teaching SMALLINT NOT NULL,
       clinical_practica SMALLINT NOT NULL,
       special_topics SMALLINT NOT NULL,
       other_type TEXT,
       loc_address varchar NULL,
       loc_city varchar NULL,
       loc_country varchar NULL,
       loc_state varchar NULL,
       loc_zip varchar NULL,
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
    username VARCHAR NOT NULL,
    department_id INT NOT NULL,
    PRIMARY KEY (id)
);

-- Add Departments
INSERT INTO intern_department VALUES  (1, 'Accounting');
INSERT INTO intern_department VALUES  (2, 'Anthropology');
INSERT INTO intern_department VALUES  (3, 'Art');
INSERT INTO intern_department VALUES  (4, 'Biology');
INSERT INTO intern_department VALUES  (5, 'Chemistry');
INSERT INTO intern_department VALUES  (6, 'Communication Sciences & Disorder');
INSERT INTO intern_department VALUES  (7, 'Communication');
INSERT INTO intern_department VALUES  (8, 'Computer Information Systems');
INSERT INTO intern_department VALUES  (9, 'Computer Science');
INSERT INTO intern_department VALUES (10, 'Curriculum & Instruction');
INSERT INTO intern_department VALUES (11, 'Economics');
INSERT INTO intern_department VALUES (12, 'Educational Leadership');
INSERT INTO intern_department VALUES (13, 'English');
INSERT INTO intern_department VALUES (14, 'Family Languages & Literatures');
INSERT INTO intern_department VALUES (15, 'Finance and Banking & Insurance');
INSERT INTO intern_department VALUES (16, 'Foreign Languages & Literatures');
INSERT INTO intern_department VALUES (17, 'Geography & Planning');
INSERT INTO intern_department VALUES (18, 'Geology');
INSERT INTO intern_department VALUES (19, 'Government & Justice Studies');
INSERT INTO intern_department VALUES (20, 'Health, Leisure & Exercise Science');
INSERT INTO intern_department VALUES (21, 'History');
INSERT INTO intern_department VALUES (22, 'Hospitality and Tourism Management');
INSERT INTO intern_department VALUES (23, 'Human Development & Psychological Counseling');
INSERT INTO intern_department VALUES (24, 'Language & Educational Studies');
INSERT INTO intern_department VALUES (25, 'Management');
INSERT INTO intern_department VALUES (26, 'Marketing');
INSERT INTO intern_department VALUES (27, 'Mathematical Sciences');
INSERT INTO intern_department VALUES (28, 'Military Science & Leadership');
INSERT INTO intern_department VALUES (29, 'Music');
INSERT INTO intern_department VALUES (30, 'Nursing');
INSERT INTO intern_department VALUES (31, 'Nutrition & Health Care Management');
INSERT INTO intern_department VALUES (32, 'Philosophy & Religion');
INSERT INTO intern_department VALUES (33, 'Physics & Astronomy');
INSERT INTO intern_department VALUES (34, 'Psychology');
INSERT INTO intern_department VALUES (35, 'Social Work');
INSERT INTO intern_department VALUES (36, 'Sociology');
INSERT INTO intern_department VALUES (37, 'Technology');
INSERT INTO intern_department VALUES (38, 'Theatre and Dance');
INSERT INTO intern_department VALUES (39, 'University College');
-- End departments

-- Add undergraduate majors
INSERT INTO intern_major VALUES (1, 'Accounting');
INSERT INTO intern_major VALUES (2, 'Actuarial Sciences');
INSERT INTO intern_major VALUES (3, 'Anthropology - Applied');
INSERT INTO intern_major VALUES (4, 'Anthropology - Archeology');
INSERT INTO intern_major VALUES (5, 'Anthropology - Biological');
INSERT INTO intern_major VALUES (6, 'Anthropology - General');
INSERT INTO intern_major VALUES (7, 'Anthropology - Multidisciplinary');
INSERT INTO intern_major VALUES (8, 'Anthropology - Sustainable Development');
INSERT INTO intern_major VALUES (9, 'Appalachian Studies');
INSERT INTO intern_major VALUES (10, 'Apparel & Textiles');
INSERT INTO intern_major VALUES (11, 'Appropriate Technology');
INSERT INTO intern_major VALUES (12, 'Art');
INSERT INTO intern_major VALUES (13, 'Art Education K-12');
INSERT INTO intern_major VALUES (14, 'Art Managemen');
INSERT INTO intern_major VALUES (15, 'Athletic Training');
INSERT INTO intern_major VALUES (16, 'Biology');
INSERT INTO intern_major VALUES (17, 'Biology - Cell/Molecular');
INSERT INTO intern_major VALUES (18, 'Biology - Ecology, Evolution and Environmental');
INSERT INTO intern_major VALUES (19, 'Biology - Secondary Education');
INSERT INTO intern_major VALUES (20, 'Building Science');
INSERT INTO intern_major VALUES (21, 'Business Education');
INSERT INTO intern_major VALUES (22, 'Chemistry');
INSERT INTO intern_major VALUES (23, 'Chemistry Secondary Education');
INSERT INTO intern_major VALUES (24, 'Child Development');
INSERT INTO intern_major VALUES (25, 'Child Development - Birth - K');
INSERT INTO intern_major VALUES (26, 'Communication Disorders');
INSERT INTO intern_major VALUES (27, 'Communication Studies');
INSERT INTO intern_major VALUES (28, 'Communication - Advertising');
INSERT INTO intern_major VALUES (29, 'Communication - Electronic Media, Broadcasting');
INSERT INTO intern_major VALUES (30, 'Communication - Journalism');
INSERT INTO intern_major VALUES (31, 'Communication - Public Relations');
INSERT INTO intern_major VALUES (32, 'Community and Regional Planning');
INSERT INTO intern_major VALUES (33, 'Computer Information Systems');
INSERT INTO intern_major VALUES (34, 'Computer Science');
INSERT INTO intern_major VALUES (35, 'Criminal Justice');
INSERT INTO intern_major VALUES (36, 'Dance Studies');
INSERT INTO intern_major VALUES (37, 'Economics');
INSERT INTO intern_major VALUES (38, 'Elementary Education');
INSERT INTO intern_major VALUES (39, 'English');
INSERT INTO intern_major VALUES (40, 'English, Secondary Education');
INSERT INTO intern_major VALUES (41, 'Environmental Science');
INSERT INTO intern_major VALUES (42, 'Exercise Science');
INSERT INTO intern_major VALUES (43, 'Family and Consumer Sciences - Secondary Education');
INSERT INTO intern_major VALUES (44, 'Finance and Banking');
INSERT INTO intern_major VALUES (45, 'French and Francophone Studies');
INSERT INTO intern_major VALUES (46, 'French and Francophone Studies - Education');
INSERT INTO intern_major VALUES (47, 'Geography');
INSERT INTO intern_major VALUES (48, 'Geology');
INSERT INTO intern_major VALUES (49, 'Geology, Secondary Education');
INSERT INTO intern_major VALUES (50, 'Global Studies');
INSERT INTO intern_major VALUES (51, 'Graphic Design');
INSERT INTO intern_major VALUES (52, 'Graphic Arts and Imaging Technology');
INSERT INTO intern_major VALUES (53, 'Health Care Management');
INSERT INTO intern_major VALUES (54, 'Health Education - Secondary Education');
INSERT INTO intern_major VALUES (55, 'Health Promotion');
INSERT INTO intern_major VALUES (56, 'History');
INSERT INTO intern_major VALUES (57, 'History - Social Studies Education');
INSERT INTO intern_major VALUES (58, 'Hospitality and Tourism Management');
INSERT INTO intern_major VALUES (59, 'Industrial Design');
INSERT INTO intern_major VALUES (60, 'Interdisciplinary Studies Program - IDS');
INSERT INTO intern_major VALUES (61, 'Interior Design');
INSERT INTO intern_major VALUES (62, 'International Business');
INSERT INTO intern_major VALUES (63, 'Management');
INSERT INTO intern_major VALUES (64, 'Marketing');
INSERT INTO intern_major VALUES (65, 'Mathematics');
INSERT INTO intern_major VALUES (66, 'Mathematics - Secondary Education');
INSERT INTO intern_major VALUES (67, 'Middle Grades Education');
INSERT INTO intern_major VALUES (68, 'Music Education');
INSERT INTO intern_major VALUES (69, 'Music Industry Studies');
INSERT INTO intern_major VALUES (70, 'Music Performance');
INSERT INTO intern_major VALUES (71, 'Music Therapy');
INSERT INTO intern_major VALUES (72, 'Nursing');
INSERT INTO intern_major VALUES (73, 'Nutrition and Foods: Dietetics');
INSERT INTO intern_major VALUES (74, 'Nutrition and Foods: Food Systems Management');
INSERT INTO intern_major VALUES (75, 'Philosophy');
INSERT INTO intern_major VALUES (76, 'Physical Education Teacher Education K-12');
INSERT INTO intern_major VALUES (77, 'Physics');
INSERT INTO intern_major VALUES (78, 'Physics - Secondary Education');
INSERT INTO intern_major VALUES (79, 'Political Science');
INSERT INTO intern_major VALUES (80, 'Psychology');
INSERT INTO intern_major VALUES (81, 'Recreation Management');
INSERT INTO intern_major VALUES (82, 'Religious Studies');
INSERT INTO intern_major VALUES (83, 'Risk Management & Insurance');
INSERT INTO intern_major VALUES (84, 'Social Work');
INSERT INTO intern_major VALUES (85, 'Sociology');
INSERT INTO intern_major VALUES (86, 'Spanish');
INSERT INTO intern_major VALUES (87, 'Spanish Education');
INSERT INTO intern_major VALUES (88, 'Statistics');
INSERT INTO intern_major VALUES (89, 'Studio Art');
INSERT INTO intern_major VALUES (90, 'Sustainable Development');
INSERT INTO intern_major VALUES (91, 'Teaching Theatre Arts - K-12');
INSERT INTO intern_major VALUES (92, 'Technical Photography');
INSERT INTO intern_major VALUES (93, 'Technical Education');
INSERT INTO intern_major VALUES (94, 'Theatre Arts');
INSERT INTO intern_major VALUES (95, 'Women''s Studies');
-- End majors

-- Add Graduate Programs
INSERT INTO intern_grad_prog VALUES (1, 'Accounting');
INSERT INTO intern_grad_prog VALUES (2, 'Appalachian Studies');
INSERT INTO intern_grad_prog VALUES (3, 'Biology');
INSERT INTO intern_grad_prog VALUES (4, 'Business Administration');
INSERT INTO intern_grad_prog VALUES (5, 'Child Development - Birth through K');
INSERT INTO intern_grad_prog VALUES (6, 'Clinical Mental Health Counseling');
INSERT INTO intern_grad_prog VALUES (7, 'Addictions Counseling');
INSERT INTO intern_grad_prog VALUES (8, 'Expressive Arts Therapy');
INSERT INTO intern_grad_prog VALUES (9, 'College Student Development');
INSERT INTO intern_grad_prog VALUES (10, 'Computer Science');
INSERT INTO intern_grad_prog VALUES (11, 'Criminal Justice');
INSERT INTO intern_grad_prog VALUES (12, 'Curriculum Specialist');
INSERT INTO intern_grad_prog VALUES (13, 'Educational Leadership');
INSERT INTO intern_grad_prog VALUES (14, 'Educational Media');
INSERT INTO intern_grad_prog VALUES (15, 'Education Media - Web-based Distance Learning');
INSERT INTO intern_grad_prog VALUES (16, 'Media Literacy');
INSERT INTO intern_grad_prog VALUES (17, 'Elementary Education');
INSERT INTO intern_grad_prog VALUES (18, 'English');
INSERT INTO intern_grad_prog VALUES (19, 'English Education');
INSERT INTO intern_grad_prog VALUES (20, 'Rhetoric and Composition');
INSERT INTO intern_grad_prog VALUES (21, 'Exercise Science');
INSERT INTO intern_grad_prog VALUES (22, 'Family & Consumer Sciences - Education');
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
INSERT INTO intern_grad_prog VALUES (59, 'Women''s Studies');
-- End Grad. Programs

-- Create and update sequences
CREATE SEQUENCE intern_department_seq;
SELECT SETVAL('intern_department_seq', MAX(id)) FROM intern_department;

CREATE SEQUENCE intern_major_seq;
SELECT SETVAL('intern_major_seq', MAX(id)) FROM intern_major;

CREATE SEQUENCE intern_grad_prog_seq;
SELECT SETVAL('intern_grad_prog_seq', MAX(id)) FROM intern_grad_prog;

CREATE SEQUENCE intern_student_seq;
CREATE SEQUENCE intern_agency_seq;
CREATE SEQUENCE intern_faculty_supervisor_seq;
CREATE SEQUENCE intern_term_seq;
CREATE SEQUENCE intern_internship_seq;
CREATE SEQUENCE intern_document_seq;
CREATE SEQUENCE intern_admin_seq;

COMMIT;
