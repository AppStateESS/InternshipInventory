alter table intern_internship add column last_name_meta character varying;
alter table intern_internship add column first_name_meta character varying;
alter table intern_internship add column middle_name_meta character varying;

update intern_internship set last_name_meta=metaphone(last_name,10), middle_name_meta=metaphone(middle_name,10), first_name_meta=metaphone(first_name,10);

