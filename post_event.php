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



  //check action, if it is ticket
  if($action == "post_event"){
    $posted_date =strtotime("today");
    $formartedPostedDate = date("Y-m-d",$posted_date );
    //Variable declaration for ticket
    $ename = '';
    $edetails = '';
    $start_date = ''; // Was part of the fields give before but not part of the API given after, just maintained it.
    $start_time   ='';
    $evenue   = '';
    $etype = '';
    $ecategory = '';
    $efee = '';
    $posted_date = '';
    $user_id = '';
    $name = '';
    $image = '';



    if(isset($_GET['ename'])){
      $ename = $_GET['ename'];
    }
    if(isset($_GET['edetails'])){
      $edetails = $_GET['edetails'];
    }
    if(isset($_GET['start_date'])){
      $start_date = $_GET['start_date'];
    }

    if(isset($_GET['start_time'])){
      $start_time = $_GET['start_time'];
    }
    if(isset($_GET['evenue'])) {
      // Was part of the fields give before but not part of the API given after, just maintained it.
      $evenue = $_GET['evenue'];
    }

    if(isset($_GET['etype'])){
      $etype = $_GET['etype'];
    }
    if(isset($_GET['ecategory'])){
      $ecategory = $_GET['ecategory'];
    }


    if(isset($_GET['efee'])){
      $efee   = $_GET['efee'];
    }

    if(isset($_GET['posted_by'])){
      $posted_by   = $_GET['posted_by'];
    }

    if(isset($formartedPostedDate)){
      $posted_date   = $formartedPostedDate;
    }
    if(isset($_GET['ebarnar'])){
      $ebarnar = $_GET['ebarnar'] .'.png';
    }

    if(isset($_GET['ewebsite'])){
      $ewebsite = $_GET['ewebsite'];
    }
    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }

    //These are for the image
    if(isset($_GET['image'])){
      $image = $_GET['image'];
    }

    if(isset($_GET['ebarnar'])){
      $name = $_GET['ebarnar'];
    }

    //Do the insertion
    //$insertData = "INSERT INTO offense( TicketNo,station, offense_date, offense_time, offense_place, driver_name, gender, permit, chasis_no, car_make, car_model, category, penalty, officer_name, officer_id, remarks)VALUES('".$ticket_no."','".$station."','".$offence_date."','".$offense_time."','".$offence_place."','".$driver_name."','".$gender."', '".$permit."','".$chasis_no."','".$car_make."','".$car_model."','".$category."','".$penalty."','".$officer_name."','".$officer_id."','".$remarks."')" ;

    $insertData = "INSERT INTO eventz(ename,edetails,start_date,start_time,evenue,etype,ecategory,efee,posted_date,posted_by,ebarnar,ewebsite,user_id)VALUES ('".$ename."','".$edetails."','".$start_date."','".$start_time."','".$evenue."','".$etype."','".$ecategory."', '".$efee."','".$posted_date."','".$posted_by."','".$ebarnar."','".$ewebsite."','".$user_id."')" ;



    //  file_put_contents($path,base64_decode($image));

      if($con->query($insertData) === TRUE){
        // Return response
        $response["success"] = 1;
        $response["error_msg"] = "Upload completed!";
        echo json_encode($response);

      }else{
        $response["success"] = 0;
        $response["error_msg"] = "Transaction did not complete";
        echo json_encode($response);
      }


    //Start of else if for action updating event
  } else if($action == "approve_event"){

    $eventId = $_GET['event_id'];

    if(isset($_GET['event_id']) &&  $eventId > 0 ){

      $updateData = "UPDATE eventz SET estatus = 1 WHERE event_id = $eventId";
      if($con -> query($updateData) === TRUE){
        // Return response
        $response["success"] = 1;
        $response["error_msg"] = "Event update completed!";
        echo json_encode($response);
      }else{
        // Return response
        $response["success"] = 0;
        $response["error_msg"] = "Event update not completed";
        echo json_encode($response);
      }

    }
    //Update event details
  } else if( $action == "disapprove_event" ){
    $eventId = $_GET['event_id'];

    if(isset($_GET['event_id']) &&  $eventId > 0 ){

      $updateData = "UPDATE eventz SET estatus = 0 WHERE event_id = $eventId";
      if($con -> query($updateData) === TRUE){
        // Return response
        $response["success"] = 1;
        $response["error_msg"] = "Event canceled";
        echo json_encode($response);
      }else{
        // Return response
        $response["success"] = 0;
        $response["error_msg"] = "Event not canceled";
        echo json_encode($response);
      }

    }

  // Delete event
  }
    else if( $action == "delete" ){
    $eventId = $_GET['event_id'];

    if(isset($_GET['event_id']) &&  $eventId > 0 ){

      $updateData = "DELETE FROM eventz  WHERE event_id = $eventId";
      if($con -> query($updateData) === TRUE){
        // Return response
        $response["success"] = 1;
        $response["error_msg"] = "Event deleted";
        echo json_encode($response);
      }else{
        // Return response
        $response["success"] = 0;
        $response["error_msg"] = "Event not deleted";
        echo json_encode($response);
      }

    }

  }

  else if($action == "update_whole_event"){

    //The get the action and declare response variables
    $action = $_GET['action'];
    $response_value = '';
    $response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);

    $posted_date =strtotime("today");
    $formartedPostedDate = date("Y-m-d",$posted_date );
    //Variable declaration for ticket
    $ename = '';
    $edetails = '';
    $start_date = ''; // Was part of the fields give before but not part of the API given after, just maintained it.
    $start_time   ='';
    $evenue   = '';
    $etype = '';
    $ecategory = '';
    $efee = '';
    $posted_date = '';
    $user_id = '';

    if(isset($_GET['ename'])){
      $ename = $_GET['ename'];
    }
    if(isset($_GET['edetails'])){
      $edetails = $_GET['edetails'];
    }
    if(isset($_GET['start_date'])){
      $start_date = $_GET['start_date'];
    }

    if(isset($_GET['start_time'])){
      $start_time = $_GET['start_time'];
    }
    if(isset($_GET['evenue'])) {
      // Was part of the fields give before but not part of the API given after, just maintained it.
      $evenue = $_GET['evenue'];
    }

    if(isset($_GET['etype'])){
      $etype = $_GET['etype'];
    }
    if(isset($_GET['ecategory'])){
      $ecategory = $_GET['ecategory'];
    }


    if(isset($_GET['efee'])){
      $efee   = $_GET['efee'];
    }

    if(isset($_GET['posted_by'])){
      $posted_by   = $_GET['posted_by'];
    }

    if(isset($formartedPostedDate)){
      $posted_date   = $formartedPostedDate;
    }
    if(isset($_GET['ebarnar'])){
      $ebarnar = $_GET['ebarnar'] .'.png';
    }

    if(isset($_GET['ewebsite'])){
      $ewebsite = $_GET['ewebsite'];
    }
    if(isset($_GET['user_id'])){
      $user_id = $_GET['user_id'];
    }

    if(isset($_GET['event_id'])){

      $event_id = $_GET['event_id'];

    }

    //Do the insertion
    //$insertData = "INSERT INTO offense( TicketNo,station, offense_date, offense_time, offense_place, driver_name, gender, permit, chasis_no, car_make, car_model, category, penalty, officer_name, officer_id, remarks)VALUES('".$ticket_no."','".$station."','".$offence_date."','".$offense_time."','".$offence_place."','".$driver_name."','".$gender."', '".$permit."','".$chasis_no."','".$car_make."','".$car_model."','".$category."','".$penalty."','".$officer_name."','".$officer_id."','".$remarks."')" ;

    $updateData = " UPDATE eventz SET ename = '".$ename."', edetails = '".$edetails."',start_date = '".$start_date."',start_time = '".$start_time."',evenue = '".$evenue."',etype = '".$etype."',ecategory = '".$ecategory."',efee = '".$efee."',posted_by = '".$posted_by."',ewebsite = '".$ewebsite."',user_id = '".$user_id."' WHERE event_id = '".$event_id ."'   " ;

    if($con->query($updateData) === TRUE){
      // Return response
      $response["success"] = 1;
      $response["error_msg"] = "Post updated!";
      echo json_encode($response);

    }else{
      $response["success"] = 0;
      $response["error_msg"] = "Post did update";
      echo json_encode($response);
    }

  }

}//End of if statement for the action check

@mysqli_close($con)

?>
