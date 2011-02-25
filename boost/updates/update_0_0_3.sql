-- Add table for documents.
-- `path` holds the full path name of the document.

START TRANSACTION;

CREATE TABLE sysinventory_document (
    id int NOT NULL,
    system_id int NOT NULL,
    path varchar NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE sysinventory_document ADD FOREIGN KEY (system_id) REFERENCES sysinventory_system(id);

COMMIT;
