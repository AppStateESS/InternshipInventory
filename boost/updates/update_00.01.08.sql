alter table intern_agency alter country drop not null;
alter table intern_agency alter phone drop not null;
alter table intern_agency alter address_same_flag drop not null;
alter table intern_agency alter address_same_flag set default false;
alter table intern_agency alter supervisor_country drop not null;
alter table intern_agency alter supervisor_country drop default;