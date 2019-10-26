<?php
include_once '../loader.php';

class Model extends Generic{
	public $iId;
	public $sName;
	public $sType;
	public $sData;
	public $aaData;

	public $sTable = 'models';
	public $sColumns = 'sName,sData,sType';

	public function build(){
		return ['"'.$this->sName.'"','"'.$this->sType.'"','"'.$this->sData.'"'];
	}

	public function setVals($aRow){
		$this->iId = $aRow[0];
		$this->sName = $aRow[1];
		$this->sData = $aRow[2];
		$this->sType = $aRow[3];
	}

	public function generateModel(){
		$oDb = $this->dbConnect();
		switch($this->sType) {
			case 'example1':
				$this->sData = 'example1';
				$this->aaData = Model::exampleData(1);
				break;
			case 'example2':
				$this->sData = 'example2';
				$this->aaData = Model::exampleData(2);
				break;
			case 'failure_cycles':
				$aaData = [];
				$sSql = "SELECT * FROM `".$this->sData."`";
				if($rsSql = $oDb->query($sSql)){
					while($aRow = $rsSql->fetch_row()) {
						$aaData[] = [(float)$aRow[10],(float)$aRow[4]];
					}
					$this->aaData[] = $aaData;
				} else {
					$oDb->close();
					echo 'bad query on '.$this->sData;
				}

				break;
			case 'log_failure_cycles':
				$aaData = [];
				$sSql = "SELECT * FROM `".$this->sData."`";
				if($rsSql = $oDb->query($sSql)){
					while($aRow = $rsSql->fetch_row()) {
						$aaData[] = [log10($aRow[10]),(float)$aRow[4]];
					}
					$this->aaData[] = $aaData;
				} else {
					$oDb->close();
					echo 'bad query on '.$this->sData;
				}
				break;
			case 'log_stress':
				$aaData = [];
				$sSql = "SELECT * FROM `".$this->sData."`";
				if($rsSql = $oDb->query($sSql)){
					while($aRow = $rsSql->fetch_row()) {
						$aaData[] = [log10($aRow[10]),log10($aRow[4])];
					}
					$this->aaData[] = $aaData;
				} else {
					$oDb->close();
					echo 'bad query on '.$this->sData;
				}
				break;
			default:
				break;
		}
	}

	public static function exampleData($iData){
		//pseudo data collection
		$aTestSemester =  [1,2,3,4,5,6,7,8];
		$aaTestGpa = [];
		$aaTestStudents = [];
		$iStartingStudents = 10000;
		$iCurrentStudents = $iStartingStudents;
		foreach($aTestSemester as $iSemester) {
			$iSumGpa = 0;
			$iStartingStudents = $iCurrentStudents;
			for($i = 0; $i < $iStartingStudents; $i++) {
				$iRandGpa = rand(0,40)/10;
				$iSumGpa += $iRandGpa;
				if($iRandGpa < 2) $iCurrentStudents--;
			}
			$iAvgGpa = $iSumGpa/$iStartingStudents;
			$aaTestGpa[] = [$iSemester,$iAvgGpa];
			$aaTestStudents[] = [$iSemester,$iCurrentStudents];
		}
		if($iData == 1) return $aaTestGpa;
		else return $aaTestStudents;
	}
}