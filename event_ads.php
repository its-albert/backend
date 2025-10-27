<?php

include_once('Connections/conn.php');
// temporary corsheaders 
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *"); 

if(isset($_GET['action'])){


  //get action
  $action = $_GET['action'];

  if( $action == 'post_ad' ){

    $ad_title = "";
    $owner_company = "";
    $ad_details = "";
    $ad_image = "";
    $ad_start_date = "";
    $ad_end_date = "";
    $user_id = "";
    $ad_status = "";

    if(isset($_GET['ad_title'])){
      $ad_title = $_GET['ad_title'];
    }
    if(isset($_GET['ad_title'])){
      $ad_title = $_GET['ad_title'];
    }
    if(isset($_GET['owner_company'])){
      $owner_company = $_GET['owner_company'];
    }
    if(isset($_GET['ad_details'])){
      $ad_details = $_GET['ad_details'];
    }
    if(isset($_GET['ad_image'])){
      $ad_image = $_GET['ad_image'] . ".png";
    }
    if(isset($_GET['ad_start_date'])){
      $ad_start_date = $_GET['ad_start_date'];
    }
    if(isset($_GET['ad_end_date'])){
      $ad_end_date = $_GET['ad_end_date'];
    }
    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }
    if(isset($_GET['ad_status'])){
      $ad_status = $_GET['ad_status'];

    }

    $ad_post_date = date("Y-m-d H:i:s");

    //Insert add statement
    $ad_post_sql = "INSERT INTO event_ads( ad_title, owner_company, ad_details, ad_image, ad_start_date, ad_end_date, user_id, ad_status, ad_post_date)
    VALUES ('".$ad_title."','".$owner_company."','".$ad_details."','".$ad_image."','".$ad_start_date."','".$ad_end_date."','".$user_id."','".$ad_status."','".$ad_post_date."')
    ON DUPLICATE KEY UPDATE ad_title = '".$ad_title."', owner_company = '".$owner_company."', ad_details = '".$ad_details."', ad_image = '".$ad_image."',
    ad_start_date = '".$ad_start_date."', ad_end_date = '".$ad_end_date."', user_id = '".$user_id."', ad_status = '".$ad_status."', ad_post_date = '".$ad_post_date."'
    ";


    if($con -> query($ad_post_sql)){
      $response["success"] = 1;
      $response["error_msg"] = "Ad posted!";
      echo json_encode($response);

    }else{
      $response["success"] = 0;
      $response["error_msg"] = "Ad not posted!";
      echo json_encode($response);

    }


  }else if($action == "get_adds"){

    $sqlGetAds = "SELECT ad_id, ad_title, owner_company, ad_details, ad_image, ad_start_date, ad_end_date, user_id, ad_status, ad_post_date FROM event_ads ";

    $adsSql = mysqli_query($con, $sqlGetAds);
    while($rows = mysqli_fetch_array($adsSql)){
      $ads[] = array(
        "ad_id" => $rows['ad_id'],
        "ad_title" => $rows['ad_title'],
        "owner_company" => $rows['owner_company'],
        "ad_details" => $rows['ad_details'],
        "ad_image" => $rows['ad_image'],
        "ad_start_date" => $rows['ad_start_date'],
        "ad_end_date" => $rows['ad_end_date'],
        "user_id" => $rows['user_id'],
        "ad_status" => $rows['ad_status'],
        "ad_post_date" => $rows['ad_post_date']
      );

    }


    if(isset($ads)){
      echo json_encode($ads);
    }else{
      echo 'No ads';
    }



  } else if($action == 'cancel_or_approve_ad'){

  
    if(isset($_GET['ad_id'])){

      $ad_id = $_GET['ad_id'];

    }

    if(isset($_GET['ad_status'])){
      $ad_status =  $_GET['ad_status'];
    }

    $approveAdSql = "UPDATE event_ads SET ad_status = $ad_status WHERE ad_id = $ad_id";

    if($con -> query($approveAdSql) === TRUE){
      // Return response
      $response["success"] = 1;
      if($ad_status == 1){
        $response["error_msg"] = "Ad approved";
      }else{
        $response["error_msg"] = "Ad canceled";
      }
      echo json_encode($response);

    }else{
      // Return response
      $response["success"] = 0;
      $response["error_msg"] = "Oops!";
      echo json_encode($response);
    }

  }

}

?>
