CREATE TABLE intern_special_host(
    id INT NOT NULL,
    admin_message VARCHAR NOT NULL, --reason for admin stop that only they see
    user_message VARCHAR NOT NULL, --reason for stop that user sees
    stop_level VARCHAR NOT NULL, --warning, full stop
    email VARCHAR NOT NULL, --see about having this as 'Admin Setting'
    PRIMARY KEY(id)
);

--be able to handle old and new sups
CREATE TABLE intern_supervisor(
    id INT NOT NULL,
    supervisor_first_name VARCHAR NULL,
    supervisor_last_name VARCHAR NULL,
    supervisor_title VARCHAR NULL,
    supervisor_phone VARCHAR NULL,
    supervisor_email VARCHAR NULL,
    supervisor_fax VARCHAR NULL,
    supervisor_address VARCHAR NULL,
    supervisor_city VARCHAR NULL,
    supervisor_state VARCHAR NULL,
    supervisor_zip VARCHAR NULL,
    supervisor_province character varying,
    supervisor_country VARCHAR,
    address_same_flag BOOLEAN DEFAULT false, --see if want to allow this now
    PRIMARY KEY(id)
);

--location will be the host HQ and intern location is stored in loc
CREATE TABLE intern_host (
       id INT NOT NULL,
       name VARCHAR NOT NULL,
       address VARCHAR NULL,
       city VARCHAR NULL,
       state VARCHAR,
       zip VARCHAR NULL,
       province VARCHAR,
       country VARCHAR,
       phone VARCHAR,
       other_name VARCHAR,
       special_condition INT REFERENCES intern_special_host(id),
       approve_flag INT NOT NULL DEFAULT 2,--flag to show if a new agency or one that is awaiting approval 0=not approved 1=approve 2=awaiting
       PRIMARY KEY(id)
);

--agency_id keep for old records
ALTER TABLE intern_internship ADD COLUMN supervisor_id INT;
ALTER TABLE intern_internship ADD COLUMN host_id INT;
ALTER TABLE intern_internship ADD CONSTRAINT supervisor_fkey FOREIGN KEY (supervisor_id) REFERENCES intern_supervisor(id);
ALTER TABLE intern_internship ADD CONSTRAINT host_fkey FOREIGN KEY (host_id) REFERENCES intern_host(id);

CREATE SEQUENCE intern_special_host_seq;
CREATE SEQUENCE intern_supervisor_seq;
CREATE SEQUENCE intern_host_seq;
