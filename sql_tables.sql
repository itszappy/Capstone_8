--code to create sql

DROP TABLE IF EXISTS user;

CREATE TABLE userTable (  --user is a predefined term
	hawkID VARCHAR(255) NOT NULL, 
	firstName VARCHAR(255) NOT NULL,
	lastName VARCHAR(255) NOT NULL,
	userRole VARCHAR(1) NOT NULL,
	email VARCHAR(255),
	phone INT,
	PRIMARY KEY (hawkID)
	);
	
CREATE TABLE studentTable (
	hawkID VARCHAR(255) NOT NULL,
	semesterBudget INT NOT NULL,
	courseID INT NOT NULL, --is this an integer or a string?
	academicYear VARCHAR(255),
	PRIMARY KEY (hawkID),
	CONSTRAINT studentHawkID FOREIGN KEY (hawkID) REFERENCES userTable(hawkID)
	
	);
	
CREATE TABLE tutorTable (
	hawkID VARCHAR(255) NOT NULL,
	hoursPerWeek INT NOT NULL,
	PRIMARY KEY (hawkID),
	CONSTRAINT tutorHawkID FOREIGN KEY (hawkID) REFERENCES userTable(hawkID)
	
	);
	
--no professor table as he told us to get rid of it.

CREATE TABLE courseTable (
	courseID INT NOT NULL,
	locationID INT NOT NULL,
	studentHawkID VARCHAR(255) NOT NULL, --put this on here several times? one for each person?
	tutorHawkID VARCHAR(255) NOT NULL,
	professorHawkID VARCHAR(255) NOT NULL,
	PRIMARY KEY (courseID),
	CONSTRAINT professorHawkID FOREIGN KEY (professorHawkID ) REFERENCES userTable(hawkID),
	CONSTRAINT studentHawkID FOREIGN KEY (studenthawkID) REFERENCES studentTable(hawkID),
	CONSTRAINT tutorHawkID FOREIGN KEY (tutorhawkID) REFERENCES tutorTable(hawkID),
	);
	
CREATE TABLE locationTable (
	locationID INT NOT NULL,
	sessionLocation VARCHAR(255) NOT NULL, --name or int?
	sessionID INT NOT NULL,
	PRIMARY KEY (locationID),
	CONSTRAINT sessionID FOREIGN KEY (sessionID) REFERENCES sessionTable(sessionID)
	
	);
	
CREATE TABLE sessionTable (
	sessionID INT NOT NULL AUTO_INCREMENT,
	problemSetID INT NOT NULL,
	studentHawkID VARCHAR(255) NOT NULL,
	tutorHawkID VARCHAR(255) NOT NULL,
	cancel BIT,
	PRIMARY KEY (sessionID),
	CONSTRAINT studentHawkID FOREIGN KEY (studenthawkID) REFERENCES studentTable(hawkID),
	CONSTRAINT tutorHawkID FOREIGN KEY (tutorhawkID) REFERENCES tutorTable(hawkID),
	CONSTRAINT problemSetID FOREIGN KEY (problemSetID) REFERENCES problemTable(problemSetID),
	);
	
CREATE TABLE availableSlots (
	slotID INT NOT NULL AUTO_INCREMENT,
	dateAndTime DATETIME NOT NULL,
	tutorhawkID VARCHAR(255) NOT NULL,
	PRIMARY KEY (slotID),
	CONSTRAINT tutorHawkID FOREIGN KEY (tutorhawkID) REFERENCES tutorTable(hawkID),
	
	);

	
CREATE TABLE problemTable (
	problemSetID INT NOT NULL AUTO_INCREMENT,
	problemSetName VARCHAR(255) NOT NULL,
	courseID INT NOT NULL,
	PRIMARY KEY (problemSetID),
	CONSTRAINT courseID FOREIGN KEY (courseID) REFERENCES courseTable(courseID)
	
	);