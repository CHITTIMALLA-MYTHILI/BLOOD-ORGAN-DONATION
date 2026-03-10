USE organ_blood_donation;

INSERT INTO users (full_name, email, phone, password_hash, role, location_city) VALUES
('System Admin', 'admin@obd.local', '9999999999', '$2y$10$dummyhashadmin', 'admin', 'Hyderabad'),
('Arun Donor', 'donor1@obd.local', '9000000001', '$2y$10$dummyhashdonor', 'donor', 'Hyderabad'),
('Priya Patient', 'patient1@obd.local', '9000000002', '$2y$10$dummyhashpatient', 'patient', 'Hyderabad'),
('City Care Hospital', 'hospital1@obd.local', '9000000003', '$2y$10$dummyhashhospital', 'hospital', 'Hyderabad');

INSERT INTO admins (user_id, designation) VALUES (1, 'Super Admin');
INSERT INTO donors (user_id, donor_type, blood_group, organs_available, medical_clearance) VALUES
(2, 'both', 'O+', JSON_ARRAY('kidney','liver'), 1);
INSERT INTO patients (user_id, blood_group, age, diagnosis, urgency_level) VALUES
(3, 'A+', 32, 'Acute blood loss', 'critical');
INSERT INTO hospitals (user_id, hospital_name, address, verified) VALUES
(4, 'City Care Hospital', 'Road 1, Hyderabad', 1);

INSERT INTO blood_requests (patient_id, hospital_id, required_group, units_required, urgency_level, status) VALUES
(1, 1, 'A+', 2, 'critical', 'pending');

INSERT INTO organ_requests (patient_id, hospital_id, organ_type, urgency_level, status) VALUES
(1, 1, 'kidney', 'high', 'pending');
