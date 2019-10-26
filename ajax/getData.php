<?php
	
	include_once '../loader.php';

	switch($_GET['sAction']) {
		case 'load_active_boards':
			$oDash = new Dashboard();
			$oGraph = new Graph();
			$aaDash = [];
			$aEmpties = [];
			if(isset($_GET['bExample'])) {
				Dashboard::testDisplayGraphs();
			}
			$aaBoards = $oDash->getAll([['iStatus',1]]);
			foreach($aaBoards as $aBoard){
				$oDash->iId = $aBoard[0];
				$aaGraphs = $oDash->getAllGraphs();
				if($aaGraphs)
					$aaDash[] = [$aBoard[0],$aBoard[2],$aBoard[3],$aaGraphs];
				else 
					$aEmpties[] =  [$aBoard[0],$aBoard[2],$aBoard[3]];
			} 
			$aaData['Dashboards'] = $aaDash;
			$aaData['Empties'] = $aEmpties;
			break;

		default:
				$aaData['sStatus'] = 'failure';
				$aaData['sMessage'] = 'Something Broke :P';
			break;
	}

			echo json_encode($aaData);



?>