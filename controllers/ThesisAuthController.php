<?php
require 'SendEmailController.php';

class ThesisAuthController extends ThesisController{
	
	function login($f3){
		if ($f3->exists('POST.cred') && $f3->exists('POST.password') && $f3->exists('POST.position')){
			$cred = $f3->get('POST.cred');
			
			$result = $this->users->getByArray(array('email=? and position=?', $cred, $f3->get('POST.position')));
				
			$validPass = 0;
			
			if (count($result) > 0){
				$crypt = \Bcrypt::instance();
				$validPass = $crypt->verify($f3->get('POST.password'), $result[0]['password']);
			}
			
			if ($validPass){
				$id = $result[0]['id'];
				//$userLongId = $result[0]['userLongId'];
				$position = $result[0]['position'];
				$email = $result[0]['email'];
				$fname = $result[0]['fname'];
				$lname = $result[0]['lname'];
				$approved = $result[0]['approved'];
				
				$f3->set('SESSION.user', $cred);/*email or id*/
				//$f3->set('SESSION.userLongId', $userLongId);
				$f3->set('SESSION.email', $email);
				$f3->set('SESSION.fname', $fname);
				$f3->set('SESSION.lname', $lname);
				$f3->set('SESSION.position', $position);
				
				if ($position == 'admin'){
					if ($approved != '1')
						$f3->set('warn_message', 'Waiting approval from calvin (calvin.chiew@sydney.edu.au)');
					
					else
						$f3->reroute('/admin');
				
				}elseif ($position == 'coordinator')
					$f3->reroute('/coordinator');
					
				elseif ($position == 'assessor')
					$f3->reroute('/assessor');
					
				elseif ($position == 'student')
					$f3->reroute('/student');
				
			}else{
				$f3->set('err_message', 'Invalid access credential. please try again!');
			}
		}
		$f3->set('inc', 'z_login.htm');
	}
	
	function logout($f3){
		$f3->clear('SESSION');
		$f3->clear('COOKIE');
		
		$f3->set('SESSION.user',null);
		//$f3->set('SESSION.userLongId',null);
		$f3->set('SESSION.fname',null);
		$f3->set('SESSION.lname',null);
		$f3->set('SESSION.position',null);
		
		$f3->reroute('/');
	}
	
	function adminRegister($f3){
	
		$f3->set('POST.position', 'admin');
		
		$this->register($f3);
		
		$f3->set('inc', 'z_register.htm');
	}
	
	function register($f3){
		if ($f3->exists('POST.id') && 
			$f3->exists('POST.fname') &&
			$f3->exists('POST.lname') &&
			$f3->exists('POST.email') &&
			$f3->exists('POST.password') &&
			$f3->exists('POST.confirmPassword')
		){
			$id = $f3->get('POST.id');
			
			$result = $this->users->getByArray(array('id=?', $id));
			
			if (count($result) > 0){
				$f3->set('err_message', 'Your Unikey has been registered!');
				
			}elseif ($f3->get('POST.password') != $f3->get('POST.confirmPassword')){
				$f3->set('err_message', 'Password and confirm password are not same!');
				
			}else{
				$crypt = \Bcrypt::instance();
				
				$f3->set('POST.password', $crypt->hash($f3->get('POST.password')));
				$f3->set('POST.userLongId', $this->genLongId());
				$this->users->add();
				
				$f3->set('SESSION.user', $id);
				//$f3->set('SESSION.userLongId', $this->users->userLongId);
				$f3->set('SESSION.fname', $this->users->fname);
				$f3->set('SESSION.lname', $this->users->lname);
				$f3->set('SESSION.email', $this->users->email);
				$f3->set('SESSION.position', $this->users->position);
				
				if ($this->users->approved != '1')
					$f3->set('warn_message', 'Your registration has been received! Waiting approval from calvin (calvin.chiew@sydney.edu.au)');//CALVIN MUST SET THE POSITION TO 'admin'
				
				$this->alertRegistration($f3, $f3->get('POST.email'), $f3->get('POST.fname'), $f3->get('POST.lname'));
				
				/*
				elseif ($this->users->position == 'admin')
					$f3->reroute('/admin');
				
				elseif ($this->users->position == 'coordinator')
					$f3->reroute('/coordinator');
					
				elseif ($this->users->position == 'assessor')
					$f3->reroute('/assessor');
					
				elseif ($this->users->position == 'student')
					$f3->reroute('/student');
				*/
			}
		}
		
		$f3->set('inc', 'z_register.htm');
	}
	
	private function alertRegistration($f3, $email, $fname, $lname){
		$sbj='Registration Alert | Thesis Management University Of Sydney';
		$fr='no-reply@thesis.quantumfi.com.au';
		$to='calvin.chiew@sydney.edu.au';
		$msg=$fname . ' ' . $lname . '(' . $email . ') has registered as admin and required your approval asap!';
		$frN='Quantumfi Bot';
		
		$cnt_json = json_encode(array('fr'=>$fr, 'to'=>$to, 'subject'=>$sbj, 'message'=>$msg, 'frName'=>$frN));
		
		$email = new SendEmailController();
		$email->post_json($f3->get('CONFIG_qMAPI'), $cnt_json);
		
	}
}