<h3>DBSCAN</h3>
<form action="" method="POST">
	<input type="text" name="eps" placeholder="Epsilon" />
	<input type="text" name="minPts" placeholder="Minimum Points" />
	<input type="submit" name="btn" value="DBSCAN"	/>
</form>
<p>
	DBSCAN clustering for 150 objects.
	Parameters: eps = 0.4, minPts = 4
	The clustering contains 4 cluster(s) and 25 noise points.<br />
	0  1  2  3  4<br /> 
	25 47 38 36  4 
</p>
<?php
function ImportCSV2Array($filename){
	$row = 0;
	$col = 0;
	$handle = @fopen($filename, "r");
	if ($handle) 
	{
		while (($row = fgetcsv($handle, 4096)) !== false) 
		{
			if (empty($fields)) 
			{
				$fields = $row;
				continue;
			}

			foreach ($row as $k=>$value) 
			{
				$results[$col][$fields[$k]] = $value;
			}
			$col++;
			unset($row);
		}
		if (!feof($handle)) 
		{
			echo "Error: unexpected fgets() failn";
		}
		fclose($handle);
	}

	return $results;
}
//+++++++++++++++Input Data 
if(@$_POST['btn'] == "DBSCAN"){
	@$eps=$_POST['eps'];
	@$minPts=$_POST['minPts'];
}
/*++++++++++++++++*/
$filename = "iris_data.csv";
$csvArray = ImportCSV2Array($filename);
$i=0;$CLUSTER=0;$clstrCnt=0;$next=0;$NOISE=0;$clstValid=0;
$slAr = array();$swAr = array();$plAr = array();$pwAr = array();
$type=array();
foreach ($csvArray as $row){
	$slAr[$i] = $row['sepal_length'];
	$swAr[$i] = $row['sepal_width'];
	$plAr[$i] = $row['petal_length'];
	$pwAr[$i] = $row['petal_width'];
	$type[$i] = $row['species'];
	$cluster[$i] = $row['cluster'];
	$i++;
}
echo "<strong>For Epsilon: </strong>".@$eps."<strong> and Minimum Points: </strong>". @$minPts."<br />";
for($n=0;$n<150;$n++){
	if($cluster[$n]==0){
		$CLUSTER++;
		for($m=$n;$m<150;$m++){
			@$distVal= sqrt(pow(($slAr[$n]-$slAr[$m]),2)+pow(($swAr[$n]-$swAr[$m]),2)+pow(($plAr[$n]-$plAr[$m]),2)+pow(($pwAr[$n]-$pwAr[$m]),2));
			//echo "Distance: ".$distVal."<br />";
			if($distVal<=@$eps){
				$clstrCnt++;
				continue;
			}
			if($clstrCnt<@$minPts){
				$cluster[$m] = 0;
			}else{
				$cluster[$m] += $CLUSTER;
				//continue;
			}		
		}
		if($clstrCnt>=@$minPts){
			echo "<strong>For CLUSTER: </strong>".$CLUSTER ." <strong>Count is: </strong>". $clstrCnt. "<br />";
		}
		$clstrCnt=0;
		$CLUSTER=0;
	}
}
?>
