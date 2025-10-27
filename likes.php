<?php
header('Content-Type: application/json; charset=utf-8');
if ($eventRow) {
    // adapt keys to the JSON shape your client expects
    echo json_encode([
        "success" => 1,
        "event" => [$eventRow] // client expects an array with event[0]
    ]);
} else {
    echo json_encode([
        "success" => 1,
        "event" => null
    ]);
}
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value  
// $something = $_GET['s'];
if(isset($_GET['action']))
{//Code section for getting comments

  //$response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);
//http://localhost/hansu/eventsonlineapp/likes.php?action=likes
  if($_GET['action'] == "likes"){
    //String to time
    $yesterdayz = strtotime("yesterday");
    $yesterday = date("Y-m-d", $yesterdayz);
    //Select statement
  //  $getComments = "SELECT like_id, user_id, event_id, event_like_status, like_date, event_end_date FROM event_likes WHERE event_end_date >= '".$yesterday."' ";
  $getComments = "CALL sp_get_like_datails()";
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

    if(isset($comments)){
      echo json_encode($comments);
    }else{
      echo 'No like made';
    }

    //code section for making a like or dislike
    //http://localhost/hansu/eventsonlineapp/likes.php?action=like&user_id=1&%20event_id=1&%20event_like_status=1&%20like_date=2016-12-09&%20event_end_date=2016-12-20
  }else if($_GET['action'] == "like"){
    //Variable to keep in db
    $user_id = '';
    $event_id = '';
    $event_like_status = '';
    $event_end_date = '';
    $like_date = '';
    $event_end_date = '';

    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }

    if(isset($_GET['event_id'])){
      $event_id = $_GET['event_id'];
    }

    if(isset($_GET['event_like_status'])){
      $event_like_status = $_GET['event_like_status'];
    }

    if(isset($_GET['event_end_date'])){
      $event_end_date = $_GET['event_end_date'];
    }

    $like_date = date("Y-m-d H:i:s");

    $insertData = "INSERT INTO event_likes(user_id, event_id, event_like_status, like_date, event_end_date)VALUES
    ('".$user_id."','".$event_id."','".$event_like_status."','".$like_date."','".$event_end_date."' )
     ON DUPLICATE KEY UPDATE event_like_status = '".$event_like_status."' , like_date = '".$like_date.  "'   ";
    if($con->query($insertData) === TRUE){
      // Return response
      $response["success"] = 1;
      $response["error_msg"] = "liked!";
      echo json_encode($response);

    }else{

      $response["success"] = 0;
      $response["error_msg"] = "No like status change";
      echo json_encode($response);
    }

  }//End of code secton for making a comment

}


@mysqli_close($con)
?>
