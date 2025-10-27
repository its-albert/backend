<?php
//error_reporting(0);
include_once('Connections/conn.php');
//handle action if available
if(isset($_GET['action']))
{
  //The get the action and declare response variables
  $action = $_GET['action'];
  $response_value = '';
  $response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);

  if($action == 'sign_up' || $action == 'google_sign_up'){

    $name = '';
    $phone = '';
    $email = '';
    $address = '';
    $uname = '';
    $pword = '';
    $type = '';

    $message = '';

    if(isset($_GET['action'])){
      $type = $_GET['action'];
    }
    if(isset($_GET['u_name'])){

      $uname = $_GET['u_name'];
    }

    if(isset($_GET['u_pass'])){

      $pword = sha1($_GET['u_pass']);

    }
    if(isset($_GET['name'])){

      $name = $_GET['name'];
    }

    if(isset($_GET['phone'])){

      $phone = $_GET['phone'];
    }
    if(isset($_GET['address'])){

      $address = $_GET['address'];

    }

    if(isset($_GET['email'])){

      $email = $_GET['email'];
    }

    //The query to execute
    $sign_up_query = " INSERT INTO users (name, user_name, user_password, phone, email, address, sign_up_type )VALUES('".$name."','".$uname."','".$pword."','".$phone."','".$email."','".$address."','".$type."' )";

    try{
      if($con -> query($sign_up_query) === true ){

        $getData = " SELECT user_id, name, user_name, email, user_status, user_role	FROM users where user_name = '".$uname."'  and user_password = '".$pword."' ";

        $result = mysqli_query($con,$getData);

        if(mysqli_num_rows($result) > 0){

          while($row = mysqli_fetch_assoc($result)){
            $user_id = $row["user_id"];
            $name = $row["name"];
            $user_name = $row["user_name"];
            $email = $row["email"];
            $user_status = $row["user_status"];
            $user_role = $row["user_role"];
          }
        }else{
          $event_data = $con->connect_error;
        }

        $events[] = array(
          "success" => 1,
          "message" => "User registered successfully",
          "user_id" => $user_id,
          "name" => $name,
          "user_name" => $user_name,
          "email" => $email,
          "user_status" => $user_status,
          "user_role" => $user_role,
        );

        $jsonresp = json_encode($events);
        echo $jsonresp;

      }else{

        $error_code =  mysqli_errno($con);
        $error_msg = mysqli_error($con);
        
        if($error_code == 1062 ){
          $msg = "User already exists " . $error_msg;
          

          //------------------------
        $getData = " SELECT user_id, name, user_name, email, user_status, user_role	FROM users where user_name = '".$uname."'  and user_password = '".$pword."' ";

        $result = mysqli_query($con,$getData);

        if(mysqli_num_rows($result) > 0){

          while($row = mysqli_fetch_assoc($result)){
            $user_id = $row["user_id"];
            $name = $row["name"];
            $user_name = $row["user_name"];
            $email = $row["email"];
            $user_status = $row["user_status"];
            $user_role = $row["user_role"];
          }
        }else{
          $error = $con->connect_error;
        }

        $events[] = array(
          "success" => 1,
          "message" => $msg,
          "user_id" => $user_id,
          "name" => $name,
          "user_name" => $user_name,
          "email" => $email,
          "user_status" => $user_status,
          "user_role" => $user_role,
        );

        $jsonresp = json_encode($events);
        echo $jsonresp;
          //------------------------
          return;
        }else{
          $error_msg = "Error code: " . $error_code .   "message: " .$error_msg;
           $message = "User not registered " .$error_msg;
        }

        $events[] = array(
          "success" => 0,
          "message" => $message ,
        );


        $jsonresp = json_encode($events);
        echo $jsonresp;

      }

    }catch( Exception $ex){

      $message = $ex -> getMessage();
      $events[] = array(
        "success" => 0,
        "message" => $message ,
      );


      $jsonresp = json_encode($events);
      echo $jsonresp;
    }


  }
}else{
  echo 'Action is not set';
}
/**
 * This function gets a user from a database given username and password
 */

 function getUserFromDb($user_name, $password, $msg){

    $getData = " SELECT user_id, name, user_name, email, user_status, user_role	FROM users where user_name = '".$uname."'  and user_password = '".$pword."' ";

        $result = mysqli_query($con,$getData);

        if(mysqli_num_rows($result) > 0){

          while($row = mysqli_fetch_assoc($result)){
            $user_id = $row["user_id"];
            $name = $row["name"];
            $user_name = $row["user_name"];
            $email = $row["email"];
            $user_status = $row["user_status"];
            $user_role = $row["user_role"];
          }
        }else{
          $error = $con->connect_error;
           echo 'Nsubuga, do it with error : ' . $error ."</br>";
        }

        if(empty($msg)){
          $msg = "User registered successfully";
        }

        $events[] = array(
          "success" => 1,
          "message" => $msg,
          "user_id" => $user_id,
          "name" => $name,
          "user_name" => $user_name,
          "email" => $email,
          "user_status" => $user_status,
          "user_role" => $user_role,
        );

        $jsonresp = json_encode($events);
        echo $jsonresp;
 }

?>
