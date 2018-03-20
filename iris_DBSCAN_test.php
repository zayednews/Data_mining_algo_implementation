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

$filename = "iris_data.csv";
$csvArray = ImportCSV2Array($filename);
$col=0;$i=0;
$slAr = array();$swAr = array();$plAr = array();$pwAr = array();
$type=array();
$data=array();
$visited=array();
$neighPts=array();
$clusters=array();
foreach ($csvArray as $row){
	$data[$i][$col] = $row['index'];
	$data[$i][$col+1] = $row['sepal_length'];
	$data[$i][$col+2] = $row['sepal_width'];
	$data[$i][$col+3] = $row['petal_length'];
	$data[$i][$col+4] = $row['petal_width'];
	$data[$i][$col+5] = $row['species'];
	$data[$i][$col+6] = $row['cluster'];
	$i++;
}
/*echo "<table border='1' style='text-align: left'>
<tr>
<th colspan='7'> Data  </th>
</tr>";
for($n=0;$n<150;$n++){
echo "<tr>";
for($m=0;$m<7;$m++){
echo "<td>".$data[$n][$m]."</td>";
}
echo "</tr>";
} 
echo "</table>";*/
/*+++++++++++++++++++ Region Query Function +++++++++*/
function regionQuery($start, $index,$data){
	$count = 0;$eps=0.4;
	for($i = 0; $i < 150; $i++){
		if($i != $index){
			$distance = 0;
			for($j = 0; $j < 4; $j++){
				@$distance = sqrt(pow(($data[$i][$j+1]-$data[$index][$j+1]),2)+pow(($data[$i][$j+2]-$data[$index][$j+2]),2)+pow(($data[$i][$j+3]-$data[$index][$j+3]),2)+pow(($data[$i][$j+4]-$data[$index][$j+4]),2));
				//echo "Distance: ".$distance."<br />";
				if($distance <= $eps){
					$neighPts[$start+$count] = $i;
					$count++;
				}
			}
		}
	}
	//echo "Count Regional Query: ". @$count ."<br />";
	return $count;
}
/*+++++++++++++++++ Expand Cluster Function +++++++++++++*/
function expandCluster($clusterNo, $numNPts, $index, $data){
	$clusters[$index] = $clusterNo;
	$count = 0;$minPts=4; $eps=0.4;
	for($i = 0; $i < $numNPts; $i++){
		if(!@$visited[@$neighPts[$i]]){
			@$visited[@$neighPts[$i]] = 1;
			$count = regionQuery(@$numNPts, @$neighPts[$i],$data);
			//echo "Count Expand Cluster: ". @$count ."<br />";
			if($count >= @$minPts){
				$numNPts += $count;
			}
		}
		if(!@$clusters[@$neighPts[$i]]){
			$clusters[@$neighPts[$i]] = $clusterNo;
		}
	}
}

//++++++++++++++++ DBSCAN Main Functional Code +++++++++++++++
$minPts=4; $eps=0.4;
$nextCluster = 1;
for($i=0;$i<150;$i++){
	if(!@$visited[$i]){
		$visited[$i]=1;
		@$numPoints = regionQuery(0, $i,$data); 
		//echo "Count Num Points in Main: ". @$numPoints ."<br />";
		if(@$numPoints >= @$minPts){ 
			expandCluster($nextCluster,$numPoints,$i, $data); // Expand Clutering Count
			$nextCluster++;
		}
	}
}

/*++++++++++ Cluster Data Print +++++++++++++*/
for($x = 0; $x < 150; $x++){
	//echo "Cluster: ". @$clusters[$x] ."<br />";
	//echo "Visited: ". @$visited[$x]. " Cluster [x]: ". @$clusters[$x]. " Neighbour Points: ". @$neighPts[$x]."<br/>";
}
?>