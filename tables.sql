-- CODE TO CREATE SQL

DROP TABLE IF EXISTS SESSIONTABLE;
DROP TABLE IF EXISTS PROBLEMS;
DROP TABLE IF EXISTS AVAILABLESLOTS;
DROP TABLE IF EXISTS COURSE;
DROP TABLE IF EXISTS TUTOR;
DROP TABLE IF EXISTS STUDENT;
DROP TABLE IF EXISTS USERTABLE;


CREATE TABLE USERTABLE (  -- USER IS A PREDEFINED TERM
	HAWKID VARCHAR(10) NOT NULL,
	HASHED_PSSWRD VARCHAR(255) NOT NULL,
	FIRSTNAME VARCHAR(255) NOT NULL,
	LASTNAME VARCHAR(255) NOT NULL,
	ISADMIN BOOLEAN NOT NULL,
	USERROLE VARCHAR(1) NOT NULL,
	EMAIL VARCHAR(255),
	PHONE INT,
	PRIMARY KEY (HAWKID)
	); 
	
CREATE TABLE STUDENT (
	STUDENTHAWKID VARCHAR(10) NOT NULL,
	SEMESTERBUDGET INT(10) NOT NULL,
	COURSEID INT(10) NOT NULL, -- IS THIS AN INTEGER OR A STRING?
	ACADEMICYEAR VARCHAR(255),
	PRIMARY KEY (STUDENTHAWKID),
	FOREIGN KEY (STUDENTHAWKID) REFERENCES USERTABLE(HAWKID)
	
	);
	
CREATE TABLE TUTOR (
	TUTORHAWKID VARCHAR(10) NOT NULL,
	HOURSPERWEEK INT NOT NULL,
	PRIMARY KEY (TUTORHAWKID),
	FOREIGN KEY (TUTORHAWKID) REFERENCES USERTABLE(HAWKID)
	
	);
	
-- NO PROFESSOR TABLE AS HE TOLD US TO GET RID OF IT.

CREATE TABLE COURSE (
	COURSEID INT NOT NULL,
	STUDENTHAWKID VARCHAR(10) NOT NULL, -- PUT THIS ON HERE SEVERAL TIMES? ONE FOR EACH PERSON?
	TUTORHAWKID VARCHAR(10) NOT NULL,
	PROFESSORHAWKID VARCHAR(10) NOT NULL,
	PRIMARY KEY (COURSEID),
	FOREIGN KEY (PROFESSORHAWKID ) REFERENCES USERTABLE(HAWKID),
	FOREIGN KEY (STUDENTHAWKID) REFERENCES STUDENT (STUDENTHAWKID),
	FOREIGN KEY (TUTORHAWKID) REFERENCES TUTOR (TUTORHAWKID)
	);
	
	
CREATE TABLE AVAILABLESLOTS (
	SLOTID INT NOT NULL AUTO_INCREMENT,
	DATEAVAILABLE DATE NOT NULL,
	AVAILABLESTART TIME,
	AVAILABLEEND TIME,
	TUTORHAWKID VARCHAR(10) NOT NULL,
	PRIMARY KEY (SLOTID),
	FOREIGN KEY (TUTORHAWKID) REFERENCES TUTOR (TUTORHAWKID)
	
	);

	
CREATE TABLE PROBLEMS (
	PROBLEMSETID INT NOT NULL AUTO_INCREMENT,
	PROBLEMSETNAME VARCHAR(255) NOT NULL,
	PROBLEMDESC TEXT,
	PROBLEMFILE VARCHAR(20),
	COURSEID INT NOT NULL,
	PRIMARY KEY (PROBLEMSETID),
	FOREIGN KEY (COURSEID) REFERENCES COURSE (COURSEID)
	
	);
	
CREATE TABLE SESSIONTABLE (
	SESSIONID INT NOT NULL AUTO_INCREMENT,
	PROBLEMSETID INT NOT NULL,
	STUDENTHAWKID VARCHAR(10) NOT NULL,
	TUTORHAWKID VARCHAR(10) NOT NULL,
	SESSIONDATE DATE NOT NULL,
	SESSIONSTART TIME NOT NULL,
	SESSIONEND TIME NOT NULL,
	CANCEL BIT,
	LOCATION VARCHAR(255),
	PRIMARY KEY (SESSIONID),
	FOREIGN KEY (STUDENTHAWKID) REFERENCES STUDENT (STUDENTHAWKID),
	FOREIGN KEY (TUTORHAWKID) REFERENCES TUTOR (TUTORHAWKID),
	FOREIGN KEY (PROBLEMSETID) REFERENCES PROBLEMS (PROBLEMSETID)

	);

