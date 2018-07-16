ALTER TABLE intern_term ADD COLUMN undergrad_overload_hours int default null;
ALTER TABLE intern_term ADD COLUMN grad_overload_hours int default null;

UPDATE intern_term SET undergrad_overload_hours=18 WHERE ((undergrad_overload_hours IS NULL) AND (semester_type=1 OR semester_type=4));
UPDATE intern_term SET undergrad_overload_hours=7 WHERE ((undergrad_overload_hours IS NULL) AND (semester_type=2 OR semester_type=3));
UPDATE intern_term SET grad_overload_hours=12 WHERE ((grad_overload_hours IS NULL) AND (semester_type=1 OR semester_type=4));
UPDATE intern_term SET grad_overload_hours=6 WHERE ((grad_overload_hours IS NULL) AND (semester_type=2 OR semester_type=3));

