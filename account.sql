--CODE TO CREATE SQL

DROP TABLE IF EXISTS USERTABLE;
DROP TABLE IF EXISTS STUDENTTABLE;
DROP TABLE IF EXISTS TUTORTABLE;
DROP TABLE IF EXISTS COURSETABLE;
DROP TABLE IF EXISTS LOCATIONTABLE;
DROP TABLE IF EXISTS SESSIONTABLE;
DROP TABLE IF EXISTS PROBLEMTABLE;

CREATE TABLE USERTABLE (  --USER IS A PREDEFINED TERM
	HAWKID VARCHAR(255) NOT NULL,
	HASHED_PSSWRD VARCHAR(255) NOT NULL,
	FIRSTNAME VARCHAR(255) NOT NULL,
	LASTNAME VARCHAR(255) NOT NULL,
	ISADMIN BIT NOT NULL,
	USERROLE VARCHAR(1) NOT NULL,
	EMAIL VARCHAR(255),
	PHONE INT(100),
	PRIMARY KEY (HAWKID)
	);
	
CREATE TABLE STUDENTTABLE (
	HAWKID VARCHAR(255) NOT NULL,
	SEMESTERBUDGET INT NOT NULL,
	COURSEID INT NOT NULL, --IS THIS AN INTEGER OR A STRING?
	ACADEMICYEAR VARCHAR(255),
	PRIMARY KEY (HAWKID),
	CONSTRAINT STUDENTHAWKID FOREIGN KEY (HAWKID) REFERENCES USERTABLE(HAWKID)
	
	);
	
CREATE TABLE TUTORTABLE (
	HAWKID VARCHAR(255) NOT NULL,
	HOURSPERWEEK INT NOT NULL,
	PRIMARY KEY (HAWKID),
	CONSTRAINT TUTORHAWKID FOREIGN KEY (HAWKID) REFERENCES USERTABLE(HAWKID)
	
	);
	
--NO PROFESSOR TABLE AS HE TOLD US TO GET RID OF IT.

CREATE TABLE COURSETABLE (
	COURSEID INT NOT NULL,
	LOCATIONID INT NOT NULL,
	STUDENTHAWKID VARCHAR(255) NOT NULL, --PUT THIS ON HERE SEVERAL TIMES? ONE FOR EACH PERSON?
	TUTORHAWKID VARCHAR(255) NOT NULL,
	PROFESSORHAWKID VARCHAR(255) NOT NULL,
	PRIMARY KEY (COURSEID),
	CONSTRAINT PROFESSORHAWKID FOREIGN KEY (PROFESSORHAWKID ) REFERENCES USERTABLE(HAWKID),
	CONSTRAINT STUDENTHAWKID FOREIGN KEY (STUDENTHAWKID) REFERENCES STUDENTTABLE(HAWKID),
	CONSTRAINT TUTORHAWKID FOREIGN KEY (TUTORHAWKID) REFERENCES TUTORTABLE(HAWKID)
	);
	
CREATE TABLE LOCATIONTABLE (
	LOCATIONID INT NOT NULL,
	SESSIONLOCATION VARCHAR(255) NOT NULL, --NAME OR INT?
	SESSIONID INT NOT NULL,
	PRIMARY KEY (LOCATIONID),
	CONSTRAINT SESSIONID FOREIGN KEY (SESSIONID) REFERENCES SESSIONTABLE(SESSIONID)
	
	);
	
CREATE TABLE SESSIONTABLE (
	SESSIONID INT NOT NULL AUTO_INCREMENT,
	PROBLEMSETID INT NOT NULL,
	STUDENTHAWKID VARCHAR(255) NOT NULL,
	TUTORHAWKID VARCHAR(255) NOT NULL,
	CANCEL BIT,
	PRIMARY KEY (SESSIONID),
	CONSTRAINT STUDENTHAWKID FOREIGN KEY (STUDENTHAWKID) REFERENCES STUDENTTABLE(HAWKID),
	CONSTRAINT TUTORHAWKID FOREIGN KEY (TUTORHAWKID) REFERENCES TUTORTABLE(HAWKID),
	CONSTRAINT PROBLEMSETID FOREIGN KEY (PROBLEMSETID) REFERENCES PROBLEMTABLE(PROBLEMSETID)
	);
	
CREATE TABLE AVAILABLESLOTS (
	SLOTID INT NOT NULL AUTO_INCREMENT,
	DATEANDTIME DATETIME NOT NULL,
	TUTORHAWKID VARCHAR(255) NOT NULL,
	PRIMARY KEY (SLOTID),
	CONSTRAINT TUTORHAWKID FOREIGN KEY (TUTORHAWKID) REFERENCES TUTORTABLE(HAWKID)
	
	);

	
CREATE TABLE PROBLEMTABLE (
	PROBLEMSETID INT NOT NULL AUTO_INCREMENT,
	PROBLEMSETNAME VARCHAR(255) NOT NULL,
	COURSEID INT NOT NULL,
	PRIMARY KEY (PROBLEMSETID),
	CONSTRAINT COURSEID FOREIGN KEY (COURSEID) REFERENCES COURSETABLE(COURSEID)
	
	);