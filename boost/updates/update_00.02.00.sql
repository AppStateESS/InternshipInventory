CREATE TABLE intern_local_student_data (
    student_id          character varying not null,
    user_name           character varying not null,
    email               character varying not null,

    first_name          character varying,
    middle_name         character varying,
    last_name           character varying,
    preferred_name      character varying,
    confidential        character varying,

    birth_date          character varying,
    gender              character varying,

    level               character varying,
    campus              character varying,
    gpa                 double precision,
    credit_hours        integer default 0,
    major_code          character varying,
    major_description   character varying,
    grad_date           character varying,

    phone               character varying,
    address             character varying,
    address2            character varying,
    city                character varying,
    state               character varying,
    zip                 character varying,

    primary key(student_id)
);

alter table intern_internship drop constraint intern_internship_term_fkey;

alter table intern_term drop constraint intern_term_term_key;

alter table intern_term alter column term TYPE character varying;
alter table intern_term alter column term drop default;
alter table intern_term alter column term set not null;

alter table intern_term drop column id;
alter table intern_term add primary key (term);

alter table intern_internship alter column term TYPE character varying;

alter table intern_internship add constraint intern_internship_term_fkey FOREIGN KEY (term) REFERENCES intern_term(term);

alter table intern_term add column description character varying;
alter table intern_term add column available_on_timestamp integer;
alter table intern_term add column census_date_timestamp integer;
alter table intern_term add column start_timestamp integer;
alter table intern_term add column end_timestamp integer;
alter table intern_term add column semester_type integer;


alter table intern_major add column level character varying;
update intern_major set level = 'U';

alter table intern_major add column code character varying unique;
alter table intern_major rename column name to description;


alter table intern_major drop constraint intern_major_name_key;
alter table intern_major add constraint intern_major_description_level_key UNIQUE (description, level);

