

PRAGMA foreign_keys=off;

BEGIN TRANSACTION;

ALTER TABLE employees RENAME TO _employees_old;

CREATE TABLE employees
( employee_id INTEGER PRIMARY KEY AUTOINCREMENT,
  last_name VARCHAR NOT NULL,
  first_name VARCHAR,
  department_id INTEGER,
  CONSTRAINT fk_departments
    FOREIGN KEY (department_id)
    REFERENCES departments(department_id)
    ON DELETE CASCADE
);

INSERT INTO employees SELECT * FROM _employees_old;

COMMIT;

PRAGMA foreign_keys=on;
