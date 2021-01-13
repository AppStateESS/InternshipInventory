BEGIN;

-- Defaults to not hidden.
CREATE TABLE intern_major (
       id INT NOT NULL,
       code VARCHAR UNIQUE DEFAULT NULL,
       description VARCHAR NOT NULL ,
       level VARCHAR NOT NULL DEFAULT 'U',
       hidden SMALLINT NOT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

ALTER TABLE intern_major add constraint intern_major_description_level_key UNIQUE (description, level);

-- Below table is loaded with departments after CREATE TABLE statements.
CREATE TABLE intern_department (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       corequisite SMALLINT NOT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_faculty (
    id              INT NOT NULL, --banner id
    username        VARCHAR NOT NULL,
    first_name      VARCHAR NOT NULL,
    last_name       VARCHAR NOT NULL,
    phone           VARCHAR,
    fax             VARCHAR,
    street_address1 VARCHAR,
    street_address2 VARCHAR,
    city            VARCHAR,
    state           VARCHAR,
    zip             VARCHAR,
    PRIMARY KEY(id)
);

CREATE TABLE intern_faculty_department (
    faculty_id      INT NOT NULL REFERENCES intern_faculty(id),
    department_id   INT NOT NULL REFERENCES intern_department(id),
    PRIMARY KEY (faculty_id, department_id)
);

--reason for admin stop that only they see
--reason for stop that user sees
--Warning, Stop
--name to check sup on flag
--see about having this as Admin Setting
CREATE TABLE intern_special_host(
   id INT NOT NULL,
   admin_message VARCHAR NOT NULL,
   user_message VARCHAR NOT NULL,
   stop_level VARCHAR NOT NULL,
   sup_check VARCHAR,
   email VARCHAR NOT NULL,
   special_notes VARCHAR,
   PRIMARY KEY(id)
);

--overall host name
--flag to show if a new host or one that is awaiting approval 0=not approved 1=approve 2=awaiting
CREATE TABLE intern_host (
    id INT NOT NULL,
    host_name VARCHAR NOT NULL,
    host_condition INT REFERENCES intern_special_host(id),
    host_condition_date VARCHAR,
    host_approve_flag INT NOT NULL DEFAULT 2,
    host_notes VARCHAR,
    PRIMARY KEY(id)
);

--sub name of host that will contain the address information
--flag to show if a new host or one that is awaiting approval 0=not approved 1=approve 2=awaiting
CREATE TABLE intern_sub_host (
       id INT NOT NULL,
       main_host_id INT REFERENCES intern_host(id),
       sub_name VARCHAR NOT NULL,
       address VARCHAR NULL,
       city VARCHAR NULL,
       state VARCHAR,
       zip VARCHAR NULL,
       province VARCHAR,
       country VARCHAR,
       other_name VARCHAR,
       sub_condition INT REFERENCES intern_special_host(id),
       sub_condition_date VARCHAR,
       sub_approve_flag INT NOT NULL DEFAULT 2,
       sub_notes VARCHAR,
       PRIMARY KEY(id)
);

--be able to handle old and new sups
CREATE TABLE intern_supervisor(
    id INT NOT NULL,
    host_id INT REFERENCES intern_host(id),
    supervisor_first_name VARCHAR NULL,
    supervisor_last_name VARCHAR NULL,
    supervisor_title VARCHAR NULL,
    supervisor_phone VARCHAR NULL,
    supervisor_email VARCHAR NULL,
    supervisor_fax VARCHAR NULL,
    supervisor_address VARCHAR NULL,
    supervisor_city VARCHAR NULL,
    supervisor_state VARCHAR NULL,
    supervisor_zip VARCHAR NULL,
    supervisor_province character varying,
    supervisor_country VARCHAR,
    address_same_flag BOOLEAN DEFAULT false,
    PRIMARY KEY(id)
);

CREATE SEQUENCE intern_special_host_seq;
CREATE SEQUENCE intern_supervisor_seq;
CREATE SEQUENCE intern_host_seq;
CREATE SEQUENCE intern_sub_host_seq;

CREATE TABLE intern_state (
       abbr VARCHAR NOT NULL UNIQUE,
       full_name VARCHAR NOT NULL UNIQUE,
       active SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(abbr)
);

INSERT INTO intern_state (abbr, full_name, active) VALUES ('AL', 'Alabama', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AK', 'Alaska', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AS', 'American Samoa', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AZ', 'Arizona', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('AR', 'Arkansas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CA', 'California', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CO', 'Colorado', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('CT', 'Connecticut', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('DE', 'Delaware', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('DC', 'District Of Columbia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('FL', 'Florida', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('GA', 'Georgia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('GU', 'Guam', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('HI', 'Hawaii', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ID', 'Idaho', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IL', 'Illinois', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IN', 'Indiana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('IA', 'Iowa', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('KS', 'Kansas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('KY', 'Kentucky', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('LA', 'Louisiana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ME', 'Maine', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MD', 'Maryland', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MA', 'Massachusetts', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MI', 'Michigan', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MN', 'Minnesota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MS', 'Mississippi', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MO', 'Missouri', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MT', 'Montana', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NE', 'Nebraska', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NV', 'Nevada', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NH', 'New Hampshire', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NJ', 'New Jersey', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NM', 'New Mexico', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NY', 'New York', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('NC', 'North Carolina', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('ND', 'North Dakota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('MP', 'Northern Mariana Islands', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OH', 'Ohio', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OK', 'Oklahoma', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('OR', 'Oregon', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('PA', 'Pennsylvania', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('PR', 'Puerto Rico', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('RI', 'Rhode Island', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('SC', 'South Carolina', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('SD', 'South Dakota', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('TN', 'Tennessee', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('TX', 'Texas', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('UT', 'Utah', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('VT', 'Vermont', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('VI', 'Virgin Islands', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('VA', 'Virginia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WA', 'Washington', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WV', 'West Virginia', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WI', 'Wisconsin', 0);
INSERT INTO intern_state (abbr, full_name, active) VALUES ('WY', 'Wyoming', 0);

CREATE TABLE intern_country (
    id VARCHAR(2) NOT NULL,
    name VARCHAR(64) NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO "intern_country" ("id", "name") VALUES ('AF', 'Afghanistan');
INSERT INTO "intern_country" ("id", "name") VALUES ('AL', 'Albania');
INSERT INTO "intern_country" ("id", "name") VALUES ('DZ', 'Algeria');
INSERT INTO "intern_country" ("id", "name") VALUES ('AD', 'Andorra');
INSERT INTO "intern_country" ("id", "name") VALUES ('AO', 'Angola');
INSERT INTO "intern_country" ("id", "name") VALUES ('AI', 'Anguilla');
INSERT INTO "intern_country" ("id", "name") VALUES ('AQ', 'Antarctica');
INSERT INTO "intern_country" ("id", "name") VALUES ('AG', 'Antigua and Barbuda');
INSERT INTO "intern_country" ("id", "name") VALUES ('AR', 'Argentina');
INSERT INTO "intern_country" ("id", "name") VALUES ('AM', 'Armenia');
INSERT INTO "intern_country" ("id", "name") VALUES ('AW', 'Aruba');
INSERT INTO "intern_country" ("id", "name") VALUES ('AU', 'Australia');
INSERT INTO "intern_country" ("id", "name") VALUES ('AT', 'Austria');
INSERT INTO "intern_country" ("id", "name") VALUES ('AZ', 'Azerbaijan');
INSERT INTO "intern_country" ("id", "name") VALUES ('BS', 'Bahamas');
INSERT INTO "intern_country" ("id", "name") VALUES ('BH', 'Bahrain');
INSERT INTO "intern_country" ("id", "name") VALUES ('BD', 'Bangladesh');
INSERT INTO "intern_country" ("id", "name") VALUES ('BB', 'Barbados');
INSERT INTO "intern_country" ("id", "name") VALUES ('BY', 'Belarus');
INSERT INTO "intern_country" ("id", "name") VALUES ('BE', 'Belgium');
INSERT INTO "intern_country" ("id", "name") VALUES ('BZ', 'Belize');
INSERT INTO "intern_country" ("id", "name") VALUES ('BJ', 'Benin');
INSERT INTO "intern_country" ("id", "name") VALUES ('BM', 'Bermuda');
INSERT INTO "intern_country" ("id", "name") VALUES ('BT', 'Bhutan');
INSERT INTO "intern_country" ("id", "name") VALUES ('BO', 'Bolivia');
INSERT INTO "intern_country" ("id", "name") VALUES ('BA', 'Bosnia and Herzegovina');
INSERT INTO "intern_country" ("id", "name") VALUES ('BW', 'Botswana');
INSERT INTO "intern_country" ("id", "name") VALUES ('BV', 'Bouvet Island');
INSERT INTO "intern_country" ("id", "name") VALUES ('BR', 'Brazil');
INSERT INTO "intern_country" ("id", "name") VALUES ('BQ', 'British Antarctic Territory');
INSERT INTO "intern_country" ("id", "name") VALUES ('IO', 'British Indian Ocean Territory');
INSERT INTO "intern_country" ("id", "name") VALUES ('VG', 'British Virgin Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('BN', 'Brunei');
INSERT INTO "intern_country" ("id", "name") VALUES ('BG', 'Bulgaria');
INSERT INTO "intern_country" ("id", "name") VALUES ('BF', 'Burkina Faso');
INSERT INTO "intern_country" ("id", "name") VALUES ('BI', 'Burundi');
INSERT INTO "intern_country" ("id", "name") VALUES ('KH', 'Cambodia');
INSERT INTO "intern_country" ("id", "name") VALUES ('CM', 'Cameroon');
INSERT INTO "intern_country" ("id", "name") VALUES ('CA', 'Canada');
INSERT INTO "intern_country" ("id", "name") VALUES ('CT', 'Canton and Enderbury Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('CV', 'Cape Verde');
INSERT INTO "intern_country" ("id", "name") VALUES ('KY', 'Cayman Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('CF', 'Central African Republic');
INSERT INTO "intern_country" ("id", "name") VALUES ('TD', 'Chad');
INSERT INTO "intern_country" ("id", "name") VALUES ('CL', 'Chile');
INSERT INTO "intern_country" ("id", "name") VALUES ('CN', 'China');
INSERT INTO "intern_country" ("id", "name") VALUES ('CX', 'Christmas Island');
INSERT INTO "intern_country" ("id", "name") VALUES ('CC', 'Cocos [Keeling] Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('CO', 'Colombia');
INSERT INTO "intern_country" ("id", "name") VALUES ('KM', 'Comoros');
INSERT INTO "intern_country" ("id", "name") VALUES ('CG', 'Congo - Brazzaville');
INSERT INTO "intern_country" ("id", "name") VALUES ('CD', 'Congo - Kinshasa');
INSERT INTO "intern_country" ("id", "name") VALUES ('CK', 'Cook Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('CR', 'Costa Rica');
INSERT INTO "intern_country" ("id", "name") VALUES ('HR', 'Croatia');
INSERT INTO "intern_country" ("id", "name") VALUES ('CU', 'Cuba');
INSERT INTO "intern_country" ("id", "name") VALUES ('CY', 'Cyprus');
INSERT INTO "intern_country" ("id", "name") VALUES ('CZ', 'Czech Republic');
INSERT INTO "intern_country" ("id", "name") VALUES ('CI', 'Côte d’Ivoire');
INSERT INTO "intern_country" ("id", "name") VALUES ('DK', 'Denmark');
INSERT INTO "intern_country" ("id", "name") VALUES ('DJ', 'Djibouti');
INSERT INTO "intern_country" ("id", "name") VALUES ('DM', 'Dominica');
INSERT INTO "intern_country" ("id", "name") VALUES ('DO', 'Dominican Republic');
INSERT INTO "intern_country" ("id", "name") VALUES ('NQ', 'Dronning Maud Land');
INSERT INTO "intern_country" ("id", "name") VALUES ('DD', 'East Germany');
INSERT INTO "intern_country" ("id", "name") VALUES ('EC', 'Ecuador');
INSERT INTO "intern_country" ("id", "name") VALUES ('EG', 'Egypt');
INSERT INTO "intern_country" ("id", "name") VALUES ('SV', 'El Salvador');
INSERT INTO "intern_country" ("id", "name") VALUES ('GQ', 'Equatorial Guinea');
INSERT INTO "intern_country" ("id", "name") VALUES ('ER', 'Eritrea');
INSERT INTO "intern_country" ("id", "name") VALUES ('EE', 'Estonia');
INSERT INTO "intern_country" ("id", "name") VALUES ('ET', 'Ethiopia');
INSERT INTO "intern_country" ("id", "name") VALUES ('FK', 'Falkland Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('FO', 'Faroe Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('FJ', 'Fiji');
INSERT INTO "intern_country" ("id", "name") VALUES ('FI', 'Finland');
INSERT INTO "intern_country" ("id", "name") VALUES ('FR', 'France');
INSERT INTO "intern_country" ("id", "name") VALUES ('GF', 'French Guiana');
INSERT INTO "intern_country" ("id", "name") VALUES ('PF', 'French Polynesia');
INSERT INTO "intern_country" ("id", "name") VALUES ('TF', 'French Southern Territories');
INSERT INTO "intern_country" ("id", "name") VALUES ('FQ', 'French Southern and Antarctic Territories');
INSERT INTO "intern_country" ("id", "name") VALUES ('GA', 'Gabon');
INSERT INTO "intern_country" ("id", "name") VALUES ('GM', 'Gambia');
INSERT INTO "intern_country" ("id", "name") VALUES ('GE', 'Georgia');
INSERT INTO "intern_country" ("id", "name") VALUES ('DE', 'Germany');
INSERT INTO "intern_country" ("id", "name") VALUES ('GH', 'Ghana');
INSERT INTO "intern_country" ("id", "name") VALUES ('GI', 'Gibraltar');
INSERT INTO "intern_country" ("id", "name") VALUES ('GR', 'Greece');
INSERT INTO "intern_country" ("id", "name") VALUES ('GL', 'Greenland');
INSERT INTO "intern_country" ("id", "name") VALUES ('GD', 'Grenada');
INSERT INTO "intern_country" ("id", "name") VALUES ('GP', 'Guadeloupe');
INSERT INTO "intern_country" ("id", "name") VALUES ('GT', 'Guatemala');
INSERT INTO "intern_country" ("id", "name") VALUES ('GG', 'Guernsey');
INSERT INTO "intern_country" ("id", "name") VALUES ('GN', 'Guinea');
INSERT INTO "intern_country" ("id", "name") VALUES ('GW', 'Guinea-Bissau');
INSERT INTO "intern_country" ("id", "name") VALUES ('GY', 'Guyana');
INSERT INTO "intern_country" ("id", "name") VALUES ('HT', 'Haiti');
INSERT INTO "intern_country" ("id", "name") VALUES ('HM', 'Heard Island and McDonald Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('HN', 'Honduras');
INSERT INTO "intern_country" ("id", "name") VALUES ('HK', 'Hong Kong SAR China');
INSERT INTO "intern_country" ("id", "name") VALUES ('HU', 'Hungary');
INSERT INTO "intern_country" ("id", "name") VALUES ('IS', 'Iceland');
INSERT INTO "intern_country" ("id", "name") VALUES ('IN', 'India');
INSERT INTO "intern_country" ("id", "name") VALUES ('ID', 'Indonesia');
INSERT INTO "intern_country" ("id", "name") VALUES ('IR', 'Iran');
INSERT INTO "intern_country" ("id", "name") VALUES ('IQ', 'Iraq');
INSERT INTO "intern_country" ("id", "name") VALUES ('IE', 'Ireland');
INSERT INTO "intern_country" ("id", "name") VALUES ('IM', 'Isle of Man');
INSERT INTO "intern_country" ("id", "name") VALUES ('IL', 'Israel');
INSERT INTO "intern_country" ("id", "name") VALUES ('IT', 'Italy');
INSERT INTO "intern_country" ("id", "name") VALUES ('JM', 'Jamaica');
INSERT INTO "intern_country" ("id", "name") VALUES ('JP', 'Japan');
INSERT INTO "intern_country" ("id", "name") VALUES ('JE', 'Jersey');
INSERT INTO "intern_country" ("id", "name") VALUES ('JT', 'Johnston Island');
INSERT INTO "intern_country" ("id", "name") VALUES ('JO', 'Jordan');
INSERT INTO "intern_country" ("id", "name") VALUES ('KZ', 'Kazakhstan');
INSERT INTO "intern_country" ("id", "name") VALUES ('KE', 'Kenya');
INSERT INTO "intern_country" ("id", "name") VALUES ('KI', 'Kiribati');
INSERT INTO "intern_country" ("id", "name") VALUES ('KW', 'Kuwait');
INSERT INTO "intern_country" ("id", "name") VALUES ('KG', 'Kyrgyzstan');
INSERT INTO "intern_country" ("id", "name") VALUES ('LA', 'Laos');
INSERT INTO "intern_country" ("id", "name") VALUES ('LV', 'Latvia');
INSERT INTO "intern_country" ("id", "name") VALUES ('LB', 'Lebanon');
INSERT INTO "intern_country" ("id", "name") VALUES ('LS', 'Lesotho');
INSERT INTO "intern_country" ("id", "name") VALUES ('LR', 'Liberia');
INSERT INTO "intern_country" ("id", "name") VALUES ('LY', 'Libya');
INSERT INTO "intern_country" ("id", "name") VALUES ('LI', 'Liechtenstein');
INSERT INTO "intern_country" ("id", "name") VALUES ('LT', 'Lithuania');
INSERT INTO "intern_country" ("id", "name") VALUES ('LU', 'Luxembourg');
INSERT INTO "intern_country" ("id", "name") VALUES ('MO', 'Macau SAR China');
INSERT INTO "intern_country" ("id", "name") VALUES ('MK', 'Macedonia');
INSERT INTO "intern_country" ("id", "name") VALUES ('MG', 'Madagascar');
INSERT INTO "intern_country" ("id", "name") VALUES ('MW', 'Malawi');
INSERT INTO "intern_country" ("id", "name") VALUES ('MY', 'Malaysia');
INSERT INTO "intern_country" ("id", "name") VALUES ('MV', 'Maldives');
INSERT INTO "intern_country" ("id", "name") VALUES ('ML', 'Mali');
INSERT INTO "intern_country" ("id", "name") VALUES ('MT', 'Malta');
INSERT INTO "intern_country" ("id", "name") VALUES ('MH', 'Marshall Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('MQ', 'Martinique');
INSERT INTO "intern_country" ("id", "name") VALUES ('MR', 'Mauritania');
INSERT INTO "intern_country" ("id", "name") VALUES ('MU', 'Mauritius');
INSERT INTO "intern_country" ("id", "name") VALUES ('YT', 'Mayotte');
INSERT INTO "intern_country" ("id", "name") VALUES ('FX', 'Metropolitan France');
INSERT INTO "intern_country" ("id", "name") VALUES ('MX', 'Mexico');
INSERT INTO "intern_country" ("id", "name") VALUES ('FM', 'Micronesia');
INSERT INTO "intern_country" ("id", "name") VALUES ('MI', 'Midway Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('MD', 'Moldova');
INSERT INTO "intern_country" ("id", "name") VALUES ('MC', 'Monaco');
INSERT INTO "intern_country" ("id", "name") VALUES ('MN', 'Mongolia');
INSERT INTO "intern_country" ("id", "name") VALUES ('ME', 'Montenegro');
INSERT INTO "intern_country" ("id", "name") VALUES ('MS', 'Montserrat');
INSERT INTO "intern_country" ("id", "name") VALUES ('MA', 'Morocco');
INSERT INTO "intern_country" ("id", "name") VALUES ('MZ', 'Mozambique');
INSERT INTO "intern_country" ("id", "name") VALUES ('MM', 'Myanmar [Burma]');
INSERT INTO "intern_country" ("id", "name") VALUES ('NA', 'Namibia');
INSERT INTO "intern_country" ("id", "name") VALUES ('NR', 'Nauru');
INSERT INTO "intern_country" ("id", "name") VALUES ('NP', 'Nepal');
INSERT INTO "intern_country" ("id", "name") VALUES ('NL', 'Netherlands');
INSERT INTO "intern_country" ("id", "name") VALUES ('AN', 'Netherlands Antilles');
INSERT INTO "intern_country" ("id", "name") VALUES ('NT', 'Neutral Zone');
INSERT INTO "intern_country" ("id", "name") VALUES ('NC', 'New Caledonia');
INSERT INTO "intern_country" ("id", "name") VALUES ('NZ', 'New Zealand');
INSERT INTO "intern_country" ("id", "name") VALUES ('NI', 'Nicaragua');
INSERT INTO "intern_country" ("id", "name") VALUES ('NE', 'Niger');
INSERT INTO "intern_country" ("id", "name") VALUES ('NG', 'Nigeria');
INSERT INTO "intern_country" ("id", "name") VALUES ('NU', 'Niue');
INSERT INTO "intern_country" ("id", "name") VALUES ('NF', 'Norfolk Island');
INSERT INTO "intern_country" ("id", "name") VALUES ('KP', 'North Korea');
INSERT INTO "intern_country" ("id", "name") VALUES ('VD', 'North Vietnam');
INSERT INTO "intern_country" ("id", "name") VALUES ('NO', 'Norway');
INSERT INTO "intern_country" ("id", "name") VALUES ('OM', 'Oman');
INSERT INTO "intern_country" ("id", "name") VALUES ('PC', 'Pacific Islands Trust Territory');
INSERT INTO "intern_country" ("id", "name") VALUES ('PK', 'Pakistan');
INSERT INTO "intern_country" ("id", "name") VALUES ('PW', 'Palau');
INSERT INTO "intern_country" ("id", "name") VALUES ('PS', 'Palestinian Territories');
INSERT INTO "intern_country" ("id", "name") VALUES ('PA', 'Panama');
INSERT INTO "intern_country" ("id", "name") VALUES ('PZ', 'Panama Canal Zone');
INSERT INTO "intern_country" ("id", "name") VALUES ('PG', 'Papua New Guinea');
INSERT INTO "intern_country" ("id", "name") VALUES ('PY', 'Paraguay');
INSERT INTO "intern_country" ("id", "name") VALUES ('YD', 'People''s Democratic Republic of Yemen');
INSERT INTO "intern_country" ("id", "name") VALUES ('PE', 'Peru');
INSERT INTO "intern_country" ("id", "name") VALUES ('PH', 'Philippines');
INSERT INTO "intern_country" ("id", "name") VALUES ('PN', 'Pitcairn Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('PL', 'Poland');
INSERT INTO "intern_country" ("id", "name") VALUES ('PT', 'Portugal');
INSERT INTO "intern_country" ("id", "name") VALUES ('QA', 'Qatar');
INSERT INTO "intern_country" ("id", "name") VALUES ('RO', 'Romania');
INSERT INTO "intern_country" ("id", "name") VALUES ('RU', 'Russia');
INSERT INTO "intern_country" ("id", "name") VALUES ('RW', 'Rwanda');
INSERT INTO "intern_country" ("id", "name") VALUES ('RE', 'Réunion');
INSERT INTO "intern_country" ("id", "name") VALUES ('BL', 'Saint Barthélemy');
INSERT INTO "intern_country" ("id", "name") VALUES ('SH', 'Saint Helena');
INSERT INTO "intern_country" ("id", "name") VALUES ('KN', 'Saint Kitts and Nevis');
INSERT INTO "intern_country" ("id", "name") VALUES ('LC', 'Saint Lucia');
INSERT INTO "intern_country" ("id", "name") VALUES ('MF', 'Saint Martin');
INSERT INTO "intern_country" ("id", "name") VALUES ('PM', 'Saint Pierre and Miquelon');
INSERT INTO "intern_country" ("id", "name") VALUES ('VC', 'Saint Vincent and the Grenadines');
INSERT INTO "intern_country" ("id", "name") VALUES ('WS', 'Samoa');
INSERT INTO "intern_country" ("id", "name") VALUES ('SM', 'San Marino');
INSERT INTO "intern_country" ("id", "name") VALUES ('SA', 'Saudi Arabia');
INSERT INTO "intern_country" ("id", "name") VALUES ('SN', 'Senegal');
INSERT INTO "intern_country" ("id", "name") VALUES ('RS', 'Serbia');
INSERT INTO "intern_country" ("id", "name") VALUES ('CS', 'Serbia and Montenegro');
INSERT INTO "intern_country" ("id", "name") VALUES ('SC', 'Seychelles');
INSERT INTO "intern_country" ("id", "name") VALUES ('SL', 'Sierra Leone');
INSERT INTO "intern_country" ("id", "name") VALUES ('SG', 'Singapore');
INSERT INTO "intern_country" ("id", "name") VALUES ('SK', 'Slovakia');
INSERT INTO "intern_country" ("id", "name") VALUES ('SI', 'Slovenia');
INSERT INTO "intern_country" ("id", "name") VALUES ('SB', 'Solomon Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('SO', 'Somalia');
INSERT INTO "intern_country" ("id", "name") VALUES ('ZA', 'South Africa');
INSERT INTO "intern_country" ("id", "name") VALUES ('GS', 'South Georgia and the South Sandwich Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('KR', 'South Korea');
INSERT INTO "intern_country" ("id", "name") VALUES ('ES', 'Spain');
INSERT INTO "intern_country" ("id", "name") VALUES ('LK', 'Sri Lanka');
INSERT INTO "intern_country" ("id", "name") VALUES ('SD', 'Sudan');
INSERT INTO "intern_country" ("id", "name") VALUES ('SR', 'Suriname');
INSERT INTO "intern_country" ("id", "name") VALUES ('SJ', 'Svalbard and Jan Mayen');
INSERT INTO "intern_country" ("id", "name") VALUES ('SZ', 'Swaziland');
INSERT INTO "intern_country" ("id", "name") VALUES ('SE', 'Sweden');
INSERT INTO "intern_country" ("id", "name") VALUES ('CH', 'Switzerland');
INSERT INTO "intern_country" ("id", "name") VALUES ('SY', 'Syria');
INSERT INTO "intern_country" ("id", "name") VALUES ('ST', 'São Tomé and Príncipe');
INSERT INTO "intern_country" ("id", "name") VALUES ('TW', 'Taiwan');
INSERT INTO "intern_country" ("id", "name") VALUES ('TJ', 'Tajikistan');
INSERT INTO "intern_country" ("id", "name") VALUES ('TZ', 'Tanzania');
INSERT INTO "intern_country" ("id", "name") VALUES ('TH', 'Thailand');
INSERT INTO "intern_country" ("id", "name") VALUES ('TL', 'Timor-Leste');
INSERT INTO "intern_country" ("id", "name") VALUES ('TG', 'Togo');
INSERT INTO "intern_country" ("id", "name") VALUES ('TK', 'Tokelau');
INSERT INTO "intern_country" ("id", "name") VALUES ('TO', 'Tonga');
INSERT INTO "intern_country" ("id", "name") VALUES ('TT', 'Trinidad and Tobago');
INSERT INTO "intern_country" ("id", "name") VALUES ('TN', 'Tunisia');
INSERT INTO "intern_country" ("id", "name") VALUES ('TR', 'Turkey');
INSERT INTO "intern_country" ("id", "name") VALUES ('TM', 'Turkmenistan');
INSERT INTO "intern_country" ("id", "name") VALUES ('TC', 'Turks and Caicos Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('TV', 'Tuvalu');
INSERT INTO "intern_country" ("id", "name") VALUES ('UM', 'U.S. Minor Outlying Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('PU', 'U.S. Miscellaneous Pacific Islands');
INSERT INTO "intern_country" ("id", "name") VALUES ('UG', 'Uganda');
INSERT INTO "intern_country" ("id", "name") VALUES ('UA', 'Ukraine');
INSERT INTO "intern_country" ("id", "name") VALUES ('SU', 'Union of Soviet Socialist Republics');
INSERT INTO "intern_country" ("id", "name") VALUES ('AE', 'United Arab Emirates');
INSERT INTO "intern_country" ("id", "name") VALUES ('GB', 'United Kingdom');
INSERT INTO "intern_country" ("id", "name") VALUES ('US', 'United States of America');
INSERT INTO "intern_country" ("id", "name") VALUES ('UY', 'Uruguay');
INSERT INTO "intern_country" ("id", "name") VALUES ('UZ', 'Uzbekistan');
INSERT INTO "intern_country" ("id", "name") VALUES ('VU', 'Vanuatu');
INSERT INTO "intern_country" ("id", "name") VALUES ('VA', 'Vatican City');
INSERT INTO "intern_country" ("id", "name") VALUES ('VE', 'Venezuela');
INSERT INTO "intern_country" ("id", "name") VALUES ('VN', 'Vietnam');
INSERT INTO "intern_country" ("id", "name") VALUES ('WK', 'Wake Island');
INSERT INTO "intern_country" ("id", "name") VALUES ('WF', 'Wallis and Futuna');
INSERT INTO "intern_country" ("id", "name") VALUES ('EH', 'Western Sahara');
INSERT INTO "intern_country" ("id", "name") VALUES ('YE', 'Yemen');
INSERT INTO "intern_country" ("id", "name") VALUES ('ZM', 'Zambia');
INSERT INTO "intern_country" ("id", "name") VALUES ('ZW', 'Zimbabwe');
INSERT INTO "intern_country" ("id", "name") VALUES ('AX', 'Åland Islands');

CREATE TABLE intern_subject (
    id INT NOT NULL,
    abbreviation VARCHAR(10) NOT NULL,
    description VARCHAR(128) NOT NULL,
    active SMALLINT NOT NULL DEFAULT 1,
    PRIMARY KEY(id)
);

CREATE SEQUENCE intern_subject_seq;

INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ACC','Accounting');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AMU','Applied Music');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ANT','Anthropology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ARB','Arabic');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ART','Art');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AS','Appalachian Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AST','Astronomy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'AT','Athletic Training');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BE','Business Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BIO','Biology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'BUS','Business');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CHE','Chemistry');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CHN','Chinese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CI','Curriculum and Instruction');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CIS','Computer Information Systems');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CJ','Criminal Justice');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'COM','Communication');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CS','Computer Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'CSD','Communication Sciences and Disorders');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'DAN','Dance');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ECO','Economics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'EDL','Educational Leadership');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ENG','English');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ENV','Environmental Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ES','Exercise Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FCS','Family and Consumer Sciences');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FDN','Foundations of Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FER','Fermentation Sciences');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FIN','Finance, Banking and Insurance');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FL','Foreign Languages and Literatures');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'FRE','French');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GER','German');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GHY','Geography');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GLS','Global Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GLY','Geology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GRA','Graphic Arts and Imaging Technology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GS','General Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSA','General Science Astronomy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSB','General Science Biology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSC','General Science Chemistry');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSG','General Science Geology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'GSP','General Science Physics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HCM','Health Care Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HE','Higher Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HED','Health Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HIS','History');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HON','Honors');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HOS','Hospitality Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HP','Health Promotion');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'HPC','Human Development and Psychological Counseling');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'IDS','Interdisciplinary Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'IND','Industrial Design');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'INT','Interior Design');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'ITC','Instructional Technology/Computers');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'JPN','Japanese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LAT','Latin');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LAW','Law');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LIB','Library Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'LSA','Leadership in School Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MAT','Mathematics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MBA','Master of Business Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MGT','Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MKT','Marketing');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MSL','Military Science and Leadership');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'MUS','Music');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'NUR','Nursing');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'NUT','Nutrition');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PA','Public Administration');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PE','Physical Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PHL','Philosophy');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PHY','Physics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PLN','Community and Regional Planning');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'POM','Production/Operations Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'POR','Portuguese');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PS','Political Science');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PSY','Psychology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RE','Reading');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'REL','Religious Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RES','Research');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RM','Recreation Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'RSN','Russian');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SCM','Supply Chain Management');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SD','Sustainable Development');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SNH','Spanish');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SOC','Sociology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SPE','Special Education');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'STT','Statistics');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'SW','Social Work');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'TEC','Technology');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'THR','Theatre');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'UCO','University College');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'US','University Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WGC','Watauga Global Community');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'WS','Women’s Studies');
INSERT INTO intern_subject (id, abbreviation, description) VALUES (nextval('intern_subject_seq'),'PHO','Photography');

CREATE TABLE intern_affiliation_agreement(
  id INT NOT NULL,
  name VARCHAR NOT NULL,
  begin_date INT NOT NULL,
  end_date INT NOT NULL,
  auto_renew INT NOT NULL DEFAULT 0,
  notes VARCHAR,
  terminated SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY(id)
);

CREATE TABLE intern_affiliation_documents(
    id INT NOT NULL,
    affiliation_id INT REFERENCES intern_affiliation_agreement(id),
    name VARCHAR,
    store_name VARCHAR,
    path_name VARCHAR,
    file_type VARCHAR,
    PRIMARY KEY (id)
);

CREATE SEQUENCE intern_affiliation_documents_seq;

CREATE TABLE intern_agreement_department(
  agreement_id INT NOT NULL REFERENCES intern_affiliation_agreement(id),
  department_id INT NOT NULL REFERENCES intern_department(id),
  PRIMARY KEY(agreement_id, department_id)
);

CREATE TABLE intern_agreement_location(
  agreement_id INT NOT NULL REFERENCES intern_affiliation_agreement(id),
  location VARCHAR NOT NULL REFERENCES intern_state(abbr),
  PRIMARY KEY(agreement_id, location)
);

CREATE TABLE intern_agreement_documents(
  agreement_id INT NOT NULL REFERENCES intern_affiliation_agreement(id),
  document_id INT NOT NULL REFERENCES documents(id),
  PRIMARY KEY(agreement_id, document_id)
);

CREATE SEQUENCE intern_affiliation_agreement_seq;

-- Term format YYYY# (e.g. 201110 is Spring 2011, 201130 is Fall 2011)
CREATE TABLE intern_term (
       term VARCHAR NOT NULL,
       description VARCHAR NOT NULL,
       available_on_timestamp INT NOT NULL,
       census_date_timestamp INT NOT NULL,
       start_timestamp INT NOT NULL,
       end_timestamp INT NOT NULL,
       semester_type INT NOT NULL,
       undergrad_overload_hours INT default NULL,
       grad_overload_hours INT default NULL,
       PRIMARY KEY(term)
);

CREATE TABLE intern_student_autocomplete (
    banner_id           INT NOT NULL,
    username            VARCHAR,
    first_name          VARCHAR,
    middle_name         VARCHAR,
    last_name           VARCHAR,
    first_name_lower    VARCHAR,
    middle_name_lower   VARCHAR,
    last_name_lower     VARCHAR,
    first_name_meta     VARCHAR,
    middle_name_meta    VARCHAR,
    last_name_meta      VARCHAR,
    start_term          INT,
    end_term            INT,
    PRIMARY KEY(banner_id)
);

CREATE TABLE intern_student_level(
  code VARCHAR NOT NULL,
  description VARCHAR,
  level VARCHAR NOT NULL,
  PRIMARY KEY(code)
);

CREATE TABLE intern_internship (
       id INT NOT NULL,
       term VARCHAR NOT NULL REFERENCES intern_term(term),
       faculty_id INT REFERENCES intern_faculty(id),
       department_id INT NOT NULL,
       start_date INT DEFAULT 0,
       end_date INT DEFAULT 0,
       internship SMALLINT NOT NULL,
       student_teaching SMALLINT NOT NULL,
       clinical_practica SMALLINT NOT NULL,
       state VARCHAR(128) NOT NULL,
       oied_certified SMALLINT NOT NULL DEFAULT 0,
       banner VARCHAR NOT NULL,
       first_name VARCHAR NOT NULL,
       middle_name VARCHAR,
       last_name VARCHAR NOT NULL,
       preferred_name VARCHAR,
       gpa VARCHAR NULL,
       level VARCHAR NOT NULL REFERENCES intern_student_level,
       phone VARCHAR,
       email VARCHAR NOT NULL,
       major_code VARCHAR,
       major_description VARCHAR,
       campus VARCHAR(128) NOT NULL,
       first_name_meta VARCHAR,
       middle_name_meta VARCHAR,
       last_name_meta VARCHAR,
       preferred_name_meta VARCHAR,
       loc_state VARCHAR NULL,
       loc_zip VARCHAR NULL,
       loc_province VARCHAR(255) NULL,
       loc_country VARCHAR NULL,
       course_subj INT REFERENCES intern_subject(id),
       course_no VARCHAR(20) NULL,
       course_sect VARCHAR(20) NULL,
       course_title VARCHAR(40) NULL,
       credits INT NULL,
       corequisite_number VARCHAR,
       corequisite_section VARCHAR,
       avg_hours_week INT NULL,
       domestic SMALLINT NOT NULL,
       international SMALLINT NOT NULL,
       paid SMALLINT,
       stipend SMALLINT,
       pay_rate VARCHAR NULL,
       multi_part SMALLINT,
       secondary_part SMALLINT,
       experience_type VARCHAR DEFAULT 'internship',
       background_check SMALLINT DEFAULT 0,
       drug_check SMALLINT DEFAULT 0,
       form_token VARCHAR,
       contract_type VARCHAR,
       affiliation_agreement_id INT,
       supervisor_id INT REFERENCES intern_supervisor(id),
       host_id INT REFERENCES intern_host(id),
       host_sub_id INT REFERENCES intern_special_host(id),
       loc_phone VARCHAR,
       remote SMALLINT,
       remote_state VARCHAR,
       PRIMARY KEY(id)
);

CREATE TABLE intern_contract_documents(
    id INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name VARCHAR,
    store_name VARCHAR,
    path_name VARCHAR,
    type VARCHAR,
    file_type VARCHAR,
    PRIMARY KEY (id)
);

CREATE SEQUENCE intern_contract_documents_seq;

CREATE TABLE intern_emergency_contact (
    id          INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name        VARCHAR,
    relation    VARCHAR,
    phone       VARCHAR,
    email       VARCHAR,
    PRIMARY KEY (id)
);

CREATE TABLE intern_document (
    id INT NOT NULL,
    internship_id INT NOT NULL REFERENCES intern_internship(id) ,
    document_fc_id INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE intern_admin (
    id INT NOT NULL,
    username VARCHAR NOT NULL,
    department_id INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE intern_change_history (
    id INT NOT NULL,
    internship_id INT NOT NULL REFERENCES intern_internship(id),
    username VARCHAR(40) NOT NULL,
    timestamp INT NOT NULL,
    from_state VARCHAR(40) NOT NULL,
    to_state VARCHAR(40) NOT NULL,
    note text,
    PRIMARY KEY(id)
);

CREATE INDEX change_history_internshp_idx ON intern_change_history(internship_id);
CREATE SEQUENCE intern_change_history_seq;

CREATE TABLE intern_courses(
      id INT NOT NULL,
      subject_id INT REFERENCES intern_subject(id),
      course_num INT NOT NULL,
      PRIMARY KEY(id)
);

CREATE SEQUENCE intern_courses_seq;

-- Add Departments
INSERT INTO intern_department VALUES  (1, 'Accounting');
INSERT INTO intern_department VALUES  (2, 'Anthropology');
INSERT INTO intern_department VALUES  (3, 'Art');
INSERT INTO intern_department VALUES  (4, 'Biology');
INSERT INTO intern_department VALUES  (5, 'Chemistry');
INSERT INTO intern_department VALUES  (6, 'Communication Sciences & Disorder');
INSERT INTO intern_department VALUES  (7, 'Communication');
INSERT INTO intern_department VALUES  (8, 'Computer Information Systems');
INSERT INTO intern_department VALUES  (9, 'Computer Science');
INSERT INTO intern_department VALUES (10, 'Curriculum & Instruction');
INSERT INTO intern_department VALUES (11, 'Economics');
INSERT INTO intern_department VALUES (12, 'Educational Leadership');
INSERT INTO intern_department VALUES (13, 'English');
INSERT INTO intern_department VALUES (14, 'Family Languages & Literatures');
INSERT INTO intern_department VALUES (15, 'Finance and Banking & Insurance');
INSERT INTO intern_department VALUES (16, 'Foreign Languages & Literatures');
INSERT INTO intern_department VALUES (17, 'Geography & Planning');
INSERT INTO intern_department VALUES (18, 'Geology');
INSERT INTO intern_department VALUES (19, 'Government & Justice Studies');
INSERT INTO intern_department VALUES (20, 'Health, Leisure & Exercise Science');
INSERT INTO intern_department VALUES (21, 'History');
INSERT INTO intern_department VALUES (22, 'Hospitality and Tourism Management');
INSERT INTO intern_department VALUES (23, 'Human Development & Psychological Counseling');
INSERT INTO intern_department VALUES (24, 'Language & Educational Studies');
INSERT INTO intern_department VALUES (25, 'Management');
INSERT INTO intern_department VALUES (26, 'Marketing');
INSERT INTO intern_department VALUES (27, 'Mathematical Sciences');
INSERT INTO intern_department VALUES (28, 'Military Science & Leadership');
INSERT INTO intern_department VALUES (29, 'Music');
INSERT INTO intern_department VALUES (30, 'Nursing');
INSERT INTO intern_department VALUES (31, 'Nutrition & Health Care Management');
INSERT INTO intern_department VALUES (32, 'Philosophy & Religion');
INSERT INTO intern_department VALUES (33, 'Physics & Astronomy');
INSERT INTO intern_department VALUES (34, 'Psychology');
INSERT INTO intern_department VALUES (35, 'Social Work');
INSERT INTO intern_department VALUES (36, 'Sociology');
INSERT INTO intern_department VALUES (37, 'Technology');
INSERT INTO intern_department VALUES (38, 'Theatre and Dance');
INSERT INTO intern_department VALUES (39, 'University College');
-- End departments

-- Add majors
INSERT INTO intern_major (id, description, level) VALUES (1, 'Accounting', 'U');
INSERT INTO intern_major (id, description, level) VALUES (2, 'Actuarial Sciences', 'U');
INSERT INTO intern_major (id, description, level) VALUES (3, 'Anthropology - Applied', 'U');
INSERT INTO intern_major (id, description, level) VALUES (4, 'Anthropology - Archeology', 'G');
INSERT INTO intern_major (id, description, level) VALUES (5, 'Anthropology - Biological', 'G');
INSERT INTO intern_major (id, description, level) VALUES (6, 'Anthropology - General', 'U');
INSERT INTO intern_major (id, description, level) VALUES (7, 'Anthropology - Multidisciplinary', 'G');
INSERT INTO intern_major (id, description, level) VALUES (8, 'Anthropology - Sustainable Development', 'G');
INSERT INTO intern_major (id, description, level) VALUES (9, 'Appalachian Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (10, 'Apparel & Textiles', 'U');
INSERT INTO intern_major (id, description, level) VALUES (11, 'Appropriate Technology', 'U');
INSERT INTO intern_major (id, description, level) VALUES (12, 'Art', 'U');
INSERT INTO intern_major (id, description, level) VALUES (13, 'Art Education K-12', 'G');
INSERT INTO intern_major (id, description, level) VALUES (14, 'Art Managemen', 'G');
INSERT INTO intern_major (id, description, level) VALUES (15, 'Athletic Training', 'U');
INSERT INTO intern_major (id, description, level) VALUES (16, 'Biology', 'U');
INSERT INTO intern_major (id, description, level) VALUES (17, 'Biology - Cell/Molecular', 'G');
INSERT INTO intern_major (id, description, level) VALUES (18, 'Biology - Ecology, Evolution and Environmental', 'G');
INSERT INTO intern_major (id, description, level) VALUES (19, 'Biology - Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (20, 'Building Science', 'U');
INSERT INTO intern_major (id, description, level) VALUES (21, 'Business Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (22, 'Chemistry', 'U');
INSERT INTO intern_major (id, description, level) VALUES (23, 'Chemistry Secondary Education', 'G');
INSERT INTO intern_major (id, description, level) VALUES (24, 'Child Development', 'U');
INSERT INTO intern_major (id, description, level) VALUES (25, 'Child Development - Birth - K', 'G');
INSERT INTO intern_major (id, description, level) VALUES (26, 'Communication Disorders', 'U');
INSERT INTO intern_major (id, description, level) VALUES (27, 'Communication Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (28, 'Communication - Advertising', 'U');
INSERT INTO intern_major (id, description, level) VALUES (29, 'Communication - Electronic Media, Broadcasting', 'U');
INSERT INTO intern_major (id, description, level) VALUES (30, 'Communication - Journalism', 'G');
INSERT INTO intern_major (id, description, level) VALUES (31, 'Communication - Public Relations', 'U');
INSERT INTO intern_major (id, description, level) VALUES (32, 'Community and Regional Planning', 'U');
INSERT INTO intern_major (id, description, level) VALUES (33, 'Computer Information Systems', 'U');
INSERT INTO intern_major (id, description, level) VALUES (34, 'Computer Science', 'U');
INSERT INTO intern_major (id, description, level) VALUES (35, 'Criminal Justice', 'U');
INSERT INTO intern_major (id, description, level) VALUES (36, 'Dance Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (37, 'Economics', 'U');
INSERT INTO intern_major (id, description, level) VALUES (38, 'Elementary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (39, 'English', 'U');
INSERT INTO intern_major (id, description, level) VALUES (40, 'English, Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (41, 'Environmental Science', 'U');
INSERT INTO intern_major (id, description, level) VALUES (42, 'Exercise Science', 'U');
INSERT INTO intern_major (id, description, level) VALUES (43, 'Family and Consumer Sciences - Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (44, 'Finance and Banking', 'U');
INSERT INTO intern_major (id, description, level) VALUES (45, 'French and Francophone Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (46, 'French and Francophone Studies - Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (47, 'Geography', 'U');
INSERT INTO intern_major (id, description, level) VALUES (48, 'Geology', 'U');
INSERT INTO intern_major (id, description, level) VALUES (49, 'Geology, Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (50, 'Global Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (51, 'Graphic Design', 'U');
INSERT INTO intern_major (id, description, level) VALUES (52, 'Graphic Arts and Imaging Technology', 'U');
INSERT INTO intern_major (id, description, level) VALUES (53, 'Health Care Management', 'U');
INSERT INTO intern_major (id, description, level) VALUES (54, 'Health Education - Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (55, 'Health Promotion', 'U');
INSERT INTO intern_major (id, description, level) VALUES (56, 'History', 'U');
INSERT INTO intern_major (id, description, level) VALUES (57, 'History - Social Studies Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (58, 'Hospitality and Tourism Management', 'U');
INSERT INTO intern_major (id, description, level) VALUES (59, 'Industrial Design', 'U');
INSERT INTO intern_major (id, description, level) VALUES (60, 'Interdisciplinary Studies Program - IDS', 'U');
INSERT INTO intern_major (id, description, level) VALUES (61, 'Interior Design', 'U');
INSERT INTO intern_major (id, description, level) VALUES (62, 'International Business', 'U');
INSERT INTO intern_major (id, description, level) VALUES (63, 'Management', 'U');
INSERT INTO intern_major (id, description, level) VALUES (64, 'Marketing', 'U');
INSERT INTO intern_major (id, description, level) VALUES (65, 'Mathematics', 'U');
INSERT INTO intern_major (id, description, level) VALUES (66, 'Mathematics - Secondary Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (67, 'Middle Grades Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (68, 'Music Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (69, 'Music Industry Studies', 'G');
INSERT INTO intern_major (id, description, level) VALUES (70, 'Music Performance', 'G');
INSERT INTO intern_major (id, description, level) VALUES (71, 'Music Therapy', 'U');
INSERT INTO intern_major (id, description, level) VALUES (72, 'Nursing', 'U');
INSERT INTO intern_major (id, description, level) VALUES (73, 'Nutrition and Foods: Dietetics', 'G');
INSERT INTO intern_major (id, description, level) VALUES (74, 'Nutrition and Foods: Food Systems Management', 'G');
INSERT INTO intern_major (id, description, level) VALUES (75, 'Philosophy', 'U');
INSERT INTO intern_major (id, description, level) VALUES (76, 'Physical Education Teacher Education K-12', 'G');
INSERT INTO intern_major (id, description, level) VALUES (77, 'Physics', 'U');
INSERT INTO intern_major (id, description, level) VALUES (78, 'Physics - Secondary Education', 'G');
INSERT INTO intern_major (id, description, level) VALUES (79, 'Political Science', 'U');
INSERT INTO intern_major (id, description, level) VALUES (80, 'Psychology', 'U');
INSERT INTO intern_major (id, description, level) VALUES (81, 'Recreation Management', 'U');
INSERT INTO intern_major (id, description, level) VALUES (82, 'Religious Studies', 'U');
INSERT INTO intern_major (id, description, level) VALUES (83, 'Risk Management & Insurance', 'U');
INSERT INTO intern_major (id, description, level) VALUES (84, 'Social Work', 'U');
INSERT INTO intern_major (id, description, level) VALUES (85, 'Sociology', 'G');
INSERT INTO intern_major (id, description, level) VALUES (86, 'Spanish', 'U');
INSERT INTO intern_major (id, description, level) VALUES (87, 'Spanish Education', 'G');
INSERT INTO intern_major (id, description, level) VALUES (88, 'Statistics', 'U');
INSERT INTO intern_major (id, description, level) VALUES (89, 'Studio Art', 'U');
INSERT INTO intern_major (id, description, level) VALUES (90, 'Sustainable Development', 'U');
INSERT INTO intern_major (id, description, level) VALUES (91, 'Teaching Theatre Arts - K-12', 'G');
INSERT INTO intern_major (id, description, level) VALUES (92, 'Technical Photography', 'U');
INSERT INTO intern_major (id, description, level) VALUES (93, 'Technical Education', 'U');
INSERT INTO intern_major (id, description, level) VALUES (94, 'Theatre Arts', 'U');
INSERT INTO intern_major (id, description, level) VALUES (95, 'Women''s Studies', 'U');
-- End majors

-- Create and update sequences
CREATE SEQUENCE intern_department_seq;
SELECT SETVAL('intern_department_seq', MAX(id)) FROM intern_department;

CREATE SEQUENCE intern_major_seq;
SELECT SETVAL('intern_major_seq', MAX(id)) FROM intern_major;

CREATE SEQUENCE intern_student_seq;
CREATE SEQUENCE intern_term_seq;
CREATE SEQUENCE intern_internship_seq;
CREATE SEQUENCE intern_document_seq;
CREATE SEQUENCE intern_admin_seq;

-- Local table for import and storage of student info
CREATE TABLE intern_local_student_data (
    student_id          VARCHAR NOT NULL,
    user_name           VARCHAR NOT NULL,
    email               VARCHAR NOT NULL,
    first_name          VARCHAR,
    middle_name         VARCHAR,
    last_name           VARCHAR,
    preferred_name      VARCHAR,
    confidential        VARCHAR,
    level               VARCHAR,
    campus              VARCHAR,
    gpa                 double precision,
    credit_hours        INT DEFAULT 0,
    major_code          VARCHAR,
    major_description   VARCHAR,
    grad_date           VARCHAR,
    phone               VARCHAR,
    PRIMARY KEY(student_id)
);

COMMIT;
