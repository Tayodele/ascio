<?php
include_once '../loader.php';

	switch($_GET['sAction']){
		
		case 'add_dashboard':
			if(isset($_GET['sDashName']) && $_GET['sDashName']) {
				$oDash = new Dashboard();
				$oDash->sName = $_GET['sDashName'];
				$oDash->create();
				$aaData['sStatus'] = 'success';
			} else {
				$aaData['sStatus'] = 'failure';
			}
			break;

		case 'delete_dash':
			if(isset($_GET['iId']) && $_GET['iId']) {
				$oDash = new Dashboard();
				$oDash->iId = $_GET['iId'];
				$oDash->delete();
				$aaData['sStatus'] = 'success';
			} else {
				$aaData['sStatus'] = 'failure';
			}
			break;
			
		default:
			$aaData['sStatus'] = 'failure';
			$aaData['sMessage'] = 'Something Broke :P';
		break;
	}

?>