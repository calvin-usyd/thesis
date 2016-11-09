<?php
class ThesisStudentController extends ThesisAdminController
{
	function afterroute($f3) {
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'student'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function index($f3){
		$email = $f3->get('SESSION.email');
		
		$this->sUserActive->getById('studentEmail', $email);
		
		$siteLongId = $this->sUserActive->siteLongId;
				
		$this->proj->getByArray(array('studentEmail=? and siteLongId=?', $email, $siteLongId));
		
		$f3->set('siteName', $this->sUserActive->name);
		$f3->set('expired', $this->sUserActive->expired);
		$f3->set('name', $this->proj->name);
		$f3->set('url', $this->proj->url);
		$f3->set('projLongId', $this->proj->projLongId);
		$f3->set('siteLongId', $siteLongId);
		$f3->set('navTab', 'home');
		$f3->set('inc', 'student_dashboard.htm');
	}
	
	function defaultRedirect($f3){
		$f3->reroute('/student');
	}
	
	function profileEdit($f3){
		if ($f3->get('POST.fname') != '' && 
			$f3->get('POST.lname') != '' && 
			$f3->get('POST.email') != '' ){
			$pass = true;
			if ($f3->get('POST.changePassword') == 1){
				if ($f3->get('POST.currentPassword') != '' && $f3->get('POST.newPassword') != ''){
				
					$this->users->getById('email', $f3->get('SESSION.email'));
					$validPass = 0;
					
					if (!$this->users->dry()){
						$crypt = \Bcrypt::instance();
						$validPass = $crypt->verify($f3->get('POST.currentPassword'), $this->users->password);

						if ($validPass){
							$f3->set('POST.password', $crypt->hash($f3->get('POST.newPassword')));
						}else{
							$msg = array('Danger', 'Incorrect current password!');
							$pass = false;
						}	
					}
				}else{
					$msg = array('Warning', 'Please fill up all the password fields or un-check "Change password" checkbox!');
					$pass = false;
				}
			}
			if ($pass){
				$this->users->reset();
				$this->users->edit('email', $f3->get('SESSION.email'));
				
				$f3->set('SESSION.fname', $f3->get('POST.fname'));
				$f3->set('SESSION.lname', $f3->get('POST.lname'));
				$f3->set('SESSION.email', $f3->get('POST.email'));
				
				$msg = array('Success', 'Profile updated successfully!');
			}
			
		}else{
			$msg = array('Warning', 'Please fill up all empty fields!');
		}
		
		echo json_encode($msg);
		die();
	}
	
	function profileView($f3){
		$msg = array(
			'id'=>$f3->get('SESSION.user'), 
			'fname'=>$f3->get('SESSION.fname'), 
			'lname'=>$f3->get('SESSION.lname'),
			'email'=>$f3->get('SESSION.email'));
		echo json_encode($msg);
		die();
	}
	
	function project($f3){
		$this->checkSessionAjax($f3);
		
		$msg = array();
		
		if ($f3->get('POST.name') != '' && $f3->get('POST.siteLongId') != '' ){
			//IF UPLOAD SUCCESS
			if ($_FILES['projectUpload']['error'] == 0){
				
				//VALIDATE FILE TYPE
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $_FILES['projectUpload']['tmp_name']);
				
				if ($mime != 'application/pdf'){
					$msg = array('Warning', 'Invalid uploaded file type!');
					echo json_encode($msg);
					die();
				}
				
				//DELETE OLD FILE
				$this->deleteDirectory($f3->get('POST.url'));
		
				//RENAME FILE
				$f3->set('UPLOADS', $f3->get('UPLOADS') . $f3->get('POST.siteLongId') . '/');
				$studentName = str_replace('--', '-', $f3->get('SESSION.fname'). '-' . $f3->get('SESSION.lname'));
				//$studentName = str_replace(' ', '-', $studentName);
				//$newN = trim(strtolower($studentName)) . '-' . Web::instance()->slug($studentName . '-' . $f3->get('POST.name')) . '.' . pathinfo($_FILES['projectUpload']['name'], PATHINFO_EXTENSION);
				$newN = Web::instance()->slug($studentName . '-' . $f3->get('POST.name')) . '.' . pathinfo($_FILES['projectUpload']['name'], PATHINFO_EXTENSION);
				$_FILES['projectUpload']['name'] = $newN;
				$f3->set('POST.url', $f3->get('UPLOADS') . $newN);
			}
			
			date_default_timezone_set('Australia/Sydney');
			$date = date('Y-m-d H:i:s');
			$f3->set('POST.submitDate', $date);
			$this->proj->getByArray(array('siteLongId=? and studentEmail=?', $f3->get('POST.siteLongId'), $f3->get('SESSION.email')));
			
			if ($f3->get('POST.projLongId') != '' || !$this->proj->dry()){//EDIT
				$this->proj->edit('projLongId', $f3->get('POST.projLongId'));
				$msg = array('Success', 'Data successfully updated!', $f3->get('POST.url'));
				
			}else{//ADD
				$f3->set('POST.studentEmail', $f3->get('SESSION.email'));
				$f3->set('POST.projLongId', $this->genLongId());
				$this->proj->add();
				$msg = array('Success', 'Data successfully added!', $f3->get('POST.url'), $_FILES['projectUpload']['error']);
			}
			
			$this->processUpload($f3);
			$this->emailConfirmationProj($f3, $f3->get('SESSION.email'), $f3->get('SESSION.fname'), $f3->get('POST.name'));
			
		}else{
			$msg = array('Warning', 'Please fill up all empty fields!');
		}
		echo json_encode($msg);
		die();
	}
	
	private function processUpload($f3){
		$overwrite = true; // set to true, to overwrite an existing file; Default: false
		$slug = true; // rename file to filesystem-friendly version

		//Check iQQuizController.php for comments
		$a = Web::instance()->receive(function($file,$formFieldName){
			//if($file['size'] > (50 * 1024 * 1024)) // if bigger than 50 MB
				//return false; 

			return true;
			},
			$overwrite,
			$slug
		);
		
		$name = array_keys($a);
		//$name = pathinfo($name['0'], PATHINFO_BASENAME);
		return $name['0'];
	}
	
	private function emailConfirmationProj($f3, $toEmail, $toName, $projName){
		$sbj='Submission Confirmation | Thesis Management University of Sydney';
		$fr='no-reply@thesis.quantumfi.net';
		$frN='Thesis Manager Bot';
		$msg='Dear ' . $toName . ',<br><br>';
		$msg=$msg . 'This is an confirmation email to confirm that your submission of (' . $projName . ') has been received.<br><br>';
		$msg=$msg . 'Auto generated by <br>' . $frN;
		
		$cnt_json = json_encode(array('fr'=>$fr, 'to'=>$toEmail, 'subject'=>$sbj, 'message'=>$msg, 'frName'=>$frN));
		
		$email = new SendEmailController();
		$email->post_json($f3->get('CONFIG_qMAPI'), $cnt_json);
	}
}	