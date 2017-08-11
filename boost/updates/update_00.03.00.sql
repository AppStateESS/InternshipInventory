ALTER TABLE intern_internship ADD contract_type varchar;
ALTER TABLE intern_internship ADD affiliation_agreement_id integer;

CREATE TABLE intern_contract_documents(
    id INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name VARCHAR, store_name VARCHAR,
    path_name VARCHAR,
    type VARCHAR,
    file_type VARCHAR,
    primary key (id)
);

CREATE SEQUENCE intern_contract_documents_seq;

INSERT INTO intern_contract_documents (id, internship_id, name, store_name, path_name, type, file_type) VALUES (nextval('intern_contract_documents_seq'));
