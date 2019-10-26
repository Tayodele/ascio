<?php
	error_reporting(E_ALL);
	ini_set('display_errors','1');

	spl_autoload_register(function($sClass){
		if(file_exists('class.'.$sClass.'.php'))
			include_once 'class.'.$sClass.'.php';
		else
			include_once 'class/class.'.$sClass.'.php';
	});	
?>