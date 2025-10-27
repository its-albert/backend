<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Getl,;

// $something = $_GET['s'];
if(isset($_GET['action']))
{

	 $action = $_GET['action'];




//Do the selection for all records if action is
//http://localhost/eventsonlineapp/event_types.php?action=getTypes
  if( $action == 'getTypes'){
    $getData = "SELECT type_id, type FROM event_types";

  }


$sql= mysqli_query($con, $getData);
	while($rows = mysqli_fetch_array($sql)){
		//echo "This is nice";

		$events[] = array(
			"type_id" => $rows['type_id'],
			"type" => $rows['type'],
		);
	}

if(isset($events)){
$abode_json_string = $events;
//echo json_encode($abode_json_string);
echo json_encode($abode_json_string);
}else{
	echo "There are no such events!";
}


}


	@mysqli_close($con)

?>
