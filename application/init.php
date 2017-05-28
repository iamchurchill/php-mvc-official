<?php
	if(session_status() !== PHP_SESSION_ACTIVE){ session_start(); }
	date_default_timezone_set('Africa/Accra');
	$GLOBALS['config']  = array('mysql' => array('host' => 'localhost', 'username' => 'root', 'password' => '8554', 'db' => 'roots'), 'remember' => array('cookie_name'=>'COOKIEID', 'cookie_expiry'=>'604800'), 'session'=>array('session_name'=>'HSESSID', 'token_name'=>'token'), 'assets'=>array('location'=>'http://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', dirname(__DIR__) . '/public'))));
	spl_autoload_register(function($class){
        require_once 'core/' . $class . '.php';
    });
?>