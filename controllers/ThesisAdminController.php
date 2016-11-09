<?php
class ThesisAdminController extends ThesisController
{	
	function afterroute($f3) { 
		if (!$f3->exists('SESSION.user') || $f3->get('SESSION.user') == '' || $f3->get('SESSION.position') != 'admin'){
			$f3->reroute('/login');
		}
		
		echo Template::instance()->render('z_layoutAccount.htm');
    }

	function defaultRedirect($f3){
		$f3->reroute('/admin');
	}
	
	function index($f3){
		//GET COORDINATOR LIST
		$coor = array();
		$coordinators = $this->users->getById('position', 'coordinator');
		foreach ($coordinators as $val ){
			$coor[$val['email']] = $val['fname'] . ' ' . $val['lname'];
		}
		
		//GET SITE LIST
		$mappedResult = array();
		$sites = $this->site->all();
		foreach ($sites as $val){
			if ($coor[$val['coordinatorEmail']] != '')
				array_push($mappedResult, array('name'=>$val['name'], 'status'=>$val['status'], 'username'=>$coor[$val['coordinatorEmail']]));
		}
		
		$f3->set('results', $mappedResult);
		//$f3->set('coordinator', $results);
		
		$f3->set('navTab', 'home');
		$f3->set('inc', 'admin_dashboard.htm');
	}
	
	function coordinatorList(){
		function mapCoordinator($val){
			return array('userLongId'=>$val['userLongId'], 'fname'=>$val['fname'], 'lname'=>$val['lname'],  'email'=>$val['email']);
		}
		$coordinators = $this->users->getById('position', 'coordinator');
		
		$mappedResult = array_map('mapCoordinator', $coordinators);
		//var_dump($mappedResult);
		echo json_encode($mappedResult);
		
		die();
	}
	
	function coordinatorAddEdit($f3){
		//$data = json_decode($f3->get('BODY'),true);
		//var_dump($f3->get('POST.email'));
		//var_dump($f3->get('POST.id'));
		if (!$f3->exists('POST.userLongId') || $f3->get('POST.userLongId') == ''){
			$this->users->getByArray(array('email=? and position=?', $f3->get('POST.email'), 'coordinator'));
			
			if ($this->users->dry()){
				if ($f3->exists('POST.fname') && $f3->exists('POST.lname') && $f3->exists('POST.email')){
					$crypt = \Bcrypt::instance();
					
					$f3->set('POST.password', $crypt->hash($f3->get('POST.email')));
					$f3->set('POST.userLongId', $this->genLongId());
					$f3->set('POST.position', 'coordinator');
					
					$this->users->add();
				}else{
					echo json_encode(array('fail', 'Please fill up all the fields!'));
					die();
				}
			}else{
				echo json_encode(array('fail', 'Unikey already exists!'));
				die();
			}
		}else{
			$this->users->editByArray(array('email=? and position=?', $f3->get('POST.email'),'coordinator'));
		}
		$this->coordinatorList();
	}
	
	function coordinatorDelete($f3){
		$this->users->delete('userLongId', $f3->get('POST.userLongId'));
		
		$this->coordinatorList();			
	}
}	