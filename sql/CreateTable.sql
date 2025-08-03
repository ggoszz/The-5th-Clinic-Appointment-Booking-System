create table doctors (
  DoctorID int primary key,
  FirstName varchar(50) not null,
  LastName varchar(50) not null,
  Email varchar(100) not null,
  Phone varchar(15) not null,
  Specialty varchar(50)
);

create table patients (
  PatientID int primary key,
  FirstName varchar(50) not null,
  LastName varchar(50) not null,
  Email varchar(100) not null,
  DOB Date,
  Phone varchar(15) not null,
  Address varchar(200),
  Gender varchar(10),
  InsuranceName varchar(100),
  InsuranceID varchar(50)
);

create table appointments (
  DoctorID int not null,
  PatientID int not null, 
  Date date not null,
  Time time not null,
  Status varchar(20) not null default 'scheduled',
  Description text,
  
  primary key (DoctorID, PatientID, Date, Time),
  foreign key (DoctorID) references doctors(DoctorID)
    on delete cascade
    on update cascade,
  foreign key (PatientID) references patients(PatientID)
    on delete cascade
    on update cascade
);

create table emergencyContacts (
  PatientID int not null,
  Name varchar(100) not null,
  Relationship varchar(50),
  Phone varchar(15) not null,
  Email varchar(100),
  
  primary key (PatientID, Name),
  foreign key (PatientID) references patients(PatientID)
    on delete cascade
    on update cascade
);