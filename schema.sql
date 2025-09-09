-- Create database
CREATE DATABASE smarthealth;
USE smarthealth;

-- -------------------------------
--  Roles and Users (Authentication)
-- -------------------------------
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Insert roles
INSERT INTO roles (role_name) VALUES 
('Admin'), ('Receptionist'), ('LabTech'), ('Cashier');

-- Insert an initial admin (password is 'admin123', hashed later in PHP)
INSERT INTO users (username, password, role_id) VALUES 
('admin', '$2y$10$abcdefghijklmnopqrstuvABCDEFGHIJKLMNOpqrstuv', 1);

-- -------------------------------
--  Patients
-- -------------------------------
CREATE TABLE Patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female') NOT NULL,
    dob DATE NOT NULL,
    contact VARCHAR(20),
    address TEXT
);

-- -------------------------------
--  Doctors
-- -------------------------------
CREATE TABLE Doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    contact VARCHAR(20),
    schedule TEXT
);

-- -------------------------------
--  Appointments
-- -------------------------------
CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES Doctors(doctor_id) ON DELETE CASCADE
);

-- -------------------------------
--  Lab Results
-- -------------------------------
CREATE TABLE LabResults (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    test_type VARCHAR(100) NOT NULL,
    result TEXT NOT NULL,
    result_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id) ON DELETE CASCADE
);

-- -------------------------------
--  Billing
-- -------------------------------
CREATE TABLE Billing (
    bill_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    appointment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Unpaid','Paid') DEFAULT 'Unpaid',
    bill_date DATE NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES Patients(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES Appointments(appointment_id) ON DELETE CASCADE
);
