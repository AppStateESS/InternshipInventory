ALTER TABLE intern_internship ADD contract_type varchar;
ALTER TABLE intern_internship ADD affiliation_agreement_id integer;

CREATE TABLE intern_contract_documents(
    id INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name VARCHAR,
    store_name VARCHAR,
    path_name VARCHAR,
    type VARCHAR,
    file_type VARCHAR,
    primary key (id)
);

CREATE SEQUENCE intern_contract_documents_seq;

INSERT INTO intern_contract_documents (id, internship_id, name, store_name, path_name, type, file_type)
(SELECT nextval('intern_contract_documents_seq'), internship_id, title, internship_id || file_name, mod_settings.small_char || 'otherDocuments/' || internship_id || documents.file_name, 'other', file_type
FROM mod_settings, intern_document JOIN documents ON intern_document.document_fc_id = documents.id
WHERE mod_settings.module = 'filecabinet' AND mod_settings.setting_name = 'base_doc_directory');

CREATE TABLE intern_affiliation_documents(
    id INT NOT NULL,
    affiliation_id INT REFERENCES intern_affiliation_agreement(id),
    name VARCHAR,
    store_name VARCHAR,
    path_name VARCHAR,
    file_type VARCHAR,
    primary key (id)
);

CREATE SEQUENCE intern_affiliation_documents_seq;

INSERT INTO intern_affiliation_documents (id, affiliation_id, name, store_name, path_name, file_type)
(SELECT nextval('intern_affiliation_documents_seq'), agreement_id, title, agreement_id || file_name, mod_settings.small_char || 'affiliation/' || agreement_id || documents.file_name, file_type
FROM mod_settings, intern_agreement_documents JOIN documents ON intern_agreement_documents.document_id = documents.id
WHERE mod_settings.module = 'filecabinet' AND mod_settings.setting_name = 'base_doc_directory');