<?php 
include_once '../loader.php';


	switch($_GET['sAction']){
			default:
				$aaData['sStatus'] = 'failure';
				$aaData['sMessage'] = 'Something Broke :P';
			break;
		}

?>