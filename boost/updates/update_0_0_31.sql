alter table intern_student add column address varchar(256);
alter table intern_student add column city varchar(256);
alter table intern_student add column state varchar(2);
alter table intern_student add column zip varchar(5);

alter table intern_student add column emergency_contact_name varchar(256);
alter table intern_student add column emergency_contact_relation varchar(256);
alter table intern_student add column emergency_contact_phone varchar(20);