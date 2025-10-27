<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

// $something = $_GET['s'];
if(isset($_GET['action']))
{

  $action = $_GET['action'];

//Do the selection for all records if action is
//http://www.hansumi.com/hansu/eventsonlineapp/zignin_in.php?action=go&namez=hassan&u_pass=12345
//http://localhost/eventsonlineapp/signin.php?action=signin&user_name=hassan&user_password=123
  if($action == 'get_users'){
    $getData = " SELECT user_id, name, phone, email, address, user_status, user_role
    FROM users ";

  }else if($action == 'get_single_user'){

      if(isset($_GET['poster_id'])){

      $poster_id = $_GET['poster_id'];

      $getData = " SELECT user_id, name, phone, email, address, user_status, user_role
      FROM users WHERE user_id = '".$poster_id."' ";

    }
  }

 $sql= mysqli_query($con, $getData);
  while($rows = mysqli_fetch_array($sql)){
    //echo "This is nice";

    $events[] = array(
      "user_id" => $rows['user_id'],
      "name" => $rows['name'],
      "phone" => $rows['phone'],
      "email" => $rows['email'],
      "address" => $rows['address'],
      "user_status" => $rows['user_status'],
      "user_role" => $rows['user_role'],

    );
  }

if(isset($events)){
$abode_json_string = $events;
//echo json_encode($abode_json_string);
echo json_encode($abode_json_string);
}else{
  echo "Invalid email or password";
}


}


  @mysqli_close($con)

?>
