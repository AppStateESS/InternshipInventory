alter table intern_agency alter country drop not null;
alter table intern_agency alter phone drop not null;
alter table intern_agency alter address_same_flag drop not null;
alter table intern_agency alter address_same_flag set default false;
alter table intern_agency alter supervisor_country drop not null;
alter table intern_agency alter supervisor_country drop default;

alter table intern_internship alter column paid drop not null;
alter table intern_internship alter column stipend drop not null;
alter table intern_internship drop column unpaid;

alter table intern_internship alter column multi_part drop not null;
alter table intern_internship alter column secondary_part drop not null;

alter table intern_internship alter column experience_type drop not null;
alter table intern_internship alter column experience_type set default 'internship';