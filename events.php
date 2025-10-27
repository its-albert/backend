<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

// $something = $_GET['s'];
if(isset($_GET['action']))
{

	 $action = $_GET['action'];
  $eventType = '';
  $eventCategory = '';
  if(isset($_GET['eventTypes'])){
    $eventType = $_GET['eventTypes'];
  }
  if(isset($_GET['eventCategory'])){
    $eventCategory = $_GET['eventCategory'];
  }
//yesterday's date
//$yesterday = date("Y/m/d")  ;
$yesterdayz = strtotime("yesterday");
$yesterday = date("Y-m-d", $yesterdayz);


//Do the selection for all records if action is
//http://localhost/eventsonlineapp/events.php?action=allTypes
if (1==1) {
	$getData = "SELECT * FROM eventz";
}else
if( $action == 'allTypes'){
    $getData = "SELECT event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
		  efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, user_id FROM eventz where estatus = 1 and start_date >= '".$yesterday."' ";

  } //http://localhost/eventsonlineapp/events.php?action=oneTypeonly&eventTypes=Entertainment
	else if($action == 'oneTypeonly' ){
    $getData = "SELECT event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
		 efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, user_id
    FROM eventz where estatus = 1 and etype = '".$eventType."' and start_date >= '".$yesterday."' ";

    //echo 'Nothing :-)';
  }
//	http://localhost/eventsonlineapp/events.php?action=oneTypeOneCategory&eventTypes=Entertainment&eventCategory=Clubs
	else if($action == 'oneTypeOneCategory'){

		$getData = " SELECT event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
		 efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, user_id
		FROM eventz where estatus = 1 and etype = '".$eventType."'  and ecategory = '".$eventCategory."' and start_date >= '".$yesterday."' ";
//	http://localhost/eventsonlineapp/events.php?action=moderateEvents&eventTypes=Entertainment&eventCategory=Clubs
}else if( $action == 'moderateEvents'){
		$getData = "SELECT event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
			efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, user_id FROM eventz where estatus = 0 and posted_date >= '".$yesterday."'";
	}

$sql= mysqli_query($con, $getData);
	while($rows = mysqli_fetch_array($sql)){

		//echo "This is nice";
		$events[] = array(
			"event_id" => $rows['event_id'],
			"ename" => $rows['ename'],
			"edetails" => $rows['edetails'],
			"start_date" => $rows['start_date'],
			"end_date" => $rows['end_date'],
			"start_time" => $rows['start_time'],
			"evenue" => $rows['evenue'],
			"etype" => $rows['etype'],
			"ecategory"=> $rows['ecategory'],
			"efee" => $rows['efee'],
			"posted_by" =>  $rows['posted_by'],
			"posted_date" =>  $rows['posted_date'],
			"updated_on" => $rows['updated_on'],
			"ebarnar" => $rows['ebarnar'],
			"ewebsite" =>  $rows['ewebsite'],
			"estatus" => $rows['estatus'],
			"user_id" => $rows['user_id']
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
