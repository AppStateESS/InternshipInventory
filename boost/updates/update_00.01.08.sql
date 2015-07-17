CREATE TABLE intern_affiliation_agreement(
  id int NOT NULL,
  name varchar NOT NULL,
  begin_date int NOT NULL,
  end_date int NOT NULL,
  auto_renew int NOT NULL,
  notes varchar,
  PRIMARY KEY(id)
);

CREATE TABLE intern_agreement_department(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  department_id int NOT NULL REFERENCES intern_department(id),
  PRIMARY KEY(agreement_id, department_id)
);

CREATE TABLE intern_agreement_location(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  location varchar NOT NULL REFERENCES intern_state(abbr),
  PRIMARY KEY(agreement_id, location)
);

CREATE TABLE intern_agreement_documents(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  document_id int NOT NULL REFERENCES documents(id),
  PRIMARY KEY(agreement_id, document_id)
);

CREATE SEQUENCE intern_affiliation_agreement_seq;
