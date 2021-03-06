<?php
class ThesisController {
	function __construct($f3) {
		$this->db=new DB\SQL(
			$f3->get('db_dns') . $f3->get('db_name'),
			$f3->get('db_user'),
			$f3->get('db_pass')
		);
		
		$this->users 		= new user($this->db);
		$this->site 		= new site($this->db);
		$this->siteUser 	= new siteUser($this->db);
		$this->proj 		= new project($this->db);
		$this->stuAssessor	= new studentAssessor($this->db);
		$this->sUserActive	= new viewSiteUserActive($this->db);
		//$this->vStuAssessor	= new viewStudentAssessor($this->db);
		$this->vAssessActive= new viewSiteAssessorActive($this->db);
		
        $f3->set('year', date('Y'));
	}
	
	function getUserList($f3, $position){
		$user = $this->users->getById('position', $position);
		//$user = $this->users->getByArray($userArr);
		
		function mapUser($val){
			return array('userLongId'=>$val['userLongId'], 'id'=>$val['id'], 'fname'=>$val['fname'], 'lname'=>$val['lname'], 'email'=>$val['email']);
		}
		
		$mappedResult = array_map('mapUser', $user);		
		
		return $mappedResult;		
	}
	
	function afterroute($f3) {
		echo Template::instance()->render('/z_layout.htm');
    }
	
	function genLongId(){
		return substr(hash('sha512',mt_rand()),0,12);
	}
	
	function deleteDirectory($dir) {
		if (!file_exists($dir)) {
			return true;
		}

		if (!is_dir($dir)) {
			return unlink($dir);
		}

		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}

			if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
				return false;
			}
		}

		return rmdir($dir);
	}
	
	function getUserArrayBy($position){
		$students = $this->users->getById('position', $position);
		$studentArr = array();
		
		foreach($students as $val){
			$studentArr[$val['email']] = array('id'=>$val['id'], 'fname'=>$val['fname'], 'lname'=>$val['lname'],  'email'=>$val['email']);
		}
		return $studentArr;
	}
	
	function checkSessionAjax($f3){
		if ($f3->get('SESSION.user') == ''){
			echo json_encode(array('failSession', 'Session expired!'));
			die();
		}
	}
}