ALTER TABLE intern_internship ADD COLUMN banner VARCHAR;
ALTER TABLE intern_internship ADD COLUMN first_name VARCHAR;
ALTER TABLE intern_internship ADD COLUMN middle_name VARCHAR;
ALTER TABLE intern_internship ADD COLUMN last_name VARCHAR;
ALTER TABLE intern_internship ADD COLUMN gpa VARCHAR;
ALTER TABLE intern_internship ADD COLUMN level VARCHAR;
ALTER TABLE intern_internship ADD COLUMN phone VARCHAR;
ALTER TABLE intern_internship ADD COLUMN email VARCHAR;
ALTER TABLE intern_internship ADD COLUMN ugrad_major INT;
ALTER TABLE intern_internship ADD COLUMN grad_prog INT;
ALTER TABLE intern_internship ADD COLUMN student_address varchar(256);
ALTER TABLE intern_internship ADD COLUMN student_city varchar(256);
ALTER TABLE intern_internship ADD COLUMN student_state varchar(2);
ALTER TABLE intern_internship ADD COLUMN student_zip varchar(5);
ALTER TABLE intern_internship ADD COLUMN emergency_contact_name varchar(256);
ALTER TABLE intern_internship ADD COLUMN emergency_contact_relation varchar(256);
ALTER TABLE intern_internship ADD COLUMN emergency_contact_phone varchar(20);
ALTER TABLE intern_internship ADD COLUMN campus character varying(128);


ALTER TABLE intern_internship ADD CONSTRAINT ugrad_major_fkey FOREIGN KEY (ugrad_major) REFERENCES intern_major(id);
ALTER TABLE intern_internship ADD CONSTRAINT grad_prog_fkey FOREIGN KEY (grad_prog) REFERENCES intern_grad_prog(id);


UPDATE intern_internship AS i SET banner = s.banner, first_name = s.first_name, middle_name = s.middle_name, last_name = s.last_name, gpa = s.gpa, level = s.level, phone = s.phone, email = s.email, ugrad_major = s.ugrad_major, grad_prog = s.grad_prog, student_address = s.address, student_city = s.city, student_state = s.state, student_zip = s.zip, emergency_contact_name = s.emergency_contact_name, emergency_contact_relation = s.emergency_contact_relation, emergency_contact_phone = s.emergency_contact_phone, campus = s.campus FROM intern_student AS s WHERE i.student_id = s.id;

ALTER TABLE intern_internship ALTER COLUMN banner SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN first_name SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN last_name SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN level SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN phone SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN email SET NOT NULL;
ALTER TABLE intern_internship ALTER COLUMN campus SET NOT NULL;

ALTER TABLE intern_internship DROP COLUMN student_id;
DROP TABLE intern_student;

ALTER TABLE intern_internship DROP COLUMN service_learn;
ALTER TABLE intern_internship DROP COLUMN independent_study;
ALTER TABLE intern_internship DROP COLUMN research_assist;
ALTER TABLE intern_internship DROP COLUMN special_topics;
ALTER TABLE intern_internship DROP COLUMN other_type;