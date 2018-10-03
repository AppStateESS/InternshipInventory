-- Drop all the intern tables
-- @author Micah Carter
BEGIN;

DROP TABLE intern_major CASCADE;
DROP TABLE intern_department CASCADE;
DROP TABLE intern_faculty CASCADE;
DROP TABLE intern_faculty_department CASCADE;
DROP TABLE intern_state CASCADE;
DROP TABLE intern_country CASCADE;
DROP TABLE intern_subject CASCADE;
DROP TABLE intern_agency CASCADE;
DROP TABLE intern_affiliation_agreement CASCADE;
DROP TABLE intern_affiliation_documents CASCADE;
DROP TABLE intern_agreement_department CASCADE;
DROP TABLE intern_agreement_location CASCADE;
DROP TABLE intern_agreement_documents CASCADE;
DROP TABLE intern_term CASCADE;
DROP TABLE intern_student_autocomplete CASCADE;
DROP TABLE intern_student_level CASCADE;
DROP TABLE intern_internship CASCADE;
DROP TABLE intern_contract_documents CASCADE;
DROP TABLE intern_emergency_contact CASCADE;
DROP TABLE intern_document CASCADE;
DROP TABLE intern_admin CASCADE;
DROP TABLE intern_change_history CASCADE;
DROP TABLE intern_courses CASCADE;
DROP TABLE intern_local_student_data CASCADE;

DROP SEQUENCE intern_subject_seq;
DROP SEQUENCE intern_affiliation_documents_seq;
DROP SEQUENCE intern_affiliation_agreement_seq;
DROP SEQUENCE intern_contract_documents_seq;
DROP SEQUENCE intern_change_history_seq;
DROP SEQUENCE intern_courses;
DROP SEQUENCE intern_department_seq;
DROP SEQUENCE intern_major_seq;
DROP SEQUENCE intern_student_seq;
DROP SEQUENCE intern_agency_seq;
DROP SEQUENCE intern_term_seq;
DROP SEQUENCE intern_internship_seq;
DROP SEQUENCE intern_document_seq;
DROP SEQUENCE intern_admin_seq;

COMMIT;
