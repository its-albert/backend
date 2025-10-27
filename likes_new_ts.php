<?php
//error_reporting(0);
include_once('Connections/conn.php');

$uname = $_GET['u_name'];
$pword = sha1($_GET['u_pass']);

function muyite($name){
  echo "Muyite: $name </br>";
}

// function getUser($uname, $pword){
  echo "calling </br>";
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
      // }




echo $uname. ' and ' .$pword;
getUser($uname, $pword);
muyite("Machuq");

@mysqli_close($con)
?>
