<?php
include_once '../loader.php';

class Generic {

	private function connect(){
		$mysqli = new mysqli("mysql.cnsp.iit.edu","tayodele","","tayodele"); // I'm not quite dumb enough to upload my db passwd :P
		if ($mysqli->connect_errno) {
    		printf("Connect failed: %s\n", $mysqli->connect_error);
		echo 'bad q0';
		die();
    	}
    	return $mysqli;

	}

	public static function dbConnect(){
		$oDb = new Generic();
		return $oDb->connect();
	}

	public function create(){
		$oDb = $this->connect();
		$aVals = $this->build();
		$sSql = 'INSERT INTO `'.$this->sTable.'`('.$this->sColumns.') VALUES('.implode(",",$aVals).');';
		if($oDb->query($sSql)){
			$oDb->close();
			return true;
		}
		echo 'bad q1';
	}

	public function update(){
		$oDb = $this->connect();
		$aVals = $this->build();
		$aSet = explode(",",$this->sColumns);
		$sSet = '';
		foreach ($aSet as $key => $column) {
			$sSet .= $column.' = '.$aVals[$key].',';
		}
		$sSet = substr($sSet,0,-1);
		$sSql = 'UPDATE `'.$this->sTable.'` SET '.$sSet.' WHERE iId = '.$this->iId;
		if($oDb->query($sSql)){
			$oDb->close();
			return true;
		}
		$oDb->close();
		echo 'bad q2';
		die();

	}

	public function delete(){
		$oDb = $this->connect();
		$sSql = 'UPDATE `'.$this->sTable.'` SET iStatus = -1 WHERE iId = '.$this->iId;
		if($oDb->query($sSql)){
			$oDb->close();
			return true;
		}
		$oDb->close();
		echo 'bad q2_2';
		die();

	}

	public function get(){
		$oDb = $this->connect();
		$sSql = 'SELECT * FROM `'.$this->sTable.'` WHERE iId = '. $this->iId;
		if($rSql = $oDb->query($sSql)){
			while($row = $rSql->fetch_row()) {
				$this->setVals($row);
			}
			$oDb->close();
			return true;
		}
		$oDb->close();
		echo 'bad q3';
		die();


	}

	public function getAll($aParams){
		$oDb = $this->connect();
		$where = '';
		$aaData = [];
		foreach($aParams as $key => $sParam){
			if(is_int($sParam[1])) {
				$where .= $sParam[0].' = '.$sParam[1].' AND ';
			} else
				$where .= $sParam[0].' = "'.$sParam[1].'" AND ';
		}
		$where = substr($where,0,-5);
		$sSql = 'SELECT * FROM `'.$this->sTable.'` WHERE '.$where;
		if($rsSql = $oDb->query($sSql)){
			while($row = $rsSql->fetch_row())
				$aaData[] = $row;
			$oDb->close();
			return $aaData;
		}
		$oDb->close();
		echo 'bad q4';
		die();
	}
}

