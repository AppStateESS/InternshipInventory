-- Drop all the sysinventory tables
-- @author Micah Carter <mcarter at tux dot appstate dot edu>
BEGIN;

DROP TABLE sysinventory_admin;
DROP TABLE sysinventory_system CASCADE;
DROP TABLE sysinventory_department CASCADE;
DROP TABLE sysinventory_location CASCADE;
COMMIT;
