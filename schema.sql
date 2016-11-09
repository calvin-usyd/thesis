drop table `thesis_users`;
drop table `thesis_site`;
drop table `thesis_site_user`;
drop table `thesis_project`;
drop table `thesis_student_assessor`;
drop view `thesis_view_student_assessor`;
drop view `thesis_view_site_user_active`;
drop view `thesis_view_site_assessor_active`;

CREATE TABLE thesis_users
(
	userLongId varchar (20) NOT NULL PRIMARY KEY,/*ADMIN & COORDINATE EDIT/DELETE USING userLongId*/
	email varchar (100) NOT NULL,/*STUDENT & ASSESSOR EDIT/DELETE USING EMAIL*/
	id varchar (50) NOT NULL,/*FOR ADMIN AND STUDENT USE ONLY! Only admin login using unikey, the rest login using email. student just for storing their student id*/
	forgotPass varchar (100),
	password varchar (100) NOT NULL,
	fname varchar (50),/*first name*/
	lname varchar (50),/*family name*/
	position varchar (20) DEFAULT 'student',/*admin, coordinator, assessor, student*//*admin need to be manually set in sql*/
	approved varchar (2) DEFAULT '0'/*1*/
);
--coordinator & admin
CREATE TABLE thesis_site
(
	siteLongId varchar (20) NOT NULL PRIMARY KEY,
	coordinatorEmail varchar (100) NOT NULL,
	name varchar (1000),/*site name : semester 1*/
	status varchar (100),/*active, inactive*/
	expired varchar (20)
);
--coordinator & assessor & student
CREATE TABLE thesis_project
(
	projLongId varchar (20) NOT NULL PRIMARY KEY,
	siteLongId varchar (20) NOT NULL,
	studentEmail varchar (100) NOT NULL,
	name varchar (1000),
	url varchar (1000),
	submitDate TIMESTAMP DEFAULT NOW()
);
--coordinator & assessor & student 
CREATE TABLE thesis_student_assessor
(
	studentAssessorLongId varchar (20) NOT NULL PRIMARY KEY,
	siteLongId varchar (20) NOT NULL,
	studentEmail varchar (100) NOT NULL,
	assessorEmail1 varchar (100) NOT NULL,
	assessorEmail2 varchar (100) ,
	assessorEmail3 varchar (100) 
);

--coordinator & assessor & student 
CREATE TABLE thesis_site_user
(
	siteLongId varchar (20) NOT NULL,
	studentEmail varchar (100) NOT NULL,
	projectId varchar (20) NOT NULL,/*Group id used by Luming*/
	PRIMARY KEY (siteLongId, studentEmail)
);
/*
CREATE VIEW thesis_view_student_assessor AS
	SELECT su.siteLongId, su.studentEmail, sa.studentAssessorLongId, sa.assessorEmail
	FROM thesis_site_user su
	LEFT JOIN thesis_student_assessor sa
	ON su.siteLongId = sa.siteLongId
	AND su.studentEmail = sa.studentEmail;
	*/
--student
CREATE VIEW thesis_view_site_user_active AS 
	SELECT site.name, site.expired, site.siteLongId , sUser.studentEmail
	FROM thesis_site site, thesis_site_user sUser
	WHERE site.siteLongId = sUser.siteLongId
	AND site.status = 'active';

--assessor
CREATE VIEW thesis_view_site_assessor_active AS 
	SELECT site.name, site.siteLongId, assessor.studentEmail, assessor.assessorEmail1, assessor.assessorEmail2, assessor.assessorEmail3
	FROM thesis_site site, thesis_student_assessor assessor
	WHERE site.siteLongId = assessor.siteLongId
	AND site.status = 'active';


ALTER TABLE thesis_project_assessor ADD COLUMN projLongId varchar (20) NOT NULL;
ALTER TABLE thesis_project ADD COLUMN siteLongId varchar (20) NOT NULL;
ALTER TABLE thesis_project ADD COLUMN submitDate TIMESTAMP DEFAULT NOW();
ALTER TABLE thesis_project MODIFY submitDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE thesis_site ADD COLUMN expired varchar (20);
ALTER TABLE thesis_site_user DROP PRIMARY KEY;
ALTER TABLE thesis_site_user ADD PRIMARY KEY(siteLongId,userLongId);