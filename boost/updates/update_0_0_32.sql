alter table intern_student add column campus character varying(128);
update intern_student set campus = 'main_campus';
alter table intern_student alter column campus set not null;