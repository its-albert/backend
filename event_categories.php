<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *");
 
// $something = $_GET['s'];
if(isset($_GET['action']))
{

	 $action = $_GET['action'];



//Do the selection for all records if action is
//http://localhost/eventsonlineapp/event_categories.php?action=getCategories
  if( $action == 'getCategories'){
    $getData = "SELECT category_id, category, type_id FROM category";


$sql= mysqli_query($con, $getData);
	while($rows = mysqli_fetch_array($sql)){
		//echo "This is nice";

		$events[] = array(
			"category_id" => $rows['category_id'],
			"category" => $rows['category'],
			"type_id" => $rows['type_id']
		);
	}

if(isset($events)){
$abode_json_string = $events;
//echo json_encode($abode_json_string);
echo json_encode($abode_json_string);
}else{
	echo "There are no such events!";
}

}else{

	echo '';
}
}

	@mysqli_close($con)

?>
