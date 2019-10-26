<head>
</head>
<p>here</p>
<?php 
require_once 'class/class.Generic.php';
error_reporting(E_ERROR);
ini_set('display_errors','1');

	$file = fopen('data/samplesteel1.csv','r');


	$oDb = Generic::dbConnect();
	$aLine = fgetcsv($file);
	$sSql = 'CREATE TABLE samplesteel1 (';
	foreach($aLine as $key => $Column) {
		if(is_numeric($Column) && $Column != 'Date')
			$sSql .= '`'.$Column.'` decimal(20,7),';
		else
			$sSql .= '`'.$Column.'` varchar(40),';
	}
	$sSql = substr($sSql,0,-1).'); ';
	echo $sSql;
	echo '<br/>';
	if(!$oDb->query($sSql)) {
		$oDb->close();
		die('bad query1');
	}

	while(($aLine = fgetcsv($file)) != false) {
		$sSql = 'INSERT INTO `samplesteel1` VALUES(';
		foreach ($aLine as $key => $val) {
			if(is_numeric($val))
				$sSql .= $val.', ';
			else 
				$sSql .= "'".$val."', ";
		}
		$sSql = substr($sSql,0,-2).'); ';
		echo $sSql;
		echo '<br/>';
		if(!$oDb->query($sSql)){
			$oDb->close();
			die('bad query2');
		}
	}

	echo "Done";
	fclose($file);
	$oDb->close();

?>