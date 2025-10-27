<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

// $something = $_GET['s'];
if(isset($_GET['action']))
{//Code section for getting comments
  $action = $_GET['action'];
  $news[] = array();

  if($action == 'news'){

    //String to time
    $yesterdayz = strtotime("yesterday");
    $yesterday = date("Y-m-d", $yesterdayz);
    //Select statement
    $getComments = "SELECT news_id, user_id, news_title, news_body, news_image_url, news_status, post_date FROM event_news ";

    $sql= mysqli_query($con, $getComments);

    while( $rows = mysqli_fetch_array($sql)){
      $comments[] = array(
        "news_id" => $rows['news_id'],
        "user_id" => $rows['user_id'],
        "news_title" => $rows['news_title'],
        "news_body" => $rows['news_body'],
        "news_image_url" => $rows['news_image_url'],
        "news_status" => $rows['news_status'],
        "post_date" => $rows['post_date']
      );

    }

    if(isset($comments)){
      echo json_encode($comments);
    }else{
      echo 'No like made';
    }


  }else if($action == 'post_news'){

    $user_id = "";
    $news_title = "";
    $news_body = "";
    $news_image_url = "";
    $news_status = "";
    $post_date = "";

    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }
    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }
    if(isset($_GET['news_title'])){
      $news_title = $_GET['news_title'];
    }

    if(isset($_GET['news_body'])){
      $news_body = $_GET['news_body'];
    }
    if(isset($_GET['news_image_url'])){
      $news_image_url = $_GET['news_image_url'];
    }
    if(isset($_GET['news_status'])){
      $news_status = $_GET['news_status'];
    }

    $post_date = date("Y-m-d H:i:s");
    $insertData = "INSERT INTO event_news(user_id, news_title, news_body, news_image_url, news_status, post_date)VALUES
    ('".$user_id."','".$news_title."','".$news_body."','".$news_image_url."','".$news_status."','".$post_date."' )
     ON DUPLICATE KEY UPDATE user_id = '".$user_id."' , news_title = '".$news_title."', news_body  = '".$news_title."'   ";
    if($con->query($insertData) === TRUE){
      // Return response
      $response["success"] = 1;
      $response["error_msg"] = "News posted!";
      echo json_encode($response);

    }else{

      $response["success"] = 0;
      $response["error_msg"] = "No news posted";
      echo json_encode($response);
    }


  }

}


@mysqli_close($con)
?>
