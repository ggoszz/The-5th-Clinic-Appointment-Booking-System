# The 5th Clinic Appointment Booking System

## db_connect.php

- Handles MySQL connection


## Appointments

### 🔹 `detePatient.php` : 
To delete Patients from the Patient Table with the given Patient ID <br><br>

Ex: GET backend/Patients/deletPatient.php?PatientID=101


### 🔹 `getAllPatients.php`
To get all patients in the Patients table <br><br>

Ex: GET backend/Patients/getAllPatients.php

### 🔹 `getPatient.php`
To get an specific patient the Patients table by their name. <br><br>
Ex: GET backend/Patients/getPatient.php?firstName=John&lastName=Doe

### 🔹 `insertPatient.php`
To insert a new Patient into the Patients Table. <br><br>

Ex: POST backend/Patients/insertPatient.php

Body Ex: <br>
```json
{
   "FirstName": "John",
   "LastName": "Doe",
   "Email": "john.doe@example.com",
   "DOB": "1990-01-01",
   "Phone": "123-456-7890",
   "Address": "123 Main St",
   "Gender": "Male",
   "InsuranceName": "Health Insurance",
   "InsuranceID": "INS123"
}
```

### 🔹 `patientLogin.php`
To check a Patient row exist with the provided name, DOB and ID.

Ex: GET backend/Patients/patientLogin.php?firstName=John&lastName=Doe&PatientID=123&DOB=1990-01-01


### 🔹 `updatePatient.php`
To update a Patient in the Patient Table.

Ex: POST backend/Patients/insertPatient.php

Body Ex: <br>
```json
{
   "PatientID": 101,
   "FirstName": "John",
   "LastName": "Doe",
   "Email": "john.doe@example.com",
   "DOB": "1990-01-01",
   "Phone": "123-456-7890",
   "Address": "123 Main St",
   "Gender": "Male",
   "InsuranceName": "Health Insurance",
   "InsuranceID": "INS123"
}
```

## EC
### 🔹 `deleteEC.php`
To delete a emergency contact from the emergency contacts table with the given Patient ID and Name <br><br>

Ex: GET backend/EC/deleteEC.php?PatientID=123&Name=Bruce Wayne

### 🔹 `getAllEC.php`
To get all emergency contacts from the emergency contacts table <br><br>

Ex: GET backend/EC/getAllEC.php

### 🔹 `insertEC.php`
To insert a new emergency contact into the emergency contacts table. <br><br>

Ex: backend/EC/insertEC.php


Body Ex: <br>
```json
{
   "PatientID": 123,
   "Name": "Bruce Wayne",
   "Relationship": "Brother",
   "Phone": "987-654-3210",
   "Email": "bruce.wayne@example.com"
}
```

### 🔹 `updateEC.php`
To update an emergency contact into the emergency contacts table.

Ex: backend/EC/updateEC.php


Body Ex: <br>
```json
{
   "PatientID": 123,
   "Name": "Bruce Wayne",
   "Relationship": "Brother",
   "Phone": "987-654-3210",
   "Email": "bruce.wayne@example.com"
}
```

## Doctors 

### 🔹 `deleteDoctor.php`
To delete a Doctor from the Doctors table with the given Doctor ID<br><br>

Ex: GET backend/Doctors/deleteDoctor.php?DoctorID=123

### 🔹 `getAllDoctors.php`
To get all Doctors from the Doctors table <br><br>

Ex: GET backend/Doctors/getAllDoctors.php

### 🔹 `getDoctor.php`
To get a Doctor entry from the Doctors table with the given first and last name <br><br>

Ex: GET backend/Doctors/getDoctor.php?firstName=tony&lastName=stark

### 🔹 `insertDoctor.php`
To insert a new Doctor entry to the Doctors table <br><br>

Ex: POST backend/Doctors/insertDoctor.php

Body Ex: <br>
```json
{
   "FirstName": "Dr.",
   "LastName": "House",
   "Email": "Foreman'sEmail@example.com",
   "Phone": "123-456-7890",
   "Specialty": "Diagnosis"
}
```

### 🔹 `updateEC.php`
To update a Doctor entry from the Doctors table <br><br>

Ex: POST backend/Doctors/updateDoctor.php


Body Ex: <br>
```json
{
   "DoctorID": 3,
   "FirstName": "Ratchet",
   "LastName": "Bot",
   "Email": "RollOut@example.com",
   "Phone": "123-456-7890",
   "Specialty": "Medic"
}
```
## Appointments
### 🔹 `deleteAppointment.php`
To delete an appointment from the appointments table with the given Doctor ID, Patient ID, Date and Time.  <br><br>

Ex: GET backend/Doctors/deleteDoctor.php?DoctorID=123&PatientID=456&Date=2023-10-01&Time=10:00:00


### 🔹 `getAllAppointments.php`
To get all appointments from the appointments table.<br><br>

Ex: GET backend/Doctors/getAllAppointments.php

### 🔹 `getDoctorApp.php`
To get all appointments for a specific doctor with the given doctor ID from the appointments table.<br><br>

Ex: GET backend/Appointments/getDoctorApp.php?DoctorID=1

### 🔹 `getPatientApp.php`
To get all appointments for a specific patient with the given patient ID from the appointments table.<br><br>

Ex: GET backend/Appointments/getPatientApp.php?PatientID=123

### 🔹 `hoursTaken.php`
To get all the times taken for appointments for a given day from the appointments table.<br><br>

Ex: GET backend/Appointments/HoursTaken.php?Date=2023-10-01

### 🔹 `insertAppointment.php`
To insert a new appointment entry into the appointments table.<br><br>

Ex: POST backend/Appointments/insertAppointment.php

```json
{
    "DoctorID": 2,
    "PatientID": 105,
    "Date": "2023-10-01",
    "Time": "10:00:00",
    "Status": "Scheduled",
    "Description": "Black Plague"
}
```

### 🔹 `updateAppointment.php`
To update an appointment entry from the appointments table.<br><br>

Ex: POST backend/Appointments/updateAppointment.php

```json
{
    "DoctorID": 2,
    "PatientID": 105,
    "Date": "2023-10-01",
    "Time": "10:00:00",
    "Status": "Scheduled",
    "Description": "Black Plague"
}
```





