<?php
require 'SendEmailController.php';

class ThesisCoordinatorController extends ThesisController
{	
	function afterroute($f3) { 
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'coordinator'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function defaultRedirect($f3){
		$f3->reroute('/coordinator');
	}
	
	function index($f3){
		//GET ASSESSOR LIST
		$f3->set('assessors', $this->getUserList($f3, 'assessor'));
		
		//GET SITE LIST
		$f3->set('sites', $this->getSiteList($f3));
		
		$f3->set('navTab', 'home');
		$f3->set('inc', 'coordinator_dashboard.htm');
	}

	private function getSiteList($f3){		
		$sites = $this->site->getById('coordinatorEmail', $f3->get('SESSION.user'));
		
		function mapSite($val){
			return array('siteLongId'=>$val['siteLongId'], 'name'=>$val['name'], 'status'=>$val['status'], 'expired'=>$val['expired']);
		}
		
		$mappedResult = array_map('mapSite', $sites);		
		
		return $mappedResult;
	}

	function getSiteUserList($f3, $siteLongId){
		$mappedResult = array();
		$mappedResultStuAssessor = array();
		
		//GET All STUDENTS UNDER THE SITE
		$siteUsers = $this->siteUser->getById('siteLongId', $siteLongId);
		
		//GET ALL USERS BY STUDENT & ASSESSOR
		$studentArr = $this->getUserArrayBy('student');
		//$assessorArr = $this->getUserArrayBy('assessor');
		
		//GET ALL ASSESSORS UNDER THE SITE
		$stuAssessorResult = $this->stuAssessor->getByArray(array('siteLongId=?', $siteLongId));
		
		foreach($stuAssessorResult as $stuAssessor){
			//$val = $assessorArr[$stuAssessor['assessorEmail']];
			//array_push($mappedResultStuAssessor, array('studentEmail'=>$stuAssessor['studentEmail']));
			$mappedResultStuAssessor[$stuAssessor['studentEmail']] = $stuAssessor;
		}
		foreach($siteUsers as $siteUser){
			$val = $studentArr[$siteUser['studentEmail']];
			$stuAssessorVal = $mappedResultStuAssessor[$siteUser['studentEmail']];
			
			array_push($mappedResult, array(
				'studentEmail'=>$siteUser['studentEmail'], 
				'id'=>$val['id'], 
				'fname'=>$val['fname'], 
				'lname'=>$val['lname'],
				'projectId'=>$siteUser['projectId'],
				'assessorEmail1'=>$stuAssessorVal['assessorEmail1'], 
				'assessorEmail2'=>$stuAssessorVal['assessorEmail2'], 
				'assessorEmail3'=>$stuAssessorVal['assessorEmail3'],
				'invitation'=>'false'
			));
		}
		
		return $mappedResult;		
	}
	
	
	
	function siteList($f3){
		$this->checkSessionAjax($f3);
		
		echo json_encode($this->getSiteList($f3));
		die();
	}
	
	function assessorList($f3){
		echo json_encode($this->getUserList($f3, 'assessor'));
		die();		
	}
	
	function studentList($f3){
		echo json_encode($this->getSiteUserList($f3, $f3->get('POST.siteLongId')));
		die();		
	}
	
	/*function studentAssessorList($f3){
		//GET STUDENT LIST
		$studentArr = $this->getUserArrayBy('student');
		
		//GET STUDENT ASSESSOR LIST
		$stuAssessorResult = $this->stuAssessor->getByArray(array('siteLongId=? and assessorEmail=?', $f3->get('POST.siteLongId'), $f3->get('POST.assessorEmail')));
		$stuAssessorResultArr = array();
		
		foreach($stuAssessorResult as $rec){
			$stuAssessorResultArr[$rec['studentEmail']] = $rec['studentAssessorLongId'];
		}
		
		//GET SITE STUDENT LIST
		$siteUserResult = $this->siteUser->getById('siteLongId', $f3->get('POST.siteLongId'));
		$siteUserResultArr = array();
		
		foreach($siteUserResult as $rec){
			$stu = $studentArr[$rec['studentEmail']];
			
			array_push($siteUserResultArr, array(
				'siteLongId'=>$rec['siteLongId'], 
				'studentAssessorLongId'=>$stuAssessorResultArr[$rec['studentEmail']], 
				'studentEmail'=>$rec['studentEmail'],
				'name'=>$stu['fname'] .' '. $stu['lname'],
				'email'=>$stu['email'],
				'studentId'=>$stu['id'],
				'selectedAssessor'=>$f3->get('POST.assessorEmail')
			));
			
		}
		
		echo json_encode($siteUserResultArr);
		die();
	}*/
	
	function studentURLList($f3){
		$studentProj = $this->proj->getById('siteLongId', $f3->get('POST.siteLongId'));
		$studentProjArr = array();
		
		foreach($studentProj as $val){
			$studentProjArr[$val['studentEmail']] = array('url'=>$val['url'], 'name'=>$val['name'], 'submitDate'=>$val['submitDate']);
		}
		
		echo json_encode(array(
			'studentProj'=>$studentProjArr,
			'student'=>$this->getSiteUserList($f3, $f3->get('POST.siteLongId'))
		));
		die();		
	}
	
	
	
	function siteAddEdit($f3){
		$this->checkSessionAjax($f3);
		
		$this->site->getById('siteLongId', $f3->get('POST.siteLongId'));
		
		if ($this->site->dry()){
			if ($f3->exists('POST.name') && $f3->exists('POST.status')){
				$f3->set('POST.siteLongId', $this->genLongId());
				$f3->set('POST.coordinatorEmail', $f3->get('SESSION.user'));
				$this->site->add();
			}else{
				echo json_encode(array('fail', 'Please fill up all the fields!'));
				die();
			}
		}else{
			$this->site->edit('siteLongId', $f3->get('POST.siteLongId'));
		}
		$this->siteList($f3);
	}
		
	public function assessorAddEdit($f3){
		$postData = json_decode($f3->get('BODY'), true);
		
		foreach($postData as $rec){			
			if ($rec['email'] != ''){
				if ($rec['delete'] == 'true'){
					$this->users->deleteByArray(array('email=? and position=?', $rec['email'], 'assessor'));
		
				}else{
					$f3->set('POST.fname', $rec['fname']);
					$f3->set('POST.lname', $rec['lname']);
					$f3->set('POST.email', $rec['email']);
					$f3->set('POST.position', 'assessor');
					
					$this->users->reset();
					
					$this->users->getByArray(array('email=? and position=?', $rec['email'], 'assessor'));
					
					if ($this->users->dry()){//IS NEW ASSESSOR
						$crypt = \Bcrypt::instance();
						$f3->set('POST.password', $crypt->hash($rec['email']));
						$f3->set('POST.userLongId', $this->genLongId());
						$this->users->add();
						
					}else{
						$this->users->editByArray(array('email=? and position=?', $rec['email'], 'assessor'));
					}
				}
				
				//EMAIL INVITATION
				if ($rec['invitation'] == 'true'){
					$this->sendInvitation($f3, $f3->get('SESSION.email'), $f3->get('SESSION.fname'), $rec['email'], $rec['fname']);
				}
			}
		}
		
		//$this->studentList($f3);
		$this->assessorList($f3);
	}
	
	public function studentAddEdit($f3){
		$this->checkSessionAjax($f3);
		
		$postData = json_decode($f3->get('BODY'), true);
		
		$siteLongId  = $postData['siteLongId'];
		
		$this->db->exec('DELETE FROM thesis_site_user WHERE siteLongId="'.$siteLongId.'"');
		$this->db->exec('DELETE FROM thesis_student_assessor WHERE siteLongId="'.$siteLongId.'"');
		
		$f3->set('POST.siteLongId', $siteLongId);

		foreach($postData['student'] as $rec){
			if ($rec['studentEmail'] != '' && $rec['delete'] != 'true'){
				$f3->set('POST.id', $rec['id']);
				$f3->set('POST.fname', $rec['fname']);
				$f3->set('POST.lname', $rec['lname']);
				$f3->set('POST.email', $rec['studentEmail']);
				$f3->set('POST.position', 'student');
				
				//ADD STUDENT
				$this->users->reset();
				
				$this->users->getByArray(array('email=? and position=?', $rec['studentEmail'], 'student'));
				
				if ($this->users->dry()){//IS NEW STUDENT
					$crypt = \Bcrypt::instance();
					$f3->set('POST.password', $crypt->hash($rec['studentEmail']));
					$f3->set('POST.userLongId', $this->genLongId());
					$this->users->add();
					
				}else{
					$this->users->editByArray(array('email=? and position=?', $rec['studentEmail'], 'student'));
				}
				
				//ASSIGN STUDENT TO SITE
				$f3->set('POST.studentEmail', $rec['studentEmail']);
				$f3->set('POST.projectId', $rec['projectId']);
				$this->siteUser->reset();
				$this->siteUser->add();
				
				//ASSIGN ASSESSOR TO STUDENT
				if ($rec['assessorEmail1'] != ''){
					$f3->set('POST.studentAssessorLongId', $this->genLongId());
					$f3->set('POST.assessorEmail1', $rec['assessorEmail1']);
					$f3->set('POST.assessorEmail2', $rec['assessorEmail2']);
					$f3->set('POST.assessorEmail3', $rec['assessorEmail3']);
					$this->stuAssessor->reset();
					$this->stuAssessor->add();
				}
				
				//EMAIL INVITATION
				if ($rec['invitation'] == 'true'){
					$this->sendInvitation($f3, $f3->get('SESSION.email'), $f3->get('SESSION.fname'), $rec['studentEmail'], $rec['fname']);
				}
			}
		}
		
		$this->studentList($f3);
	}
	
	public function alertAssessor($f3){
		$sbj = 'Assessor Notification Alert | Thesis Management University of Sydney';
		$frEmail= $f3->get('SESSION.email');
		$frName= $f3->get('SESSION.fname');
		$msg = 'Dear Assessor,<br><br>';
		$msg = $msg . $f3->get('POST.emailContent') . '<br><br>';
		$msg = $msg . 'Regards,<br>';
		$msg = $msg . $f3->get('SESSION.fname');
		
		$assessorArr = array($frEmail);
		$assessorResult = $this->stuAssessor->getById('siteLongId', $f3->get('POST.siteLongId'));
		
		foreach($assessorResult as $assessorRec){
			$ae1 = $assessorRec['assessorEmail1'];
			$ae2 = $assessorRec['assessorEmail2'];
			$ae3 = $assessorRec['assessorEmail3'];
			
			if ($ae1 != '')
				array_push($assessorArr, $ae1);
			
			if ($ae2 != '')
				array_push($assessorArr, $ae2);
			
			if ($ae3 != '')
				array_push($assessorArr, $ae3);
		}
		
		$assessorArr = array_unique($assessorArr);
			
		foreach($assessorArr as $aEmail){
			$cnt_json = json_encode(array(
				'fr'=>$frEmail, 
				'to'=>$aEmail, 
				'cc'=>$frEmail, 
				'subject'=>$sbj, 
				'message'=>$msg, 
				'frName'=>$frName
			));
			
			$email = new SendEmailController();
			$email->post_json($f3->get('CONFIG_qMAPI'), $cnt_json);
		}
		
		echo json_encode(array('Success', 'Email sent successfully!'));
		die();
	}
	
	private function sendInvitation($f3, $frEmail, $frName, $toEmail, $toName){
		$url='http://thesis.quantumfi.net';
		$sbj='Invitation Alert | Thesis Management University of Sydney';
		$msg='Dear ' . $toName . ',<br><br>';
		$msg=$msg . 'A thesis management account has been created for you! Kindly login with the following email at <a href="'.$url.'">'.$url.'</a> :<br><br>';
		$msg=$msg . '<b>'.$toEmail . '</b><br><br>';
		$msg=$msg . 'Password is the same as the email. Please change your password after login.<br><br>';
		$msg=$msg . 'Regards,<br>';
		$msg=$msg . $frName;
		
		$cnt_json = json_encode(array('fr'=>$frEmail, 'to'=>$toEmail, 'subject'=>$sbj, 'message'=>$msg, 'frName'=>$frName));
		
		$email = new SendEmailController();
		$email->post_json($f3->get('CONFIG_qMAPI'), $cnt_json);
	}
	
	function siteDelete($f3){
		$this->site->delete('siteLongId', $f3->get('POST.siteLongId'));
		
		$this->siteList($f3);
	}
	/*function assessorDelete($f3){
		$this->users->deleteByArray('email=? and position=?', $f3->get('POST.email'), 'assessor');
		
		$this->assessorList($f3);
	}
	function studentDelete($f3){
		$this->users->deleteByArray('email=? and position=?', $f3->get('POST.email'), 's');
		
		$this->coordinatorList($f3);
	}*/
	
	/*function assignStudent2Assessor($f3){
		if ($f3->get('POST.siteLongId') != '' && $f3->get('POST.assessorLongId') != '' && $f3->get('POST.studentLongId') != '' ){
			$this->stuAssessor->getByArray(array('siteLongId=? and assessorLongId=? and studentLongId=?', 
				$f3->get('POST.siteLongId'), 
				$f3->get('POST.assessorLongId'), 
				$f3->get('POST.studentLongId')
			));
			
			if ($this->stuAssessor->dry()){
				$f3->set('POST.studentAssessorLongId', $this->genLongId());
				
				$this->stuAssessor->add();
				
				$msg = array('Success', 'Project successfully assigned to assessor!');
			}else{
				$msg = array('Warning', 'Data submitting twice!');
				
			}
			
		}else{
			$msg = array('Warning', 'Missing required data!');
		}
		
		$this->studentAssessorList($f3);
		//echo json_encode($msg);
		//die();
	}
	function deallocateStudent2Assessor($f3){
		if ($f3->get('POST.siteLongId') != '' && $f3->get('POST.assessorLongId') != '' && $f3->get('POST.studentLongId') != '' ){
			$this->stuAssessor->deleteByArray(array('siteLongId=? and assessorLongId=? and studentLongId=?', 
					$f3->get('POST.siteLongId'),
					$f3->get('POST.assessorLongId'),
					$f3->get('POST.studentLongId')
			));
			
			$msg = array('Success', 'Project successfully deallocate from assessor!');
			
		}else{
			$msg = array('Warning', 'Missing required data!');
		}
		
		$this->studentAssessorList($f3);
		//echo json_encode($msg);
		//die();
	}*/
}	