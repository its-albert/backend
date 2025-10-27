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
 if( $action == 'allTypes'){
    $getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
	efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, phone,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
	(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments FROM eventz where estatus = 1 ";

  }else if( $action == 'allTypesAdmin'){
    $getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
	efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, phone,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
	(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments FROM eventz";

  }
  //http://localhost/eventsonlineapp/events.php?action=oneTypeonly&eventTypes=Entertainment
	else if($action == 'oneTypeonly' ){
    $getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
	efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, phone,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
	(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
	(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments
    FROM eventz where estatus = 1 and etype = '".$eventType."'  ) ";

    //echo 'Nothing :-)';
  }
//	http://localhost/eventsonlineapp/events.php?action=oneTypeOneCategory&eventTypes=Entertainment&eventCategory=Clubs
	else if($action == 'oneTypeOneCategory'){

		$getData = " SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
		efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, phone,
		(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
		(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
		(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments
		FROM eventz where estatus = 1 and etype = '".$eventType."'  and ecategory = '".$eventCategory."' ";
//	http://localhost/eventsonlineapp/events.php?action=moderateEvents&eventTypes=Entertainment&eventCategory=Clubs
}else if( $action == 'moderateEvents'){
		$getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
		efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, phone,
		(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
		(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
		(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments 
			FROM eventz where estatus = 0 ";
	}

$sql= mysqli_query($con, $getData);

	while($rows = mysqli_fetch_array($sql)){

		//echo "This is nice";
		$events[] = array(
			"event_id" => $rows['event_id'],
			"ename" => mb_convert_encoding($rows['ename'], 'UTF-8', 'UTF-8'),
			"edetails" => mb_convert_encoding($rows['edetails'], 'UTF-8', 'UTF-8') ,
			"start_date" => mb_convert_encoding($rows['start_date'], 'UTF-8', 'UTF-8') ,
			"end_date" => mb_convert_encoding($rows['end_date'], 'UTF-8', 'UTF-8') ,
			"start_time" => mb_convert_encoding($rows['start_time'], 'UTF-8', 'UTF-8') ,
			"evenue" => mb_convert_encoding($rows['evenue'], 'UTF-8', 'UTF-8') ,
			"etype" => mb_convert_encoding( $rows['etype'], 'UTF-8', 'UTF-8'),
			"ecategory"=> mb_convert_encoding( $rows['ecategory'], 'UTF-8', 'UTF-8') ,
			"efee" => mb_convert_encoding(  $rows['efee'], 'UTF-8', 'UTF-8') ,
			"posted_by" =>  mb_convert_encoding(  $rows['posted_by'], 'UTF-8', 'UTF-8')  ,
			"posted_date" =>  mb_convert_encoding(  $rows['posted_date'], 'UTF-8', 'UTF-8')  ,
			"updated_on" => mb_convert_encoding(  $rows['updated_on'], 'UTF-8', 'UTF-8')  ,
			"ebarnar" => mb_convert_encoding(  $rows['ebarnar'], 'UTF-8', 'UTF-8') ,
			"ewebsite" =>  mb_convert_encoding(   $rows['ewebsite'], 'UTF-8', 'UTF-8') ,
			"estatus" => mb_convert_encoding(   $rows['estatus'], 'UTF-8', 'UTF-8') ,
			"user_id" => mb_convert_encoding(  $rows['user_id'], 'UTF-8', 'UTF-8') ,
			"curreny_type" => mb_convert_encoding( $rows['curreny_type'], 'UTF-8', 'UTF-8') ,
			"district" => mb_convert_encoding(  $rows['district'], 'UTF-8', 'UTF-8'),
			"likes" => mb_convert_encoding(  $rows['likes'], 'UTF-8', 'UTF-8') ,
			"dis_likes" => mb_convert_encoding( $rows['dis_likes'], 'UTF-8', 'UTF-8') ,
			"comments" => mb_convert_encoding(  $rows['comments'], 'UTF-8', 'UTF-8'),
			"phone" => mb_convert_encoding(  $rows['phone'], 'UTF-8', 'UTF-8')


		);
	}

if(isset($events)){
$abode_json_string = $events;
//echo json_encode($abode_json_string);

// echo json_encode($abode_json_string);


// $show_json = json_encode($events , JSON_FORCE_OBJECT);
$show_json = json_encode($events);

if ( json_last_error_msg()=="Malformed UTF-8 characters, possibly incorrectly encoded" ) {
	echo "Malformed UTF-8 characters, possibly incorrectly encoded";
    $show_json = json_encode($API_array, JSON_PARTIAL_OUTPUT_ON_ERROR );
}
if ( $show_json !== false ) {
    echo($show_json);
} else {
	  echo "die";
    die("json_encode fail: " . json_last_error_msg());
}


}else{
	echo "There are no events!";
}


}
	@mysqli_close($con)

?>
