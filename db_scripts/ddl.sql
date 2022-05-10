USE blukasik;

CREATE TABLE HOUSEHOLD (
  household_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  join_code VARCHAR(100) NOT NULL
);


CREATE TABLE D_USER (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  household_id INT NOT NULL,
  FOREIGN KEY (household_id) REFERENCES HOUSEHOLD(household_id)
);

CREATE TABLE DRUG_TEMPLATE (
  drug_template_id INT NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  manufacturer VARCHAR(255) NOT NULL,
  active_ingredient VARCHAR(255) NOT NULL,
  package VARCHAR(255) NOT NULL,
  leaflet VARCHAR(255) NOT NULL
);

CREATE TABLE DRUG (
  drug_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  drug_template_id INT NOT NULL,
  price DECIMAL NOT NULL,
  initial_quantity DECIMAL(10, 2) NOT NULL,
  expiry_dt DATETIME NOT NULL,
  household_id INT NOT NULL,
  is_discarded BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (drug_template_id) REFERENCES DRUG_TEMPLATE(drug_template_id),
  FOREIGN KEY (household_id) REFERENCES HOUSEHOLD(household_id)
);

CREATE TABLE DOSAGE (
  dosage_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  drug_id INT NOT NULL,
  quantity DECIMAL NOT NULL,
  dosage_date DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES D_USER(user_id),
  FOREIGN KEY (drug_id) REFERENCES DRUG(drug_id)
);