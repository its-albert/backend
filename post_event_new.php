<?php
//error_reporting(0);
// include_once('Connections/conn.php');
include_once('Connections/db_connect.php');
//handle action if available

if(isset($_POST['action']))
{
  // The get the action and declare response variables

  $action = $_POST['action'];
  $response_value = '';
  $response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);

  //All VALUES


  // $posted_date = strtotime("today");
  // $formartedPostedDate = date("Y-m-d",$posted_date );
  //Variable declaration for ticket
  $ename = '';
  $edetails = '';
  $start_date = ''; // Was part of the fields give before but not part of the API given after, just maintained it.
  $start_time   ='';
  $evenue   = '';
  $etype = '';
  $ecategory = '';
  $efee = '';
  $isitfree = 'na';
  // $posted_date = '';
  $user_id = '';
  $name = '';
  $image = '';
  $currency = '';
  $district = '';
  $end_date = '';
  $phone = '';





  if(isset($_POST ['ename'])){
    $ename = mysqli_real_escape_string($conn,$_POST ['ename']);
  }
  if(isset($_POST ['edetails'])){
    $edetails = mysqli_real_escape_string($conn, $_POST ['edetails']);
  }
  if(isset($_POST ['start_date'])){
    $start_date = mysqli_real_escape_string($conn, $_POST ['start_date']);
  }

  if(isset($_POST ['start_time'])){
    $start_time = mysqli_real_escape_string($conn, $_POST ['start_time']);
  }
  if(isset($_POST ['evenue'])) {
    // Was part of the fields give before but not part of the API given after, just maintained it.
    $evenue = mysqli_real_escape_string($conn, $_POST ['evenue']);
  }

  if(isset($_POST ['etype'])){
    $etype = mysqli_real_escape_string($conn, $_POST ['etype']);
  }
  if(isset($_POST ['ecategory'])){
    $ecategory = mysqli_real_escape_string($conn, $_POST ['ecategory']);
  }

  if(isset($_POST ['efee'])){
    $efee   = mysqli_real_escape_string($conn, $_POST ['efee']);
  }

  if(isset($_POST ['posted_by'])){
    $posted_by   = mysqli_real_escape_string($conn, $_POST ['posted_by']);
  }

  // if(isset($formartedPostedDate)){
  //   $posted_date   = $formartedPostedDate;
  // }
  if(isset($_POST ['ebarnar'])){
    $ebarnar = mysqli_real_escape_string($conn, $_POST ['ebarnar']);
  }

  if(isset($_POST ['ewebsite'])){
    $ewebsite = mysqli_real_escape_string($conn, $_POST ['ewebsite']);
  }

  if(isset($_POST ['phone'])){
    $phone = mysqli_real_escape_string($conn, $_POST ['phone']);
    echo 'The phone is: '. $phone;
  }
  if(isset($_POST ['user_id'])){
    $user_id = mysqli_real_escape_string($conn, $_POST ['user_id']);
    // $posted_by = $user_id;
  }

  if(isset($_POST ['currency'])){
    $currency = mysqli_real_escape_string($conn, $_POST ['currency']) ;
  }

  if(isset($_POST ['district'])){
    $district = mysqli_real_escape_string($conn, $_POST ['district']);

  }

  if(isset($_POST ['end_date'])){
    $end_date = mysqli_real_escape_string($conn, $_POST ['end_date']);
  }

  //End of all VALUES

  //check action, if it is ticket
  if($action == "post_event"){
    try {
      $posted_date = date("Y-m-d",strtotime("today") );

      // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
      // $connn = new mysqli("localhost", "dev", "56789#", "events");


      if( !$stmt = $conn -> prepare("INSERT INTO 
      eventz(ename,edetails,start_date,end_date, start_time,evenue,
       district ,etype,ecategory,isitfree,efee, curreny_type ,posted_date,posted_by,ebarnar
       ,ewebsite,phone,user_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")){
        die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      }


      if (!$stmt -> bind_param("ssssssssssssssssss",
      $ename,$edetails,$start_date,$end_date, $start_time,$evenue, 
      $district ,$etype,$ecategory,$isitfree,$efee, $currency ,$posted_date,
      $posted_by,$ebarnar,$ewebsite,$phone,$user_id)) {
        die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
      }

      $stmt->execute();

      //Start of Image saving
      $image_data = $_POST['image_data'];
      if( isset($image_data)){

        //$actualpath =  "http://hansumi.com/hansu/eventsonlineapp/$path";
        // $actualpath =  "hansu/eventsonlineapp/$path";
        $date_now = new DateTime();
        $time_stamp = $date_now->getTimestamp();
        $image_name = 'img_'. $user_id. '_' . $time_stamp . '.jpg';
        $path = "files/images/$ebarnar";

        //Local
        $root_path = $_SERVER['DOCUMENT_ROOT']."/eventsonlineapp/";
        //Server
        $root_path = $_SERVER['DOCUMENT_ROOT']."/events/";
        $actualpath = $root_path . $path;

        if(file_exists($actualpath)){
          echo "No image exists " . $ebarnar. "  " . $actualpath . "    image: " . $image_data ;


          if(unlink($actualpath)){

            file_put_contents($path,base64_decode($image_data));
            echo "success, image replaced " . $actualpath;

          }else{

            echo "file exists";
          }

        }else{

          file_put_contents($path,base64_decode($image_data));
          echo "success, image created  " . $actualpath;
        }

      }else {
        echo "No image saved " . $ebarnar. "  " . $actualpath . "    image: " . $image_data ;
      } // End of image saving

      //Start of else if for action updating event
    } catch (Exception $e) {
      echo "tabu";
      echo 'Message: ' .$e->getMessage();
    }


    // UPDATE SECTION
  }else if($action == "update_event"){

    try {
      $posted_date = date("Y-m-d",strtotime("today") );

      $updated_date = date("Y-m-d",strtotime("today") );
      echo "Nsubuga, the updated date: " .$updated_date . '</br>';


      if(isset($_POST ['event_id'])){
        $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
      }

      if( !$stmt = $conn -> prepare("UPDATE eventz SET ename=?,edetails=?,start_date=?,end_date=?, start_time=?,evenue=?, district=? ,etype=?,ecategory=?,isitfree=?,efee=?, curreny_type=? ,posted_by=?,ebarnar=?,ewebsite=?,user_id=?,updated_on=? WHERE event_id = ? ")){
        die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      }


      if (!$stmt -> bind_param("sssssssssssssssss",$ename,$edetails,$start_date,$end_date, $start_time,$evenue, $district ,$etype,$ecategory,$isitfree,$efee, $currency ,$posted_by,$ebarnar,$ewebsite,$user_id, $updated_date,$event_id)) {
        die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
      }

      $stmt->execute();
      echo "done updating... " . $event_id. '... </br>';

      //Start of Image saving
      $image_data = $_POST['image_data'];
      if( isset($image_data)){

        //$actualpath =  "http://hansumi.com/hansu/eventsonlineapp/$path";
        // $actualpath =  "hansu/eventsonlineapp/$path";
        $date_now = new DateTime();
        $time_stamp = $date_now->getTimestamp();
        $image_name = 'img_'. $user_id. '_' . $time_stamp . '.jpg';
        $path = "files/images/$ebarnar";

        //-------------------  
        //Server
        $root_path = $_SERVER['DOCUMENT_ROOT']."/events/";
        //Local
        // $root_path = $_SERVER['DOCUMENT_ROOT']."/eventsonlineapp/";
        $actualpath = $root_path . $path;
        //-------------------

        if(file_exists($actualpath)){
          echo "No image exists " . $ebarnar. "  " . $actualpath . "    image: " . $image_data ;


          if(unlink($actualpath)){

            file_put_contents($path,base64_decode($image_data));
            echo "success, image replaced " . $actualpath;

          }else{

            echo "file exists";
          }

        }else{

          file_put_contents($path,base64_decode($image_data));
          echo "success, image created  " . $actualpath;
        }

      }else {
        echo "No image to update " . $ebarnar. "  " . $actualpath . "    image: " . $image_data ;
      } // End of image saving

      //Start of else if for action updating event
    } catch (Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }

    //===================== End Update Section ==================================

  }elseif ($action == "delete_event") {
    try {

      // if(isset($_POST ['event_id'])){
      //   $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
      // }

      // if( !$stmt = $conn -> prepare("DELETE FROM eventz WHERE event_id = ? ")){
      //   die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      // }


      // if (!$stmt -> bind_param("i",$event_id)) {
      //   die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
      // }
      $state = 1;

      if(isset($_POST ['event_id'])){
        $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
      }

      if( !$stmt = $conn -> prepare("UPDATE eventz SET deleted=? WHERE event_id = ? ")){
        die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      }

      if (!$stmt -> bind_param("ii",$state,$event_id)) {
        die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
      }

      $stmt->execute();
      echo "Soft Deleted event: " . $event_id;
    }catch (Exception $e) {
      echo 'Message: ' .$e->getMessage();
    }
    // End of Delete Action
  }else if($action == "disapprove_event"){
    $state = 0;
    try {

      if(isset($_POST ['event_id'])){
        $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
      }

      if( !$stmt = $conn -> prepare("UPDATE eventz SET estatus=? WHERE event_id = ? ")){
        die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      }

      if (!$stmt -> bind_param("ii",$state,$event_id)) {
        die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
      }

      $stmt->execute();
      echo "Disapproved event: " . $event_id;
    }catch(Exception $e){
      echo "Message: " . $e->getMessage();
    }
  //End of Dispprove Event action
 }else if($action == "approve_event"){
   $state = 1;
   try {

     if(isset($_POST ['event_id'])){
       $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
     }

     if( !$stmt = $conn -> prepare("UPDATE eventz SET estatus=? WHERE event_id = ? ")){
       die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
     }

     if (!$stmt -> bind_param("ii",$state,$event_id)) {
       die( "Error in bind_param: (" .$conn->errno . ") " . $conn->error);
     }

     $stmt->execute();
     echo "Approved event: " . $event_id;
   }catch(Exception $e){
     echo "Message: " . $e->getMessage();
   }
 //End of Dispprove Event action
}


}else{
  echo "Action is not set!" . $_POST['phone'];
}

@mysqli_close($conn)

?>
