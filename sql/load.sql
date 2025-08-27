LOAD DATA LOCAL INFILE 'EmergencyContact.csv'
INTO TABLE emergencyContacts
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(PatientID, Name, Relationship, Phone, Email);

LOAD DATA LOCAL INFILE 'Doctor.csv'
INTO TABLE doctors
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(DoctorID, FirstName, LastName, Email, Phone, Specialty);

LOAD DATA LOCAL INFILE 'Appointment.csv'
INTO TABLE appointments
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(DoctorID, PatientID, Date, Time, Status, Description);

LOAD DATA LOCAL INFILE 'Patient.csv'
INTO TABLE patients 
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(PatientID, FirstName, LastName, Email, DOB, Phone, Address, Gender, InsuranceName, InsuranceID);
