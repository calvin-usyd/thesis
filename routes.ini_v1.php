;<?php die(); ?>
[routes]
//ThesisFrontPageController
GET /=ThesisFrontPageController->index

//AuthController
GET /logout=AuthController->logout
GET|POST /login=AuthController->login
GET|POST /register=AuthController->register


//ForgotPasswordController
GET /resetPassword/@id=ForgotPasswordController->resetPassword
POST /resetPassword=ForgotPasswordController->resetPassword
GET|POST /forgotPassword=ForgotPasswordController->processForgot


//ThesisAccountController
GET /dashboard=ThesisAccountController->index


//ADMIN
//AuthController
GET|POST /a_register=AuthController->adminRegister

//ThesisAdminController
GET /admin=ThesisAdminController->index
GET /showStatistic=ThesisAdminController->showAnswerStatistic
GET /admin/*=ThesisAdminController->defaultRedirect

//ThesisGroupController
POST /admin/groupConfigure=ThesisGroupController->configure
POST /groupUserDelete=ThesisGroupController->groupUserDelete
POST /groupEditAdd=ThesisGroupController->editAdd
POST /groupDelete=ThesisGroupController->delete

//ThesisStudentController
GET /studentThesis=ThesisStudentController->view
POST /markEdit=ThesisStudentController->markEdit


//PROFESSOR
//ThesisProfessorController
GET /professor=ThesisProfessorController->index
POST /professor/groupStudentView=ThesisProfessorController->groupStudentView
POST /professor/markEdit=ThesisProfessorController->markEdit
GET /professor/*=ThesisProfessorController->defaultRedirect
