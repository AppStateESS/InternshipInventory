ALTER TABLE intern_agency ADD city  VARCHAR NOT NULL DEFAULT ' ';
ALTER TABLE intern_agency ADD state VARCHAR NOT NULL DEFAULT 'XX';
ALTER TABLE intern_agency ADD zip   INT     NOT NULL DEFAULT 00000;
ALTER TABLE intern_agency ADD supervisor_city  VARCHAR NOT NULL DEFAULT ' ';
ALTER TABLE intern_agency ADD supervisor_state VARCHAR NOT NULL DEFAULT 'XX';
ALTER TABLE intern_agency ADD supervisor_zip   INT     NOT NULL DEFAULT 00000;