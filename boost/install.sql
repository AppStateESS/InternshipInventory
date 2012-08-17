BEGIN;

-- Defaults to not hidden.
CREATE TABLE intern_major (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NOT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

-- Defaults to not hidden.
CREATE TABLE intern_grad_prog (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
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

INSERT INTO intern_state (abbr, full_name, active) VALUES ('AL', 'Alabama', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AK', 'Alaska', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AZ', 'Arizona', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AR', 'Arkansas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CA', 'California', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CO', 'Colorado', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CT', 'Connecticut', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('DE', 'Delaware', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('DC', 'District Of Columbia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('FL', 'Florida', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('GA', 'Georgia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('HI', 'Hawaii', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ID', 'Idaho', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IL', 'Illinois', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IN', 'Indiana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IA', 'Iowa', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('KS', 'Kansas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('KY', 'Kentucky', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('LA', 'Louisiana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ME', 'Maine', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MD', 'Maryland', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MA', 'Massachusetts', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MI', 'Michigan', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MN', 'Minnesota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MS', 'Mississippi', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MO', 'Missouri', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MT', 'Montana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NE', 'Nebraska', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NV', 'Nevada', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NH', 'New Hampshire', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NJ', 'New Jersey', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NM', 'New Mexico', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NY', 'New York', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NC', 'North Carolina', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ND', 'North Dakota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OH', 'Ohio', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OK', 'Oklahoma', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OR', 'Oregon', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('PA', 'Pennsylvania', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('RI', 'Rhode Island', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('SC', 'South Carolina', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('SD', 'South Dakota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('TN', 'Tennessee', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('TX', 'Texas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('UT', 'Utah', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('VT', 'Vermont', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('VA', 'Virginia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WA', 'Washington', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WV', 'West Virginia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WI', 'Wisconsin', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WY', 'Wyoming', 0);

CREATE TABLE intern_subject (
    id INT NOT NULL,
    abbreviation character varying(10) NOT NULL,
    description character varying(128) NOT NULL,
    PRIMARY KEY(id)
);

CREATE SEQUENCE intern_subject_seq;

INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ACC','Accounting');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AMU','Applied Music');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ANT','Anthropology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ARB','Arabic');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ART','Art');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AS','Appalachian Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AST','Astronomy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AT','Athletic Training');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BE','Business Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BIO','Biology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BUS','Business');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CHE','Chemistry');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CHN','Chinese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CI','Curriculum and Instruction');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CIS','Computer Information Systems');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CJ','Criminal Justice');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'COM','Communication');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CS','Computer Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CSD','Communication Sciences and Disorders');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'DAN','Dance');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ECO','Economics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'EDL','Educational Leadership');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ENG','English');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ENV','Environmental Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ES','Exercise Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FCS','Family and Consumer Sciences');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FDN','Foundations of Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FER','Fermentation Sciences');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FIN','Finance, Banking and Insurance');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FL','Foreign Languages and Literatures');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FRE','French');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GER','German');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GHY','Geography');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GLS','Global Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GLY','Geology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GRA','Graphic Arts and Imaging Technology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GS','General Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSA','General Science Astronomy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSB','General Science Biology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSC','General Science Chemistry');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSG','General Science Geology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSP','General Science Physics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HCM','Health Care Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HE','Higher Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HED','Health Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HIS','History');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HON','Honors');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HOS','Hospitality Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HP','Health Promotion');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HPC','Human Development and Psychological Counseling');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'IDS','Interdisciplinary Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'IND','Industrial Design');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'INT','Interior Design');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ITC','Instructional Technology/Computers');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'JPN','Japanese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LAT','Latin');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LAW','Law');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LIB','Library Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LSA','Leadership in School Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MAT','Mathematics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MBA','Master of Business Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MGT','Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MKT','Marketing');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MSL','Military Science and Leadership');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MUS','Music');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'NUR','Nursing');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'NUT','Nutrition');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PA','Public Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PE','Physical Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PHL','Philosophy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PHY','Physics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PLN','Community and Regional Planning');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'POM','Production/Operations Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'POR','Portuguese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PS','Political Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PSY','Psychology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RE','Reading');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'REL','Religious Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RES','Research');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RM','Recreation Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RSN','Russian');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SCM','Supply Chain Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SD','Sustainable Development');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SNH','Spanish');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SOC','Sociology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SPE','Special Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'STT','Statistics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SW','Social Work');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'TEC','Technology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'THR','Theatre');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'UCO','University College');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'US','University Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WGC','Watauga Global Community');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WS','Women’s Studies');

CREATE TABLE intern_agency (
       id INT NOT NULL,
       name VARCHAR NOT NULL,  
       address VARCHAR NULL,
       city VARCHAR NULL,
       state VARCHAR,
       zip VARCHAR NULL,
       country VARCHAR NOT NULL,
       phone VARCHAR NOT NULL,
       supervisor_first_name VARCHAR NULL,
       supervisor_last_name VARCHAR NULL,
       supervisor_title VARCHAR NULL,
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
       state varchar(128) NOT NULL,
       oied_certified smallint not null default 0,
       term INT NOT NULL REFERENCES intern_term(term),
       student_id INT NOT NULL REFERENCES intern_student(id),
       agency_id INT NOT NULL REFERENCES intern_agency(id),
       faculty_supervisor_id INT NOT NULL REFERENCES intern_faculty_supervisor(id),
       department_id INT NOT NULL,
       start_date INT NOT NULL default 0,
       end_date INT NOT NULL default 0,
       internship SMALLINT NOT NULL,
       student_teaching SMALLINT NOT NULL,
       clinical_practica SMALLINT NOT NULL,
       
       banner VARCHAR NOT NULL UNIQUE,
       first_name VARCHAR NOT NULL,
       middle_name VARCHAR,
       last_name VARCHAR NOT NULL,
       gpa VARCHAR NULL,
       level VARCHAR NOT NULL,
       phone VARCHAR NOT NULL,
       email VARCHAR NOT NULL,
       ugrad_major INT NULL REFERENCES intern_major(id),
       grad_prog INT NULL REFERENCES intern_grad_prog(id),
       student_address varchar(256),
       student_city varchar(256),
       student_state varchar(2),
       student_zip varchar(5),
       emergency_contact_name varchar(256),
       emergency_contact_relation varchar(256),
       emergency_contact_phone varchar(20),
       campus character varying(128) NOT NULL,
       
       loc_address varchar NULL,
       loc_city varchar NULL,
       loc_state varchar NULL,
       loc_zip varchar NULL,
       loc_province varchar(255) NULL,
       loc_country varchar NULL,
       course_subj integer REFERENCES intern_subject(id),
       course_no varchar(20) null,
       course_sect varchar(20) null,
       course_title varchar(40) null,
       credits INT NULL,
       avg_hours_week INT NULL,
       domestic SMALLINT NOT NULL, 
       international SMALLINT NOT NULL,
       paid SMALLINT NOT NULL,    
       stipend SMALLINT NOT NULL, 
       unpaid SMALLINT NOT NULL,
       pay_rate VARCHAR NULL,
       multi_part SMALLINT NOT NULL,
       secondary_part SMALLINT NOT NULL,
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

CREATE TABLE intern_change_history (
    id INT NOT NULL,
    internship_id INT NOT NULL REFERENCES intern_internship(id),
    username character varying(40) NOT NULL,
    timestamp int NOT NULL,
    from_state character varying(40) NOT NULL,
    to_state character varying(40) NOT NULL,
    note text,
    PRIMARY KEY(id)
);

CREATE INDEX change_history_internshp_idx ON intern_change_history(internship_id);

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
