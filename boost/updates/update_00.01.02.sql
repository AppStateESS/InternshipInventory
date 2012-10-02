CREATE TABLE intern_emergency_contact (
    id          INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name        character varying,
    relation    character varying,
    phone       character varying,
    PRIMARY KEY (id)
);

create sequence intern_emergency_contact_seq;

INSERT INTO intern_emergency_contact (id, internship_id, name, relation, phone) SELECT nextval('intern_emergency_contact_seq'), id, emergency_contact_name, emergency_contact_relation, emergency_contact_phone FROM intern_internship WHERE emergency_contact_name IS NOT NULL OR emergency_contact_relation IS NOT NULL OR emergency_contact_phone IS NOT NULL;

alter table intern_internship drop column emergency_contact_name;
alter table intern_internship drop column emergency_contact_relation;
alter table intern_internship drop column emergency_contact_phone;