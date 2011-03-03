-- No need for path column, using File Cabinet.
ALTER TABLE sysinventory_document DROP COLUMN path;
ALTER TABLE sysinventory_document ADD COLUMN document_fc_id INT NOT NULL;
ALTER TABLE sysinventory_document ADD FOREIGN KEY (document_fc_id) REFERENCES documents(id);
