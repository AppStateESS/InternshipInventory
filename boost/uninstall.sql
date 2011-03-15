-- Drop all the intern tables
-- @author Micah Carter <mcarter at tux dot appstate dot edu>
BEGIN;

DROP TABLE intern_admin;
DROP TABLE intern_system CASCADE;
DROP TABLE intern_department CASCADE;
DROP TABLE intern_location CASCADE;
DROP TABLE intern_default_system CASCADE;
DROP TABLE intern_document CASCADE;

COMMIT;
