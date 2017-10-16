BEGIN;

-- Defaults to not hidden.
CREATE TABLE intern_major (
       id INT NOT NULL,
       code varchar NOT NULL UNIQUE,
       description VARCHAR NOT NULL,
       level VARCHAR NOT NULL,
       hidden SMALLINT NOT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

alter table intern_major add constraint intern_major_description_level_key UNIQUE (description, level);

-- TODO: remove this table
CREATE TABLE intern_grad_prog (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       hidden SMALLINT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

-- Below table is loaded with departments after CREATE TABLE statements.
CREATE TABLE intern_department (
       id INT NOT NULL,
       name VARCHAR NOT NULL UNIQUE,
       college_name character varying,
       hidden SMALLINT NULL DEFAULT 0,
       corequisite SMALLINT NOT NULL DEFAULT 0,
       PRIMARY KEY(id)
);

create table intern_faculty (
    id              integer NOT NULL,
    username        character varying NOT NULL,
    first_name      character varying NOT NULL,
    last_name       character varying NOT NULL,
    phone           character varying,
    fax             character varying,
    street_address1 character varying,
    street_address2 character varying,
    city            character varying,
    state           character varying,
    zip             character varying,
    PRIMARY KEY(id)
);

create table intern_faculty_department (
    faculty_id      integer NOT NULL REFERENCES intern_faculty(id),
    department_id   integer NOT NULL REFERENCES intern_department(id),
    PRIMARY KEY (faculty_id, department_id)
);

CREATE TABLE intern_state (
       abbr varchar NOT NULL UNIQUE,
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
    abbreviation character varying(10) NOT NULL,
    description character varying(128) NOT NULL,
    active smallint not null default 1,
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

CREATE TABLE intern_agency (
       id INT NOT NULL,
       name VARCHAR NOT NULL,
       address VARCHAR NULL,
       city VARCHAR NULL,
       state VARCHAR,
       zip VARCHAR NULL,
       province character varying,
       country VARCHAR,
       phone VARCHAR,
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


CREATE TABLE intern_affiliation_agreement(
  id int NOT NULL,
  name varchar NOT NULL,
  begin_date int NOT NULL,
  end_date int NOT NULL,
  auto_renew int NOT NULL DEFAULT 0,
  notes varchar,
  terminated smallint NOT NULL DEFAULT 0,
  PRIMARY KEY(id)
);

CREATE TABLE intern_agreement_department(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  department_id int NOT NULL REFERENCES intern_department(id),
  PRIMARY KEY(agreement_id, department_id)
);

CREATE TABLE intern_agreement_location(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  location varchar NOT NULL REFERENCES intern_state(abbr),
  PRIMARY KEY(agreement_id, location)
);

CREATE TABLE intern_agreement_documents(
  agreement_id int NOT NULL REFERENCES intern_affiliation_agreement(id),
  document_id int NOT NULL REFERENCES documents(id),
  PRIMARY KEY(agreement_id, document_id)
);

CREATE SEQUENCE intern_affiliation_agreement_seq;

-- Term format YYYY# (e.g. 20111 is Spring 2011, 20113 is Fall 2011)
CREATE TABLE intern_term (
       term character varying NOT NULL,
       description character varying NOT NULL,
       available_on_timestamp integer NOT NULL,
       census_date_timestamp integer NOT NULL,
       start_timestamp integer NOT NULL,
       end_timestamp integer NOT NULL,
       semester_type integer NOT NULL,
       PRIMARY KEY (term)
);

create table intern_student_autocomplete (
    banner_id           integer NOT NULL,
    username            character varying,
    first_name          character varying,
    middle_name         character varying,
    last_name           character varying,
    first_name_lower    character varying,
    middle_name_lower   character varying,
    last_name_lower     character varying,
    first_name_meta     character varying,
    middle_name_meta    character varying,
    last_name_meta      character varying,
    start_term          integer,
    end_term            integer,
    PRIMARY KEY(banner_id)
);

CREATE TABLE intern_internship (
       id INT NOT NULL,
       term character varying NOT NULL REFERENCES intern_term(term),

       agency_id INT NOT NULL REFERENCES intern_agency(id),
       faculty_id integer REFERENCES intern_faculty(id),
       department_id INT NOT NULL,

       start_date INT default 0,
       end_date INT default 0,

       internship SMALLINT NOT NULL,
       student_teaching SMALLINT NOT NULL,
       clinical_practica SMALLINT NOT NULL,

       state varchar(128) NOT NULL,
       oied_certified smallint not null default 0,

       banner VARCHAR NOT NULL,
       first_name VARCHAR NOT NULL,
       middle_name VARCHAR,
       last_name VARCHAR NOT NULL,
       gpa VARCHAR NULL,
       level VARCHAR NOT NULL,
       phone VARCHAR,
       email VARCHAR NOT NULL,
       major_code character varying,
       major_description character varying,
       student_address varchar(256),
       student_address2 character varying,
       student_city varchar(256),
       student_state varchar(2),
       student_zip VARCHAR NULL,
       campus character varying(128) NOT NULL,
       first_name_meta character varying,
       middle_name_meta character varying,
       last_name_meta character varying,
       birth_date integer NOT NULL,

       loc_address varchar NULL,
       loc_city varchar NULL,
       loc_state varchar NULL,
       loc_zip varchar NULL,
       loc_province varchar(255) NULL,
       loc_country varchar NULL,
       course_subj integer REFERENCES intern_subject(id),
       course_no varchar(20) null,
       course_sect varchar(20) null,
       course_title varchar(40) null,
       credits INT NULL,
       corequisite_number character varying,
       corequisite_section character varying,
       avg_hours_week INT NULL,
       domestic SMALLINT NOT NULL,
       international SMALLINT NOT NULL,
       paid SMALLINT,
       stipend SMALLINT,
       pay_rate VARCHAR NULL,
       co_op smallint default 0,
       multi_part SMALLINT,
       secondary_part SMALLINT,
       experience_type varchar default 'internship',
       background_check SMALLINT DEFAULT 0,
       drug_check SMALLINT DEFAULT 0,
       PRIMARY KEY(id)
);

CREATE TABLE intern_emergency_contact (
    id          INT NOT NULL,
    internship_id INT REFERENCES intern_internship(id),
    name        character varying,
    relation    character varying,
    phone       character varying,
    email       character varying,
    PRIMARY KEY (id)
);

CREATE TABLE intern_document (
    id int NOT NULL,
    internship_id int NOT NULL REFERENCES intern_internship(id) ,
    document_fc_id int NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE intern_admin (
    id INT NOT NULL,
    username VARCHAR NOT NULL,
    department_id INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE intern_change_history (
    id INT NOT NULL,
    internship_id INT NOT NULL REFERENCES intern_internship(id),
    username character varying(40) NOT NULL,
    timestamp int NOT NULL,
    from_state character varying(40) NOT NULL,
    to_state character varying(40) NOT NULL,
    note text,
    PRIMARY KEY(id)
);

CREATE INDEX change_history_internshp_idx ON intern_change_history(internship_id);
CREATE SEQUENCE intern_change_history_seq;

CREATE TABLE intern_courses(
      id INT NOT NULL,
      subject_id integer REFERENCES intern_subject(id),
      course_num INT NOT NULL,
      primary key (id)
);

CREATE SEQUENCE intern_courses_seq;

-- Add Departments
INSERT INTO intern_department VALUES  (1, 'Accounting', 'College of Business');
INSERT INTO intern_department VALUES  (2, 'Anthropology', 'Arts and Sciences');
INSERT INTO intern_department VALUES  (3, 'Art', 'Fine and Applied Arts');
INSERT INTO intern_department VALUES  (4, 'Biology', 'Arts and Sciences');
INSERT INTO intern_department VALUES  (5, 'Chemistry', 'Arts and Sciences');
INSERT INTO intern_department VALUES  (6, 'Communication Sciences & Disorder');
INSERT INTO intern_department VALUES  (7, 'Communication', 'Fine & Applied Arts');
INSERT INTO intern_department VALUES  (8, 'Computer Information Systems', 'College of Business');
INSERT INTO intern_department VALUES  (9, 'Computer Science', 'Arts and Sciences');
INSERT INTO intern_department VALUES (10, 'Curriculum & Instruction', 'College of Education');
INSERT INTO intern_department VALUES (11, 'Economics', 'College of Business');
INSERT INTO intern_department VALUES (12, 'Educational Leadership', 'College of Education');
INSERT INTO intern_department VALUES (13, 'English', 'Arts and Sciences');
INSERT INTO intern_department VALUES (14, 'Family Languages & Literatures', 'Arts and Sciences');
INSERT INTO intern_department VALUES (15, 'Finance and Banking & Insurance', 'College of Business');
INSERT INTO intern_department VALUES (16, 'Foreign Languages & Literatures', 'Arts and Sciences');
INSERT INTO intern_department VALUES (17, 'Geography & Planning', 'Arts and Sciences');
INSERT INTO intern_department VALUES (18, 'Geology', 'Arts and Sciences');
INSERT INTO intern_department VALUES (19, 'Government & Justice Studies', 'Arts and Sciences');
INSERT INTO intern_department VALUES (20, 'Health, Leisure & Exercise Science', 'College of Health Sciences');
INSERT INTO intern_department VALUES (21, 'History', 'Arts and Sciences');
INSERT INTO intern_department VALUES (22, 'Hospitality and Tourism Management', 'College of Business');
INSERT INTO intern_department VALUES (23, 'Human Development & Psychological Counseling', 'College of Education');
INSERT INTO intern_department VALUES (24, 'Language & Educational Studies', 'College of Education');
INSERT INTO intern_department VALUES (25, 'Management', 'College of Business');
INSERT INTO intern_department VALUES (26, 'Marketing', 'College of Business');
INSERT INTO intern_department VALUES (27, 'Mathematical Sciences', 'Arts and Sciences');
INSERT INTO intern_department VALUES (28, 'Military Science & Leadership', 'Arts and Sciences');
INSERT INTO intern_department VALUES (29, 'Music', 'School of Music');
INSERT INTO intern_department VALUES (30, 'Nursing', 'College of Health Sciences');
INSERT INTO intern_department VALUES (31, 'Nutrition & Health Care Management', 'College of Health Sciences');
INSERT INTO intern_department VALUES (32, 'Philosophy & Religion', 'Arts and Sciences');
INSERT INTO intern_department VALUES (33, 'Physics & Astronomy', 'Arts and Sciences');
INSERT INTO intern_department VALUES (34, 'Psychology', 'Arts and Sciences');
INSERT INTO intern_department VALUES (35, 'Social Work', 'College of Health Sciences');
INSERT INTO intern_department VALUES (36, 'Sociology', 'Arts and Sciences');
INSERT INTO intern_department VALUES (37, 'Technology', 'Fine and Applied Arts');
INSERT INTO intern_department VALUES (38, 'Theatre and Dance', 'Fine and Applied Arts');
INSERT INTO intern_department VALUES (39, 'University College', 'University College');
-- End departments

-- Add undergraduate majors
INSERT INTO intern_major VALUES (1, 1, 'Accounting', 'U');
INSERT INTO intern_major VALUES (2, 2, 'Actuarial Sciences', 'U');
INSERT INTO intern_major VALUES (3, 3, 'Anthropology - Applied', 'U');
INSERT INTO intern_major VALUES (4, 4, 'Anthropology - Archeology', 'U');
INSERT INTO intern_major VALUES (5, 5, 'Anthropology - Biological', 'U');
INSERT INTO intern_major VALUES (6, 6, 'Anthropology - General', 'U');
INSERT INTO intern_major VALUES (7, 7, 'Anthropology - Multidisciplinary', 'U');
INSERT INTO intern_major VALUES (8, 8, 'Anthropology - Sustainable Development', 'U');
INSERT INTO intern_major VALUES (9, 9, 'Appalachian Studies', 'U');
INSERT INTO intern_major VALUES (10, 10, 'Apparel & Textiles', 'U');
INSERT INTO intern_major VALUES (11, 11, 'Appropriate Technology', 'U');
INSERT INTO intern_major VALUES (12, 12, 'Art', 'U');
INSERT INTO intern_major VALUES (13, 13, 'Art Education K-12', 'U');
INSERT INTO intern_major VALUES (14, 14, 'Art Managemen', 'U');
INSERT INTO intern_major VALUES (15, 15, 'Athletic Training', 'U');
INSERT INTO intern_major VALUES (16, 16, 'Biology', 'U');
INSERT INTO intern_major VALUES (17, 17, 'Biology - Cell/Molecular', 'U');
INSERT INTO intern_major VALUES (18, 18, 'Biology - Ecology, Evolution and Environmental', 'U');
INSERT INTO intern_major VALUES (19, 19, 'Biology - Secondary Education', 'U');
INSERT INTO intern_major VALUES (20, 20, 'Building Science', 'U');
INSERT INTO intern_major VALUES (21, 21, 'Business Education', 'U');
INSERT INTO intern_major VALUES (22, 22, 'Chemistry', 'U');
INSERT INTO intern_major VALUES (23, 23, 'Chemistry Secondary Education', 'U');
INSERT INTO intern_major VALUES (24, 24, 'Child Development', 'U');
INSERT INTO intern_major VALUES (25, 25, 'Child Development - Birth - K', 'U');
INSERT INTO intern_major VALUES (26, 26, 'Communication Disorders', 'U');
INSERT INTO intern_major VALUES (27, 27, 'Communication Studies', 'U');
INSERT INTO intern_major VALUES (28, 28, 'Communication - Advertising', 'U');
INSERT INTO intern_major VALUES (29, 29, 'Communication - Electronic Media, Broadcasting', 'U');
INSERT INTO intern_major VALUES (30, 30, 'Communication - Journalism', 'U');
INSERT INTO intern_major VALUES (31, 31, 'Communication - Public Relations', 'U');
INSERT INTO intern_major VALUES (32, 32, 'Community and Regional Planning', 'U');
INSERT INTO intern_major VALUES (33, 33, 'Computer Information Systems', 'U');
INSERT INTO intern_major VALUES (34, 34, 'Computer Science', 'U');
INSERT INTO intern_major VALUES (35, 35, 'Criminal Justice', 'U');
INSERT INTO intern_major VALUES (36, 36, 'Dance Studies', 'U');
INSERT INTO intern_major VALUES (37, 37, 'Economics', 'U');
INSERT INTO intern_major VALUES (38, 38, 'Elementary Education', 'U');
INSERT INTO intern_major VALUES (39, 39, 'English', 'U');
INSERT INTO intern_major VALUES (40, 40, 'English, Secondary Education', 'U');
INSERT INTO intern_major VALUES (41, 41, 'Environmental Science', 'U');
INSERT INTO intern_major VALUES (42, 42, 'Exercise Science', 'U');
INSERT INTO intern_major VALUES (43, 43, 'Family and Consumer Sciences - Secondary Education', 'U');
INSERT INTO intern_major VALUES (44, 44, 'Finance and Banking', 'U');
INSERT INTO intern_major VALUES (45, 45, 'French and Francophone Studies', 'U');
INSERT INTO intern_major VALUES (46, 46, 'French and Francophone Studies - Education', 'U');
INSERT INTO intern_major VALUES (47, 47, 'Geography', 'U');
INSERT INTO intern_major VALUES (48, 48, 'Geology', 'U');
INSERT INTO intern_major VALUES (49, 49, 'Geology, Secondary Education', 'U');
INSERT INTO intern_major VALUES (50, 50, 'Global Studies', 'U');
INSERT INTO intern_major VALUES (51, 51, 'Graphic Design', 'U');
INSERT INTO intern_major VALUES (52, 52, 'Graphic Arts and Imaging Technology', 'U');
INSERT INTO intern_major VALUES (53, 53, 'Health Care Management', 'U');
INSERT INTO intern_major VALUES (54, 54, 'Health Education - Secondary Education', 'U');
INSERT INTO intern_major VALUES (55, 55, 'Health Promotion', 'U');
INSERT INTO intern_major VALUES (56, 56, 'History', 'U');
INSERT INTO intern_major VALUES (57, 57, 'History - Social Studies Education', 'U');
INSERT INTO intern_major VALUES (58, 58, 'Hospitality and Tourism Management', 'U');
INSERT INTO intern_major VALUES (59, 59, 'Industrial Design', 'U');
INSERT INTO intern_major VALUES (60, 60, 'Interdisciplinary Studies Program - IDS', 'U');
INSERT INTO intern_major VALUES (61, 61, 'Interior Design', 'U');
INSERT INTO intern_major VALUES (62, 62, 'International Business', 'U');
INSERT INTO intern_major VALUES (63, 63, 'Management', 'U');
INSERT INTO intern_major VALUES (64, 64, 'Marketing', 'U');
INSERT INTO intern_major VALUES (65, 65, 'Mathematics', 'U');
INSERT INTO intern_major VALUES (66, 66, 'Mathematics - Secondary Education', 'U');
INSERT INTO intern_major VALUES (67, 67, 'Middle Grades Education', 'U');
INSERT INTO intern_major VALUES (68, 68, 'Music Education', 'U');
INSERT INTO intern_major VALUES (69, 69, 'Music Industry Studies', 'U');
INSERT INTO intern_major VALUES (70, 70, 'Music Performance', 'U');
INSERT INTO intern_major VALUES (71, 71, 'Music Therapy', 'U');
INSERT INTO intern_major VALUES (72, 72, 'Nursing', 'U');
INSERT INTO intern_major VALUES (73, 73, 'Nutrition and Foods: Dietetics', 'U');
INSERT INTO intern_major VALUES (74, 74, 'Nutrition and Foods: Food Systems Management', 'U');
INSERT INTO intern_major VALUES (75, 75, 'Philosophy', 'U');
INSERT INTO intern_major VALUES (76, 76, 'Physical Education Teacher Education K-12', 'U');
INSERT INTO intern_major VALUES (77, 77, 'Physics', 'U');
INSERT INTO intern_major VALUES (78, 78, 'Physics - Secondary Education', 'U');
INSERT INTO intern_major VALUES (79, 79, 'Political Science', 'U');
INSERT INTO intern_major VALUES (80, 80, 'Psychology', 'U');
INSERT INTO intern_major VALUES (81, 81, 'Recreation Management', 'U');
INSERT INTO intern_major VALUES (82, 82, 'Religious Studies', 'U');
INSERT INTO intern_major VALUES (83, 83, 'Risk Management & Insurance', 'U');
INSERT INTO intern_major VALUES (84, 84, 'Social Work', 'U');
INSERT INTO intern_major VALUES (85, 85, 'Sociology', 'U');
INSERT INTO intern_major VALUES (86, 86, 'Spanish', 'U');
INSERT INTO intern_major VALUES (87, 87, 'Spanish Education', 'U');
INSERT INTO intern_major VALUES (88, 88, 'Statistics', 'U');
INSERT INTO intern_major VALUES (89, 89, 'Studio Art', 'U');
INSERT INTO intern_major VALUES (90, 90, 'Sustainable Development', 'U');
INSERT INTO intern_major VALUES (91, 91, 'Teaching Theatre Arts - K-12', 'U');
INSERT INTO intern_major VALUES (92, 92, 'Technical Photography', 'U');
INSERT INTO intern_major VALUES (93, 93, 'Technical Education', 'U');
INSERT INTO intern_major VALUES (94, 94, 'Theatre Arts', 'U');
INSERT INTO intern_major VALUES (95, 95, 'Women''s Studies', 'U');
-- End majors

-- Add Graduate Programs
INSERT INTO intern_grad_prog VALUES (1, 'Accounting');
INSERT INTO intern_grad_prog VALUES (2, 'Appalachian Studies');
INSERT INTO intern_grad_prog VALUES (3, 'Biology');
INSERT INTO intern_grad_prog VALUES (4, 'Business Administration');
INSERT INTO intern_grad_prog VALUES (5, 'Child Development - Birth through K');
INSERT INTO intern_grad_prog VALUES (6, 'Clinical Mental Health Counseling');
INSERT INTO intern_grad_prog VALUES (7, 'Addictions Counseling');
INSERT INTO intern_grad_prog VALUES (8, 'Expressive Arts Therapy');
INSERT INTO intern_grad_prog VALUES (9, 'College Student Development');
INSERT INTO intern_grad_prog VALUES (10, 'Computer Science');
INSERT INTO intern_grad_prog VALUES (11, 'Criminal Justice');
INSERT INTO intern_grad_prog VALUES (12, 'Curriculum Specialist');
INSERT INTO intern_grad_prog VALUES (13, 'Educational Leadership');
INSERT INTO intern_grad_prog VALUES (14, 'Educational Media');
INSERT INTO intern_grad_prog VALUES (15, 'Education Media - Web-based Distance Learning');
INSERT INTO intern_grad_prog VALUES (16, 'Media Literacy');
INSERT INTO intern_grad_prog VALUES (17, 'Elementary Education');
INSERT INTO intern_grad_prog VALUES (18, 'English');
INSERT INTO intern_grad_prog VALUES (19, 'English Education');
INSERT INTO intern_grad_prog VALUES (20, 'Rhetoric and Composition');
INSERT INTO intern_grad_prog VALUES (21, 'Exercise Science');
INSERT INTO intern_grad_prog VALUES (22, 'Family & Consumer Sciences - Education');
INSERT INTO intern_grad_prog VALUES (23, 'Geography');
INSERT INTO intern_grad_prog VALUES (24, 'GIS Science');
INSERT INTO intern_grad_prog VALUES (25, 'Planning');
INSERT INTO intern_grad_prog VALUES (26, 'Gerontology');
INSERT INTO intern_grad_prog VALUES (27, 'Higher Education');
INSERT INTO intern_grad_prog VALUES (28, 'History');
INSERT INTO intern_grad_prog VALUES (29, 'History Education');
INSERT INTO intern_grad_prog VALUES (30, 'Public History');
INSERT INTO intern_grad_prog VALUES (31, 'Library Science');
INSERT INTO intern_grad_prog VALUES (32, 'Marriage & Family Therapy');
INSERT INTO intern_grad_prog VALUES (33, 'Mathematics');
INSERT INTO intern_grad_prog VALUES (34, 'Mathematics Education');
INSERT INTO intern_grad_prog VALUES (35, 'Middle Grades Education');
INSERT INTO intern_grad_prog VALUES (36, 'Music Performance');
INSERT INTO intern_grad_prog VALUES (37, 'Music Education');
INSERT INTO intern_grad_prog VALUES (38, 'Music Therapy');
INSERT INTO intern_grad_prog VALUES (39, 'Nutrition');
INSERT INTO intern_grad_prog VALUES (40, 'Engineering Physics');
INSERT INTO intern_grad_prog VALUES (41, 'Political Science');
INSERT INTO intern_grad_prog VALUES (42, 'Psychology, General Experimental');
INSERT INTO intern_grad_prog VALUES (43, 'Clinical Health Psychology');
INSERT INTO intern_grad_prog VALUES (44, 'Industrial-Organizational Psychology and HRM');
INSERT INTO intern_grad_prog VALUES (45, 'School Psychology');
INSERT INTO intern_grad_prog VALUES (46, 'Public Administration');
INSERT INTO intern_grad_prog VALUES (47, 'Reading Education');
INSERT INTO intern_grad_prog VALUES (48, 'Romance Languages');
INSERT INTO intern_grad_prog VALUES (49, 'French');
INSERT INTO intern_grad_prog VALUES (50, 'Spanish');
INSERT INTO intern_grad_prog VALUES (51, 'School Administration');
INSERT INTO intern_grad_prog VALUES (52, 'Educational Administration');
INSERT INTO intern_grad_prog VALUES (53, 'School Counseling');
INSERT INTO intern_grad_prog VALUES (54, 'Social Work');
INSERT INTO intern_grad_prog VALUES (55, 'Sociology');
INSERT INTO intern_grad_prog VALUES (56, 'Special Education');
INSERT INTO intern_grad_prog VALUES (57, 'Speech Language Pathology');
INSERT INTO intern_grad_prog VALUES (58, 'Technology');
INSERT INTO intern_grad_prog VALUES (59, 'Women''s Studies');
-- End Grad. Programs

-- Create and update sequences
CREATE SEQUENCE intern_department_seq;
SELECT SETVAL('intern_department_seq', MAX(id)) FROM intern_department;

CREATE SEQUENCE intern_major_seq;
SELECT SETVAL('intern_major_seq', MAX(id)) FROM intern_major;

CREATE SEQUENCE intern_grad_prog_seq;
SELECT SETVAL('intern_grad_prog_seq', MAX(id)) FROM intern_grad_prog;

CREATE SEQUENCE intern_student_seq;
CREATE SEQUENCE intern_agency_seq;
CREATE SEQUENCE intern_term_seq;
CREATE SEQUENCE intern_internship_seq;
CREATE SEQUENCE intern_document_seq;
CREATE SEQUENCE intern_admin_seq;

-- Local table for import and storage of student info
CREATE TABLE intern_local_student_data (
    student_id          character varying not null,
    user_name           character varying not null,
    email               character varying not null,

    first_name          character varying,
    middle_name         character varying,
    last_name           character varying,
    preferred_name      character varying,
    confidential        character varying,

    birth_date          character varying,
    gender              character varying,

    level               character varying,
    campus              character varying,
    gpa                 double precision,
    credit_hours        integer default 0,
    major_code          character varying,
    major_description   character varying,
    grad_date           character varying,

    phone               character varying,
    address             character varying,
    address2            character varying,
    city                character varying,
    state               character varying,
    zip                 character varying,

    primary key(student_id)
);

COMMIT;
