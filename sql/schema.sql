CREATE DATABASE IF NOT EXISTS organ_blood_donation;
USE organ_blood_donation;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','donor','patient','hospital') NOT NULL,
  location_city VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNIQUE NOT NULL,
  designation VARCHAR(100),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE donors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNIQUE NOT NULL,
  donor_type ENUM('blood','organ','both') NOT NULL,
  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  organs_available JSON NULL,
  medical_clearance TINYINT(1) DEFAULT 0,
  status ENUM('active','inactive') DEFAULT 'active',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNIQUE NOT NULL,
  blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  age INT,
  diagnosis VARCHAR(255),
  urgency_level ENUM('low','medium','high','critical') DEFAULT 'medium',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE hospitals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNIQUE NOT NULL,
  hospital_name VARCHAR(150) NOT NULL,
  address VARCHAR(255),
  verified TINYINT(1) DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE blood_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  hospital_id INT,
  required_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  units_required INT NOT NULL,
  urgency_level ENUM('low','medium','high','critical') DEFAULT 'medium',
  status ENUM('pending','matched','approved','rejected','completed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
);

CREATE TABLE organ_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  hospital_id INT,
  organ_type VARCHAR(50) NOT NULL,
  urgency_level ENUM('low','medium','high','critical') DEFAULT 'high',
  status ENUM('pending','matched','approved','rejected','completed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
);

CREATE TABLE donations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT NOT NULL,
  patient_id INT,
  blood_request_id INT,
  organ_request_id INT,
  donation_type ENUM('blood','organ') NOT NULL,
  donation_date DATE,
  compatibility_score INT,
  location_score INT,
  waiting_time_score INT,
  total_priority_score INT,
  status ENUM('proposed','approved','completed','cancelled') DEFAULT 'proposed',
  FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
  FOREIGN KEY (blood_request_id) REFERENCES blood_requests(id) ON DELETE SET NULL,
  FOREIGN KEY (organ_request_id) REFERENCES organ_requests(id) ON DELETE SET NULL
);