UPDATE intern_term SET available_on_timestamp=1504584000, census_date_timestamp=1531108800, start_timestamp=1530331200, end_timestamp= 1534219200, description='Summer 2 2018', semester_type = 3 WHERE term='20183';
UPDATE intern_term SET available_on_timestamp=1499659200, census_date_timestamp=1527739200, start_timestamp=1525320000, end_timestamp= 1534219200, description='Summer 1 2018', semester_type = 2 WHERE term='20182';
UPDATE intern_term SET available_on_timestamp=1496289600, census_date_timestamp=1517202000, start_timestamp=1513573200, end_timestamp= 1527480000, description='Spring 2018', semester_type = 1 WHERE term='20181';
UPDATE intern_term SET available_on_timestamp=1485752400, census_date_timestamp=1504584000, start_timestamp=1501905600, end_timestamp= 1515992400, description='Fall 2017', semester_type = 4 WHERE term='20174';
UPDATE intern_term SET available_on_timestamp=1472443200, census_date_timestamp=1499659200, start_timestamp=1498881600, end_timestamp= 1502683200, description='Summer 2 2017', semester_type = 3 WHERE term='20173';
UPDATE intern_term SET available_on_timestamp=1467691200, census_date_timestamp=1496289600, start_timestamp=1493870400, end_timestamp= 1502683200, description='Summer 1 2017', semester_type = 2 WHERE term='20172';
UPDATE intern_term SET available_on_timestamp=1464235200, census_date_timestamp=1485752400, start_timestamp=1481605200, end_timestamp= 1496030400, description='Spring 2017', semester_type = 1 WHERE term='20171';
UPDATE intern_term SET available_on_timestamp=1453698000, census_date_timestamp=1472443200, start_timestamp=1470283200, end_timestamp= 1484542800, description='Fall 20016', semester_type = 4 WHERE term='20164';
UPDATE intern_term SET available_on_timestamp=1440734400, census_date_timestamp=1467691200, start_timestamp=1466827200, end_timestamp= 1471147200, description='Summer 2 2016', semester_type = 3 WHERE term='20163';
UPDATE intern_term SET available_on_timestamp=1435809600, census_date_timestamp=1464235200, start_timestamp=1463112000, end_timestamp= 1471147200, description='Summer 1 2016', semester_type = 2 WHERE term='20162';
UPDATE intern_term SET available_on_timestamp=1432785600, census_date_timestamp=1453698000, start_timestamp=1450155600, end_timestamp= 1463976000, description='Spring 2016', semester_type = 1 WHERE term='20161';
UPDATE intern_term SET available_on_timestamp=1422162000, census_date_timestamp=1440734400, start_timestamp=1438660800, end_timestamp= 1452402000, description='Fall 2015', semester_type = 4 WHERE term='20154';
UPDATE intern_term SET available_on_timestamp=1409630400, census_date_timestamp=1435809600, start_timestamp=1435636800, end_timestamp= 1439524800, description='Summer 2 2015', semester_type = 3 WHERE term='20153';
UPDATE intern_term SET available_on_timestamp=1404792000, census_date_timestamp=1432785600, start_timestamp=1431144000, end_timestamp= 1439524800, description='Summer 1 2015', semester_type = 2 WHERE term='20152';
UPDATE intern_term SET available_on_timestamp=1401336000, census_date_timestamp=1422162000, start_timestamp=1418706000, end_timestamp= 1432526400, description='Spring 2015', semester_type = 1 WHERE term='20151';
UPDATE intern_term SET available_on_timestamp=1390798800, census_date_timestamp=1409630400, start_timestamp=1407470400, end_timestamp= 1420952400, description='Fall 2014', semester_type = 4 WHERE term='20144';
UPDATE intern_term SET available_on_timestamp=1378180800, census_date_timestamp=1404792000, start_timestamp=1403928000, end_timestamp= 1408334400, description='Summer 2 2014', semester_type = 3 WHERE term='20143';
UPDATE intern_term SET available_on_timestamp=1372996800, census_date_timestamp=1401336000, start_timestamp=1399694400, end_timestamp= 1408334400, description='Summer 1 2014', semester_type = 2 WHERE term='20142';
UPDATE intern_term SET available_on_timestamp=1369713600, census_date_timestamp=1390798800, start_timestamp=1387256400, end_timestamp= 1401076800, description='Spring 2014', semester_type = 1 WHERE term='20141';
UPDATE intern_term SET available_on_timestamp=1359349200, census_date_timestamp=1378180800, start_timestamp=1376280000, end_timestamp= 1389416400, description='Fall 2013', semester_type = 4 WHERE term='20134';
UPDATE intern_term SET available_on_timestamp=1346731200, census_date_timestamp=1372996800, start_timestamp=1372651200, end_timestamp= 1375416000, description='Summer 2 2013', semester_type = 3 WHERE term='20133';
UPDATE intern_term SET available_on_timestamp=1341460800, census_date_timestamp=1369713600, start_timestamp=1368417600, end_timestamp= 1375416000, description='Summer 1 2013', semester_type = 2 WHERE term='20132';
UPDATE intern_term SET available_on_timestamp=1338523200, census_date_timestamp=1359349200, start_timestamp=1358139600, end_timestamp= 1368158400, description='Spring 2013', semester_type = 1 WHERE term='20131';
UPDATE intern_term SET available_on_timestamp=1327899600, census_date_timestamp=1346731200, start_timestamp=1345521600, end_timestamp= 1355461200, description='Fall 2012', semester_type = 4 WHERE term='20124';
UPDATE intern_term SET available_on_timestamp=1315281600, census_date_timestamp=1341460800, start_timestamp=1341201600, end_timestamp= 1343966400, description='Summer 2 2012', semester_type = 3 WHERE term='20123';
UPDATE intern_term SET available_on_timestamp=1338523200, census_date_timestamp=1338523200, start_timestamp=1341201600, end_timestamp= 1343966400, description='Summer 1 2012', semester_type = 2 WHERE term='20122';
UPDATE intern_term SET available_on_timestamp=1338523200, census_date_timestamp=1327899600, start_timestamp=1326776400, end_timestamp= 1336708800, description='Spring 2012', semester_type = 1 WHERE term='20121';
UPDATE intern_term SET available_on_timestamp=1338523200, census_date_timestamp=1315281600, start_timestamp=1314072000, end_timestamp= 1324011600, description='Fall 2011', semester_type = 4 WHERE term='20114';
