<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *");  
// $something = $_GET['s'];
if(isset($_GET['action']) || isset($_POST['action']))
{//Code section for getting comments

  //$response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);
//http://localhost/hansu/eventsonlineapp/comments.php?action=comments
  if($_GET['action'] == "comments"){
    //String to time
    $yesterdayz = strtotime("yesterday");
    $yesterday = date("Y-m-d", $yesterdayz);
    //Select statement
    // $getComments = "SELECT comment_id, comments.user_id, event_id, user_comment, comment_date, name FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE event_end_date >= '".$yesterday."' ";
    $getComments = "SELECT comment_id, comments.user_id, event_id, user_comment, comment_date, name FROM comments INNER JOIN users ON comments.user_id = users.user_id ";
    
    if(isset($_GET['event_id'])){
      $event_id = $_GET['event_id'];
      $getComments = "SELECT comment_id, comments.user_id, event_id, user_comment, comment_date, name FROM comments INNER JOIN users ON comments.user_id = users.user_id where comments.event_id = $event_id";
        }

    $sql= mysqli_query($con, $getComments);


        while( $rows = mysqli_fetch_array($sql)){
      $comments[] = array(
        "comment_id" => $rows['comment_id'],
        "user_id" => $rows['user_id'],
        "event_id" => $rows['event_id'],
        "user_comment" => $rows['user_comment'],
        "comment_date" => $rows['comment_date'],
        "name" => $rows['name']
      );

    }

    if(isset($comments)){
      echo json_encode($comments);
    }else{
      echo 'No comments';
    }

    //code section for making a comment
    //http://localhost/hansu/eventsonlineapp/comments.php?action=comment&user_id=1&event_id=11&user_comment=All%20is%20nice%20here&event_end_date=2016-12-20
  }else if($_POST['action'] == "comment"){
    //Variable to keep in db
    $user_id = '';
    $event_id = '';
    $comment = '';
    $event_end_date = '';
    $date_time = '';
    $user_comment = '';
    if(isset($_POST['user_id'])){
      $user_id = $_POST['user_id'];
    }

    if(isset($_POST['event_id'])){
      $event_id = $_POST['event_id'];
    }

    if(isset($_POST['comment_date'])){
      $comment_date = $_POST['comment_date'];
    }

    if(isset($_POST['event_end_date'])){
      $event_end_date = $_POST['event_end_date'];
    }

    if(isset($_POST['user_comment'])){
      $user_comment = $_POST['user_comment'];
    }

    $comment_date = date("Y-m-d");


    $insertData = "INSERT INTO comments(user_id,event_id, user_comment, comment_date,event_end_date)VALUES('".$user_id."','".$event_id."','".$user_comment."','".$comment_date."','".$event_end_date."' )";
    if($con->query($insertData) === TRUE){
      // Return response
      $response["success"] = 1;
      $response["error_msg"] = "commented!";

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
  }

  $getComments = "SELECT comment_id, comments.user_id, event_id, user_comment, comment_date, name FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE event_id = $event_id";

  $sql= mysqli_query($con, $getComments);


  while( $rows = mysqli_fetch_array($sql)){
    $comments[] = array(
      "comment_id" => $rows['comment_id'],
      "user_id" => $rows['user_id'],
      "event_id" => $rows['event_id'],
      "user_comment" => $rows['user_comment'],
      "comment_date" => $rows['comment_date'],
      "name" => $rows['name']
    );

  }

  if(isset($comments)){
    $response['comments'] = $comments;
  }



      echo json_encode($response);

    }else{

      $response["success"] = 0;
      $response["error_msg"] = "No comment made";
      echo json_encode($respons  e);
    }


  }//End of code secton for making a comment



}


@mysqli_close($con)
?>
