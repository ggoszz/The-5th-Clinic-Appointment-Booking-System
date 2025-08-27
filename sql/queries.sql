use clinicDB;
-- CRUD queries

-- Patients
-- create
insert into patients (
	PatientID, FirstName, LastName, Email, DOB, Phone, Address, Gender, InsuranceName, InsuranceID
) values (
	106, 'Emily', 'Park', 'emily.park@email.com', '1995-03-23', '214-999-0000', '606 Maple St', 'Female',
    'BlueCross', 'BC12345'
);

-- read
select * from patients;

select* from patients
where FirstName = 'Emily' and LastName = 'Park';

-- update
update patients
set phone = '214-123-9999'
where PatientID = 106;

-- delete
delete from patients
where PatientID = 106;

-- Doctors
insert into doctors (
	DoctorID, FirstName, LastName, Email, Phone, Specialty
) values (
	6, 'Noah', 'Carroll', 'noah.carroll@clinic.com', '972-111-2222', 'Oncology'
);

select * from doctors;

update doctors
set Phone = '972-222-3333', Specialty = 'International Medicine'
where DoctorID = 6;

delete from doctors
where DoctorID = 6;

-- Appointments
INSERT INTO appointments 
(DoctorID, PatientID, Date, Time, Status, Description) 
VALUES 
(1, 106, '2025-08-10', '10:00:00', 'Scheduled', 'New patient checkup');


select * from appointments;

update appointments
set Status = 'Completed'
where DoctorID = 1 and PatientID = 106 and date = '2025-08-10' and Time = '10:00:00';

delete from appointments
where DoctorID = 1 and PatientID = 106 and Date = '2025-08-10' and Time = '10:00:00';

-- emergencyContacts
insert into emergencyContacts (
	PatientID, Name, Relationship, Phone, Email
) values (
	106, 'Lily Hernandez', 'Sister', '214-333-4444', 'lily.hernandez@email.com'
);

select * from emergencyContacts;

update emergencyContacts
set Phone = '214-999-8888'
where PatientID = 106 and Name = 'Lily Hernandez';

delete from emergencyContacts
where PatientID = 106 and Name = 'Lily Hernandez';

