alter table intern_student add column address varchar(256);
alter table intern_student add column city varchar(256);
alter table intern_student add column state varchar(2);
alter table intern_student add column zip varchar(5);

alter table intern_student add column emergency_contact_name varchar(256);
alter table intern_student add column emergency_contact_relation varchar(256);
alter table intern_student add column emergency_contact_phone varchar(20);

alter table intern_internship add column loc_province character varying(255);

alter table intern_agency alter column state DROP NOT NULL;

alter table intern_internship drop approved;
alter table intern_internship drop approved_by;
alter table intern_internship drop approved_on;

alter table intern_internship add column state varchar(128);
update intern_internship set state = 'New';
alter table intern_internship alter column state SET NOT NULL;
alter table intern_internship add column oied_certified smallint not null default 0;

alter table intern_major alter column hidden SET DEFAULT 0;
update intern_major set hidden = 0 where hidden IS NULL;
alter table intern_major alter column hidden SET NOT NULL;

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
create sequence intern_change_history_seq;

alter table intern_internship drop column notes;

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
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WGC',' Watauga Global Community');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WS','Women’s Studies');

alter table intern_internship drop column course_subj;
alter table intern_internship add column course_subj integer REFERENCES intern_subject(id);
