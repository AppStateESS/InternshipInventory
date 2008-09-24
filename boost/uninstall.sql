-- Drop all the sysinventory tables
-- @author Micah Carter <mcarter at tux dot appstate dot edu>
BEGIN;

DROP TABLE sysinventory_admin;
DROP TABLE sysinventory_location;
DROP TABLE sysinventory_office;
DROP TABLE sysinventory_system;
DROP TABLE sysinventory_printer;
DROP TABLE sysinventory_department;
COMMIT;
