BEGIN;
-- If your editor's syntax highlighting is going crazy with the
-- apostrophes IGNORE it. I ran this update file and it works fine.

-- Fixing some odd characters in graduate program and major names.
-- They were messing up the PDF reports.
update intern_grad_prog set name = replace(name, '–', '-');
update intern_grad_prog set name = replace(name, '’', '\'');
update intern_major set name = replace(name, '–', '-');
update intern_major set name = replace(name, '’', '\'');
COMMIT;