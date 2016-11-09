<?php
$f3=require_once '../../f3_lib_3.5/base.php';

$f3->config('config_prod.ini.php');
$f3->config('routes.ini.php');

/*600 seconds = 10min refresh cache*/
//$f3->route('GET /','PrepsController->index',600);

$f3->run();