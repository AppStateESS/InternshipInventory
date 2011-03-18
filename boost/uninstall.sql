-- Drop all the intern tables
-- @author Micah Carter <mcarter at tux dot appstate dot edu>
BEGIN;

DROP TABLE intern_document CASCADE;
DROP TABLE intern_internship CASCADE;
DROP TABLE intern_faculty_supervisor CASCADE;
DROP TABLE intern_agency CASCADE;
DROP TABLE intern_department CASCADE;
DROP TABLE intern_student CASCADE;
DROP TABLE intern_admin CASCADE;

COMMIT;
