CREATE DATABASE DentalClinic;

USE DentalClinic;

CREATE TABLE Patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_name VARCHAR(64),
    patient_email VARCHAR(64),
    patient_password VARCHAR(64),
    patient_phoneno INT,
    patient_gender VARCHAR(16),
    patient_nationality VARCHAR(16),
    patient_dob DATE
);

CREATE TABLE Dentists (
    dentist_id INT PRIMARY KEY AUTO_INCREMENT,
    dentist_name VARCHAR(64),
    dentist_email VARCHAR(64),
    dentist_password VARCHAR(64),
    dentist_description TEXT,
    dentist_image VARCHAR(64)
); 

CREATE TABLE Services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    service_type VARCHAR(64),
    service_description TEXT,
    service_image VARCHAR(64)
);

CREATE TABLE Schedule (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    dentist_id INT,
    available_date DATE,
    available_time TIME,
    availability_status BOOLEAN,
    FOREIGN KEY (dentist_id) REFERENCES Dentists(dentist_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE 
);

CREATE TABLE Appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    patient_id INT,
    dentist_id INT,
    schedule_id INT,
    service_id INT,
    remarks TEXT, 
    cancelled BOOLEAN,
    rescheduled BOOLEAN,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (dentist_id) REFERENCES Dentists(dentist_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES Schedule(schedule_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE appointments ADD COLUMN attendance TINYINT(1) DEFAULT NULL;

