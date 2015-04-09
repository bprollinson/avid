CREATE TABLE members (
  id INTEGER NOT NULL, 
  username VARCHAR(32) NOT NULL, 
  password VARCHAR(64) NOT NULL, 
  country CHAR(6) NOT NULL, 
  province CHAR(7) DEFAULT '' NOT NULL, 
  city VARCHAR(25) DEFAULT '' NOT NULL, 
  postal_code CHAR(7) NOT NULL, 
  date_of_birth CHAR(10) NOT NULL, 
  limits VARCHAR(25) NOT NULL, 
  height CHAR(5) NOT NULL, 
  weight VARCHAR(7) NOT NULL, 
  body_type VARCHAR(16) NOT NULL, 
  ethnicity VARCHAR(16) NOT NULL, 
  email VARCHAR(50) NOT NULL, 
  PRIMARY KEY("id")
); CREATE UNIQUE INDEX UniqueUsername ON members (username);