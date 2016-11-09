;<?php die(); ?>
[routes]
//ThesisFrontPageController
GET /=ThesisFrontPageController->index

//ThesisAuthController
GET /logout=ThesisAuthController->logout
GET|POST /login=ThesisAuthController->login
GET|POST /profile=ThesisAuthController->profile
GET|POST /register=ThesisAuthController->adminRegister
GET|POST /changePassword=ThesisAuthController->changePassword

//ForgotPasswordController
GET /resetPassword/@id=ForgotPasswordController->resetPassword
POST /resetPassword=ForgotPasswordController->resetPassword
GET|POST /forgotPassword=ForgotPasswordController->processForgot


//ADMIN
//ThesisAdminController
GET /admin=ThesisAdminController->index
GET /admin/*=ThesisAdminController->defaultRedirect
GET /admin/sites=ThesisAdminController->sites
POST /admin/coordinatorList=ThesisAdminController->coordinatorList
POST /admin/coordinatorAddEdit=ThesisAdminController->coordinatorAddEdit
POST /admin/coordinatorDelete=ThesisAdminController->coordinatorDelete


//COORDINATOR
//ThesisCoordinatorController
GET /coordinator=ThesisCoordinatorController->index
GET /coordinator/*=ThesisCoordinatorController->defaultRedirect

POST /coordinator/siteList=ThesisCoordinatorController->siteList
POST /coordinator/assessorList=ThesisCoordinatorController->assessorList
POST /coordinator/studentList=ThesisCoordinatorController->studentList
POST /coordinator/studentURLList=ThesisCoordinatorController->studentURLList

POST /coordinator/siteAddEdit=ThesisCoordinatorController->siteAddEdit
POST /coordinator/assessorAddEdit=ThesisCoordinatorController->assessorAddEdit
POST /coordinator/studentAddEdit=ThesisCoordinatorController->studentAddEdit

POST /coordinator/siteDelete=ThesisCoordinatorController->siteDelete
POST /coordinator/alertAssessor=ThesisCoordinatorController->alertAssessor

POST /coordinator/studentAssessorList=ThesisCoordinatorController->studentAssessorList
POST /coordinator/assignStudent2Assessor=ThesisCoordinatorController->assignStudent2Assessor
POST /coordinator/deallocateStudent2Assessor=ThesisCoordinatorController->deallocateStudent2Assessor

//ASSESSOR
//ThesisAssessorController
GET /assessor=ThesisAssessorController->index
GET /assessor/*=ThesisAssessorController->defaultRedirect
POST /assessor/getStudentProj=ThesisAssessorController->getStudentProj


//STUDENT
//ThesisStudentController
GET /student=ThesisStudentController->index
GET /student/*=ThesisStudentController->defaultRedirect
GET /student/profileView=ThesisStudentController->profileView
POST /student/profileEdit=ThesisStudentController->profileEdit
POST /student/projectAddEdit=ThesisStudentController->project

