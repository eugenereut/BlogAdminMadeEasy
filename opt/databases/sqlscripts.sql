CREATE TABLE bookcase (
  idbc INTEGER PRIMARY KEY AUTOINCREMENT,
  namebookcase TEXT,
  aboutbookcase TEXT
);

CREATE TABLE shelves
(
  idsh INTEGER PRIMARY KEY AUTOINCREMENT,
  nameshelve TEXT,
  idbc INTEGER,
  CONSTRAINT fk_bookcase
    FOREIGN KEY (idbc)
    REFERENCES bookcase(idbc)
    ON DELETE CASCADE
);

CREATE TABLE postcase
(
  idpt INTEGER PRIMARY KEY AUTOINCREMENT,
  datepost date,
  filepath TEXT,
  postname TEXT
);

CREATE TABLE postonshelve
(
  postonshelve_id INTEGER PRIMARY KEY AUTOINCREMENT,
  datepost date,
  idpt INTEGER,
  idsh INTEGER,
  CONSTRAINT fk_shelves
    FOREIGN KEY (idsh)
    REFERENCES shelves(idsh)
    ON DELETE CASCADE
);

CREATE TABLE postinbookcase
(
  postinbookcase_id INTEGER PRIMARY KEY AUTOINCREMENT,
  datepost date,
  idpt INTEGER,
  idbc INTEGER,
  CONSTRAINT fk_bookcase
    FOREIGN KEY (idbc)
    REFERENCES bookcase(idbc)
    ON DELETE CASCADE
);
