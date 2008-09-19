-- Set up tables for Sysinventory:
-- @author Micah Carter <mcarter at tux dot appstate dot edu>

CREATE TABLE sysinventory_location (
    id int NOT NULL,
    description varchar NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_department (
    id int NOT NULL,
    description varchar NOT NULL,
    location_id int,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_office (
    id int NOT NULL,
    description varchar NOT NULL,
    department_id int,
    location_id int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_system (
    id int NOT NULL,
    location_id int NOT NULL,
    department_id int,
    office_id int,
    model_num varchar,
    hard_drive varchar,
    proc varchar,
    ram varchar,
    dual_monitor boolean,
    mac macaddr,
    printer_id int,
    staff_member varchar,
    username varchar,
    telephone varchar,
    room_num varchar,
    docking_stand boolean default FALSE,
    deep_freeze boolean default FALSE,
    purchase_date date,
    rotation_year int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_printer (
    id int NOT NULL;
    location_id int NOT NULL,
    department_id int,
    office_id int,
    model_num varchar,
    staff_member varchar,
    username varchar,
    telephone varchar,
    room_num varchar,
    purchase_date date,
    PRIMARY KEY (id)
);

CREATE TABLE sysinventory_employee (
    id int NOT NULL;
    location_id int,
    department_id int,
    office_id int,
    fname varchar NOT NULL,
    lname varchar NOT NULL,
    username varchar NOT NULL,
    PRIMARY KEY(id)
);
