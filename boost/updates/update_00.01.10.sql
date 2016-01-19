ALTER TABLE intern_internship ADD COLUMN form_token varchar;
UPDATE intern_internship SET form_token = uuid_in(md5(random()::text || clock_timestamp()::text)::cstring);
