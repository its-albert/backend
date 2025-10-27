<?php
//error_reporting(0);
include_once('Connections/conn.php');
//Put the location value in to a variable

// temporary corsheaders
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *");

// $something = $_GET['s'];
if(isset($_GET['action']))
{

	 $action = $_GET['action'];
  $user_name = '';
  $user_password = '';
  if(isset($_GET['namez'])){
    $namez = $_GET['namez'];
  }

  if(isset($_GET['u_pass'])){
    $u_pass = sha1($_GET['u_pass']);
  }



//Do the selection for all records if action is
//http://localhost/eventsonlineapp/signin.php?action=signin&user_name=hassan&user_password=123
  if($action == 'go'){

		$getData = " SELECT user_id, name, user_name, email, user_status, user_role
		FROM users where user_name = '".$namez."'  and user_password = '".$u_pass."' ";
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
