START TRANSACTION;

CREATE TABLE intern_major (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       PRIMARY KEY(id)
);

-- Add undergraduate majors
INSERT INTO intern_major VALUES (1, 'Accounting');
INSERT INTO intern_major VALUES (2, 'Actuarial Sciences');
INSERT INTO intern_major VALUES (3, 'Anthropology – Applied');
INSERT INTO intern_major VALUES (4, 'Anthropology – Archeology');
INSERT INTO intern_major VALUES (5, 'Anthropology – Biological');
INSERT INTO intern_major VALUES (6, 'Anthropology – General');
INSERT INTO intern_major VALUES (7, 'Anthropology – Multidisciplinary');
INSERT INTO intern_major VALUES (8, 'Anthropology – Sustainable Development');
INSERT INTO intern_major VALUES (9, 'Appalachian Studies');
INSERT INTO intern_major VALUES (10, 'Apparel & Textiles');
INSERT INTO intern_major VALUES (11, 'Appropriate Technology');
INSERT INTO intern_major VALUES (12, 'Art');
INSERT INTO intern_major VALUES (13, 'Art Education K-12');
INSERT INTO intern_major VALUES (14, 'Art Managemen');
INSERT INTO intern_major VALUES (15, 'Athletic Training');
INSERT INTO intern_major VALUES (16, 'Biology');
INSERT INTO intern_major VALUES (17, 'Biology – Cell/Molecular');
INSERT INTO intern_major VALUES (18, 'Biology – Ecology, Evolution and Environmental');
INSERT INTO intern_major VALUES (19, 'Biology – Secondary Education');
INSERT INTO intern_major VALUES (20, 'Building Science');
INSERT INTO intern_major VALUES (21, 'Business Education');
INSERT INTO intern_major VALUES (22, 'Chemistry');
INSERT INTO intern_major VALUES (23, 'Chemistry Secondary Education');
INSERT INTO intern_major VALUES (24, 'Child Development');
INSERT INTO intern_major VALUES (25, 'Child Development – Birth – K');
INSERT INTO intern_major VALUES (26, 'Communication Disorders');
INSERT INTO intern_major VALUES (27, 'Communication Studies');
INSERT INTO intern_major VALUES (28, 'Communication – Advertising');
INSERT INTO intern_major VALUES (29, 'Communication – Electronic Media, Broadcasting');
INSERT INTO intern_major VALUES (30, 'Communication – Journalism');
INSERT INTO intern_major VALUES (31, 'Communication – Public Relations');
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
INSERT INTO intern_major VALUES (43, 'Family and Consumer Sciences – Secondary Education');
INSERT INTO intern_major VALUES (44, 'Finance and Banking');
INSERT INTO intern_major VALUES (45, 'French and Francophone Studies');
INSERT INTO intern_major VALUES (46, 'French and Francophone Studies – Education');
INSERT INTO intern_major VALUES (47, 'Geography');
INSERT INTO intern_major VALUES (48, 'Geology');
INSERT INTO intern_major VALUES (49, 'Geology, Secondary Education');
INSERT INTO intern_major VALUES (50, 'Global Studies');
INSERT INTO intern_major VALUES (51, 'Graphic Design');
INSERT INTO intern_major VALUES (52, 'Graphic Arts and Imaging Technology');
INSERT INTO intern_major VALUES (53, 'Health Care Management');
INSERT INTO intern_major VALUES (54, 'Health Education – Secondary Education');
INSERT INTO intern_major VALUES (55, 'Health Promotion');
INSERT INTO intern_major VALUES (56, 'History');
INSERT INTO intern_major VALUES (57, 'History – Social Studies Education');
INSERT INTO intern_major VALUES (58, 'Hospitality and Tourism Management');
INSERT INTO intern_major VALUES (59, 'Industrial Design');
INSERT INTO intern_major VALUES (60, 'Interdisciplinary Studies Program – IDS');
INSERT INTO intern_major VALUES (61, 'Interior Design');
INSERT INTO intern_major VALUES (62, 'International Business');
INSERT INTO intern_major VALUES (63, 'Management');
INSERT INTO intern_major VALUES (64, 'Marketing');
INSERT INTO intern_major VALUES (65, 'Mathematics');
INSERT INTO intern_major VALUES (66, 'Mathematics – Secondary Education');
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
INSERT INTO intern_major VALUES (78, 'Physics – Secondary Education');
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
INSERT INTO intern_major VALUES (91, 'Teaching Theatre Arts – K-12');
INSERT INTO intern_major VALUES (92, 'Technical Photography');
INSERT INTO intern_major VALUES (93, 'Technical Education');
INSERT INTO intern_major VALUES (94, 'Theatre Arts');
INSERT INTO intern_major VALUES (95, 'Women’s Studies');
-- End majors

-- Create and update sequence
CREATE SEQUENCE intern_major_seq;
SELECT SETVAL('intern_major_seq', MAX(id)) FROM intern_major;

COMMIT;