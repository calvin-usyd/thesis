<?php
class ThesisAssessorController extends ThesisController
{
	function afterroute($f3) { 
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'assessor'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function index($f3){
		$f3->set('navTab', 'home');
		$f3->set('inc', 'assessor_dashboard.htm');		
	}
	
	function getStudentProj($f3){
		//GET STUDENT LIST
		$studentArr = $this->getUserArrayBy('student');
		
		//GET ALL ACTIVE SITE
		$activeSite = $this->site->getByArray(array('status=?', 'active'));
		
		//GET STUDENT LIST ASSIGNED TO ASSESSOR
		$email = $f3->get('SESSION.email');
		foreach($activeSite as $aSite){
			$studAssessorResult = $this->vAssessActive->getByArray(array('siteLongId=? and (assessorEmail1=? or assessorEmail2=? or assessorEmail3=?)', $aSite['siteLongId'], $email, $email, $email));
			if(!$this->vAssessActive->dry()){
				break;
			}
		}
		$studAssessorResultArr = array();
		
		//GET PROJECT INFO
		$studentProj = $this->proj->getById('siteLongId', $studAssessorResult[0]['siteLongId']);
		$studentProjArr = array();
		
		foreach($studentProj as $val){
			$studentProjArr[$val['studentEmail']] = array('url'=>$val['url'], 'name'=>$val['name']);
		}
		
		//MERGED ALL INFO
		foreach($studAssessorResult as $res){
			$stuInfo = $studentArr[$res['studentEmail']];
			$projInfo = $studentProjArr[$res['studentEmail']];
			
			array_push($studAssessorResultArr, array(
				'studentId'=>$stuInfo['id'],
				'studentName'=>$stuInfo['fname'] .' '.$stuInfo['lname'],
				'email'=>$stuInfo['email'],
				'projName'=>$projInfo['name'],
				'url'=>$projInfo['url']
			));
		}
		
		echo json_encode(array('siteName'=>$studAssessorResult[0]['name'], 'siteLongId'=>$studAssessorResult[0]['siteLongId'], 'studentList'=>$studAssessorResultArr));
		die();
	}

	function defaultRedirect($f3){
		$f3->reroute('/assessor');
	}
}	