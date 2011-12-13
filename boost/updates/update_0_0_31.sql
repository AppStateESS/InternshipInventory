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