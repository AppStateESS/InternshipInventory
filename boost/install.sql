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
    room_number varchar,
    model varchar,
    hdd varchar,
    proc varchar,
    ram varchar,
    dual_mon varchar default 'no',
    mac varchar,
    printer varchar default NULL,
    staff_member varchar,
    username varchar,
    telephone varchar,
    docking_stand varchar default 'no',
    deep_freeze varchar default 'no',
    purchase_date date,
    vlan varchar,
    reformat varchar default 'no',
    notes varchar,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_default_system (
    id int NOT NULL,
    name varchar NOT NULL,
    model varchar,
    hdd varchar,
    proc varchar,
    ram varchar,
    dual_mon varchar default 'no',
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_document (
    id int NOT NULL,
    system_id int NOT NULL,
    path varchar NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE sysinventory_system ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_system ADD FOREIGN KEY (location_id) REFERENCES sysinventory_location(id);
ALTER TABLE sysinventory_admin ADD FOREIGN KEY (department_id) REFERENCES sysinventory_department(id);
ALTER TABLE sysinventory_document ADD FOREIGN KEY (system_id) REFERENCES sysinventory_system(id);
COMMIT;
