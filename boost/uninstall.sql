-- Drop all the intern tables
-- @author Micah Carter <mcarter at tux dot appstate dot edu>
BEGIN;

DROP TABLE intern_document CASCADE;
DROP TABLE intern_grad_prog CASCADE;
DROP TABLE intern_major CASCADE;
DROP TABLE intern_term CASCADE;
DROP TABLE intern_internship CASCADE;
DROP TABLE intern_faculty_supervisor CASCADE;
DROP TABLE intern_agency CASCADE;
DROP TABLE intern_department CASCADE;
DROP TABLE intern_student CASCADE;
DROP TABLE intern_admin CASCADE;
DROP TABLE intern_state CASCADE;

DROP SEQUENCE intern_admin_seq;
DROP SEQUENCE intern_student_seq;
DROP SEQUENCE intern_department_seq;
DROP SEQUENCE intern_agency_seq;
DROP SEQUENCE intern_faculty_supervisor_seq;
DROP SEQUENCE intern_internship_seq;
DROP SEQUENCE intern_document_seq;
DROP SEQUENCE intern_term_seq;
DROP SEQUENCE intern_major_seq;
DROP SEQUENCE intern_grad_prog_seq;
DROP SEQUENCE intern_state_seq;

COMMIT;
