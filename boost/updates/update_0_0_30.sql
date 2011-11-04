alter table intern_student add column level character varying;
update intern_student set level = 'ugrad';
alter table intern_student alter column level set NOT NULL;

alter table intern_agency add column supervisor_title character varying;
alter table intern_student add column gpa character varying;
alter table intern_internship add column pay_rate character varying;