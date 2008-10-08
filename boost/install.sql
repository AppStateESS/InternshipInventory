-- Set up tables for Sysinventory:
-- @author Micah Carter <mcarter at tux dot appstate dot edu>

BEGIN;
CREATE TABLE sysinventory_admin (
    id int NOT NULL,
    username varchar NOT NULL,
    department_id int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_location (
    id int NOT NULL,
    description varchar NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_department (
    id int NOT NULL,
    description varchar NOT NULL,
    last_update int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_system (
    id int NOT NULL,
    location_id int,
    department_id int NOT NULL,
    room_number int,
    model varchar,
    hdd varchar,
    proc varchar,
    ram varchar,
    dual_mon boolean default FALSE,
    mac varchar,
    printer varchar default NULL,
    staff_member varchar,
    username varchar,
    telephone varchar,
    docking_stand boolean default FALSE,
    deep_freeze boolean default FALSE,
    purchase_date date,
    vlan varchar,
    reformat boolean default FALSE,
    notes varchar,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_printer (
    id int NOT NULL,
    location_id int,
    department_id int NOT NULL,
    office_id int,
    model_num varchar,
    staff_member varchar,
    username varchar,
    telephone varchar,
    room_num varchar,
    purchase_date date,
    PRIMARY KEY (id)
);

ALTER TABLE sysinventory_system ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_printer ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_admin ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_location ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_office ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
COMMIT;
