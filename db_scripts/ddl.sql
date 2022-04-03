USE blukasik;

CREATE TABLE HOUSEHOLD (
  household_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  join_code VARCHAR(100) NOT NULL
);


CREATE TABLE D_USER (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(30) NOT NULL,
  first_name VARCHAR(30) NOT NULL,
  last_name VARCHAR(30) NOT NULL,
  household_id INT NOT NULL,
  FOREIGN KEY (household_id) REFERENCES HOUSEHOLD(household_id)
);

CREATE TABLE QUANTITY_TYPE (
  q_type_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(20) NOT NULL
);

CREATE TABLE DRUG (
  drug_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  price DECIMAL NOT NULL,
  expiry_dt DATETIME NOT NULL,
  quantity_type INT NOT NULL,
  FOREIGN KEY (quantity_type) REFERENCES QUANTITY_TYPE(q_type_id)
);

CREATE TABLE DOSAGE (
  dosage_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  quantity DECIMAL NOT NULL,
  dosage_date DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES D_USER(user_id)
);