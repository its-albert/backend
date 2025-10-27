<?php
//error_reporting(0);
include_once('Connections/conn.php');
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *");  

if(isset($_GET['action']) && $_GET['action'] == "likes")
{

//http://localhost/hansu/eventsonlineapp/likes.php?action=likes
  
    $user_id = $_GET["user_id"];
    $getLikes = "SELECT * FROM event_likes WHERE user_id = $user_id ";

    
    $sql= mysqli_query($con, $getLikes);


    while( $rows = mysqli_fetch_array($sql)){
      $likes[] = array(
        "like_id" => $rows['like_id'],
        "user_id" => $rows['user_id'],
        "event_id" => $   rows['event_id'],
        "event_id" => $rows['event_id'],
        "event_like_status" => $rows['event_like_status'],
        "like_date" => $rows['like_date'],
        "event_end_date" => $rows['event_end_date']

      );

    }

    if(isset($likes)){
      echo json_encode($likes);
    }else{
      echo 'No Likes';
    }

}elseif (isset($_POST['action']) && $_POST['action'] == "like") {
    $user_id = '';
    $event_id = '';
    $event_like_status = '';
    $event_end_date = '';
    $like_date = '';
    $event_end_date = '';

    if(isset($_POST['user_id'])){
      $user_id = $_POST['user_id'];
    }

    if(isset($_POST['event_id'])){
      $event_id = $_POST['event_id'];
    }

    if(isset($_POST['event_like_status'])){
      $event_like_status = $_POST['event_like_status'];
    }

    if(isset($_POST['event_end_date'])){
      $event_end_date = $_POST['event_end_date'];
    }

    $like_date = date("Y-m-d H:i:s");

    $insertData = "INSERT INTO event_likes(user_id, event_id, event_like_status, like_date, event_end_date)VALUES
    ('".$user_id."','".$event_id."','".$event_like_status."','".$like_date."','".$event_end_date."' )
     ON DUPLICATE KEY UPDATE event_like_status = '".$event_like_status."' , like_date = '".$like_date.  "'   ";
    if($con->query($insertData) === TRUE){
      // Return response

      //================++++++++++++++++++==============
//String to time
      $yesterdayz = strtotime("yesterday");
      $yesterday = date("Y-m-d", $yesterdayz);

    $getComments = "SELECT eventz.event_id,
    (SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
    (SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
    (SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments FROM eventz where event_id = $event_id";
      $sql= mysqli_query($con, $getComments);

      while( $rows = mysqli_fetch_array($sql)){
        $comments[] = array(

          "event_id" => $rows['event_id'],
          "likes" => $rows['likes'],
          "dis_like" => $rows['dis_likes'],
          "comments" => $rows['comments'],
          "event_end_date" => $rows['event_end_date']

        );
      }

      $response["success"] = 1;
      $response["error_msg"] = "liked!";
      $response["updated_data"] = $comments;

//====================== Get Event just liked ===============
$getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
efee,posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, end_date, 
(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 1 and event_id = eventz.event_id ) AS likes,
(SELECT COUNT(event_likes.event_like_status) from event_likes where event_like_status = 2 and event_id = eventz.event_id ) AS dis_likes,
(SELECT COUNT(comments.user_comment) from comments where comments.event_id = eventz.event_id) AS comments FROM eventz where event_id = $event_id ";

$sql= mysqli_query($con, $getData);

	// while(){
    $rows = mysqli_fetch_array($sql);
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
			"comments" => mb_convert_encoding(  $rows['comments'], 'UTF-8', 'UTF-8')


		);
	// }

  if(isset($events)){
    $response["event"] = $events;
  }else{
  $response["event"] = '{}';
  }
  
  echo json_encode($response);
}else{
  $error = mysqli_error($con);
  echo $error;
  $response["success"] = 0;
  $response["error_msg"] = "No like status change";
  echo json_encode($response);
}
}else{
  echo 'No action is not set';
}

@mysqli_close($con)
?>
