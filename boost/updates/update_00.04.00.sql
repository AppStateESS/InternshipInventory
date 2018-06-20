UPDATE intern_term SET undergrad_overload_hours=18 WHERE ((undergrad_overload_hours IS NULL) AND (semester_type=1 OR semester_type=4));
UPDATE intern_term SET undergrad_overload_hours=7 WHERE ((undergrad_overload_hours IS NULL) AND (semester_type=2 OR semester_type=3));
UPDATE intern_term SET grad_overload_hours=12 WHERE ((grad_overload_hours IS NULL) AND (semester_type=1 OR semester_type=4));
UPDATE intern_term SET grad_overload_hours=6 WHERE ((grad_overload_hours IS NULL) AND (semester_type=2 OR semester_type=3));

ALTER TABLE intern_term ALTER COLUMN undergrad_overload_hours SET NOT NULL;
ALTER TABLE intern_term ALTER COLUMN grad_overload_hours SET NOT NULL;
