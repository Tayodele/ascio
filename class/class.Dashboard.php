<?php

include_once '../loader.php';

class Dashboard extends Generic{

	public $iId;
	public $sName;
	public $sDescription;

	public $sTable = 'dashboards';
	public $sColumns = 'sName,sDescription';

	public function build(){
		return ['"'.$this->sName.'"','"'.$this->sDescription.'"'];
	}

	public function setVals($aRow){
		$this->iId = $aRow[0];
		$this->sStatus = $aRow[1];
		$this->sName = $aRow[2];
		$this->sDescription = $aRow[3];
	}

	public static function testDisplayGraphs() {
		//Create a Model with the data collected
		$oModelA = new Model();
		$oModelA->sType = 'example1';
		$oModelA->sName = 'Average GPA of students by Semester';
		$oModelB = new Model();
		$oModelB->sName = 'Number of students by Semester';
		$oModelB->sType = 'example2';
		if(sizeof($oModelA->getAll([['sName',$oModelA->sName]])) == 0){
			$oModelA->create();
			$oModelB->create();
		}
		$aaModels = $oModelA->getAll([['sName',$oModelA->sName]]);
		$oModelA->iId = $aaModels[0][0];
		$aaModels = $oModelA->getAll([['sName',$oModelB->sName]]);
		$oModelB->iId = $aaModels[0][0];
		//Assign Those Graphs to a Dashboard
		$oDash = new Dashboard();
		$oDash->sName = 'Example Dashboard';
		$aaDashes = $oDash->getAll([['iStatus',1],['sName',$oDash->sName]]);
		if(sizeof($aaDashes) == 0) {
			$oDash->create();	
			$aaDashes = $oDash->getAll([['iStatus',1],['sName',$oDash->sName]]);
		}
		//Create Graphs to represent those models
		$oGraphA = new Graph($oModelA->sName,'column','Semesters Enrolled','GPA',$oModelA,$aaDashes[0][0]);
		$oGraphB = new Graph($oModelB->sName,'line','Semesters Enrolled','Student Population',$oModelB,$aaDashes[0][0]);
		if(sizeof($oGraphA->getAll([['sName',$oModelA->sName]])) == 0){
			$oGraphA->create();
			$oGraphB->create();
		}
	}

	public function getAllGraphs(){
		$oGraph = new Graph();
		$aaGraphs = [];
		$aaGraphs = $oGraph->getAll([['iDashboard',$this->iId]]);
		$aaGraphs = Graph::fetchModels($aaGraphs);
		if(sizeof($aaGraphs) > 0) return $aaGraphs;
		else return false;
	}
}
?>