<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

// $something = $_GET['s'];
if(isset($_GET['action']))
{

	 $action = $_GET['action'];
  $user_name = '';
  $user_password = '';
  if(isset($_GET['user_name'])){
    $user_name = $_GET['user_name'];
  }

  if(isset($_GET['user_password'])){
    $user_password = $_GET['user_password'];
  }



//Do the selection for all records if action is
//http://localhost/eventsonlineapp/signin.php?action=signin&user_name=hassan&user_password=123
  if($action == 'signin'){

		$getData = " SELECT user_id, name, user_name, email, user_status, user_role
		FROM users where user_name = '".$user_name."'  and user_password = '".$user_password."' ";
	}


$sql= mysqli_query($con, $getData);
	while($rows = mysqli_fetch_array($sql)){
		//echo "This is nice";

		$events[] = array(
			"user_id" => $rows['user_id'],
			"name" => $rows['name'],
			"user_name" => $rows['user_name'],
			"email" => $rows['email'],
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
