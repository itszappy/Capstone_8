DROP TABLE IF EXISTS PROBLEMS;
DROP TABLE IF EXISTS SLOTS;
DROP TABLE IF EXISTS PROFESSOR;
DROP TABLE IF EXISTS TUTOR;
DROP TABLE IF EXISTS STUDENT;
DROP TABLE IF EXISTS COURSE;
DROP TABLE IF EXISTS USERTABLE;


CREATE TABLE USERTABLE ( 
	HAWKID VARCHAR(10) NOT NULL,
	HASHED_PSSWRD VARCHAR(255) NOT NULL,
	FIRSTNAME VARCHAR(255) NOT NULL,
	LASTNAME VARCHAR(255) NOT NULL,
	ISADMIN BIT NOT NULL,
	USERROLE VARCHAR(1) NOT NULL,
	EMAIL VARCHAR(255),
	PHONE INT,
	PRIMARY KEY (HAWKID)
	); 
	
CREATE TABLE COURSE (
	COURSEID INT(10) NOT NULL AUTO_INCREMENT,
	SECTION INT NOT NULL,
	STUDENTHAWKID VARCHAR(10) NOT NULL, -- PUT THIS ON HERE SEVERAL TIMES? ONE FOR EACH PERSON?
	TUTORHAWKID VARCHAR(10) NOT NULL,
	PROFESSORHAWKID VARCHAR(10) NOT NULL,
	PRIMARY KEY (COURSEID),
	FOREIGN KEY (PROFESSORHAWKID ) REFERENCES USERTABLE(HAWKID)
	);	
	
CREATE TABLE STUDENT (
	STUDENTHAWKID VARCHAR(10) NOT NULL,
	COURSEID INT(10) NOT NULL,
	SEMESTERBUDGET INT NOT NULL,
	SECTION INT NOT NULL,
	PRIMARY KEY (STUDENTHAWKID, COURSEID),
	FOREIGN KEY (STUDENTHAWKID) REFERENCES USERTABLE(HAWKID),
	FOREIGN KEY (COURSEID) REFERENCES COURSE(COURSEID)
	);
	
CREATE TABLE TUTOR (
	TUTORHAWKID VARCHAR(10) NOT NULL,
	COURSEID INT(10) NOT NULL,
	HOURSPERWEEK INT NOT NULL,
	SECTION INT NOT NULL,
	PRIMARY KEY (TUTORHAWKID, COURSEID),
	FOREIGN KEY (TUTORHAWKID) REFERENCES USERTABLE(HAWKID),
	FOREIGN KEY (COURSEID) REFERENCES COURSE(COURSEID)
	);
	
CREATE TABLE PROFESSOR (
	PROFESSORHAWKID VARCHAR(10) NOT NULL,
	COURSEID INT(10) NOT NULL,
	SECTION INT NOT NULL,
	PRIMARY KEY (PROFESSORHAWKID, COURSEID),
	FOREIGN KEY (PROFESSORHAWKID) REFERENCES USERTABLE(HAWKID),
	FOREIGN KEY (COURSEID) REFERENCES COURSE(COURSEID)
	);
	
	
CREATE TABLE SLOTS (
	SLOTID INT NOT NULL AUTO_INCREMENT,
	SLOTDATE DATE NOT NULL,
	SLOTSTART TIME,
	SLOTEND TIME,
	TUTORHAWKID VARCHAR(10) NOT NULL,
	COURSEID INT(10) NOT NULL,
	LOCATION VARCHAR(30),
	PRIMARY KEY (SLOTID),
	FOREIGN KEY (TUTORHAWKID, COURSEID) REFERENCES TUTOR (TUTORHAWKID, COURSEID)
	);

	
CREATE TABLE PROBLEMS (
	PROBLEMSETID INT NOT NULL AUTO_INCREMENT,
	PROBLEMSETNAME VARCHAR(30) NOT NULL,
	PROBLEMDESC TEXT,
	PROBLEMFILE VARCHAR(20),
	COURSEID INT NOT NULL,
	PRIMARY KEY (PROBLEMSETID),
	FOREIGN KEY (COURSEID) REFERENCES COURSE (COURSEID)
	
	);

