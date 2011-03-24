BEGIN;

CREATE TABLE intern_term (
       id INT NOT NULL,
       term INT NOT NULL UNIQUE,
       PRIMARY KEY (id);
);

-- Add some terms!
INSERT INTO intern_term VALUES (1, 20111);
INSERT INTO intern_term VALUES (2, 20112);
INSERT INTO intern_term VALUES (3, 20113);
INSERT INTO intern_term VALUES (4, 20121);
INSERT INTO intern_term VALUES (5, 20122);
INSERT INTO intern_term VALUES (6, 20123);
INSERT INTO intern_term VALUES (7, 20131);
INSERT INTO intern_term VALUES (8, 20132);
INSERT INTO intern_term VALUES (9, 20133);
-- End terms

CREATE SEQUENCE intern_term_seq;
SELECT SETVAL('intern_term_seq', MAX(id)) FROM intern_term;

COMMIT;