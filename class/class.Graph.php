<?php
include_once '../loader.php';

class Graph extends Generic{

	public $iId;
	public $sName;
	public $sType;
	public $sXName;
	public $sYName;
	public $iDashboard;
	public $oModel;

	public $sTable = 'graphs';
	public $sColumns = 'sName,sType,sXName,sYName,iModel,iDashboard';

	public function setVals($aRow){
		$this->iId = $aRow[0];
		$this->sName = $aRow[1];
		$this->sType = $aRow[2];
		$this->sXName = $aRow[3];
		$this->sYName = $aRow[4];
		$oModel = new Model();
		$oModel->iId = $aRow[5];
		$oModel->get();
		$this->oModel = $oModel;
		$oModel->iDashboard = $aRow[6];

	}

	public function __construct($sname = null, $stype = null, $sxname = null, $syname = null, $omodel = null, $idash = null){
		$this->sName = $sname;
		$this->sType = $stype;
		$this->sXName = $sxname;
		$this->sYName = $syname;
		$this->oModel = $omodel;
		$this->iDashboard = $idash;
	}

	public function build(){
		return ['"'.$this->sName.'"','"'.$this->sType.'"','"'.$this->sXName.'"','"'.$this->sYName.'"','"'.$this->oModel->iId.'"','"'.$this->iDashboard.'"'];
	}

	public function fetchModel($iModel){
		$oModel = new Model();
		$oModel->iId = $iModel;
		$oModel->get();
		$this->oModel = $oModel;
	}

	public static function fetchModels($aaGraphs) {
		foreach ($aaGraphs as $key => $aGraph) {
			$oModel = new Model();
			$oModel->iId = $aGraph[5];
			$oModel->get();
			$oModel->generateModel();
			$aaGraphs[$key][5] = json_encode($oModel);
		}
		return $aaGraphs;

	}

}

?>