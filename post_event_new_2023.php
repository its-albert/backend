<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);
// include_once('Connections/conn.php');
include_once('Connections/db_connect.php');
//handle action if available
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: Get, Post, OPTIONS");
header("Access-control-Allow-Headers: *");

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('Connections/db_connect.php');

// Allow browser access
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");

// Allow both GET and POST
$request = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

if (isset($request['action'])) {

  $action = $request['action'];
  $response_value = '';
  $response = array("action" => $action, "success" => 0, "error" => 0, "value" => $response_value);

  // Initialize all vars
  $ename = $edetails = $start_date = $start_time = $evenue = $etype = $ecategory = $efee = '';
  $isitfree = 'na';
  $user_id = $currency = $district = $end_date = $phone = '';
  $posted_by = $ebarnar = $ewebsite = '';

  // Read values safely from request
  foreach (['ename','edetails','start_date','end_date','start_time','evenue','etype','ecategory','efee','posted_by','ebarnar','ewebsite','phone','user_id','currency','district'] as $field) {
      if (isset($request[$field])) {
          $$field = mysqli_real_escape_string($conn, $request[$field]);
      }
  }

  //check action, if it is ticket
  if ($action == "post_event") {
  try {
    $posted_date = date("Y-m-d");
    $updated_on = null;
    $estatus = 1;   // active by default
    $deleted = 0;   // not deleted

    $stmt = $conn->prepare("
      INSERT INTO eventz(
        ename, edetails, start_date, end_date, start_time,
        evenue, district, etype, ecategory, isitfree, efee,
        curreny_type, posted_by, posted_date, updated_on,
        ebarnar, ewebsite, estatus, user_id, deleted
      ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    if (!$stmt) {
      die('Error preparing statement: (' . $conn->errno . ') ' . $conn->error);
    }

    $stmt->bind_param(
      "ssssssssssssssssiiis",
      $ename,       // varchar(225)
      $edetails,    // varchar(800)
      $start_date,  // date
      $end_date,    // date
      $start_time,  // varchar(12)
      $evenue,      // varchar(200)
      $district,    // varchar(125)
      $etype,       // varchar(100)
      $ecategory,   // varchar(100)
      $isitfree,    // varchar(3)
      $efee,        // varchar(45)
      $currency,    // varchar(5) — matches curreny_type
      $posted_by,   // varchar(45)
      $posted_date, // date
      $updated_on,  // date (nullable)
      $ebarnar,     // varchar(200)
      $ewebsite,    // varchar(200)
      $estatus,     // tinyint
      $user_id,     // bigint
      $deleted      // tinyint
    );

    $stmt->execute();
    echo "✅ Event created successfully, ID: " . $stmt->insert_id;

    // ==================== IMAGE HANDLING ====================
    if (isset($_POST['image_data'])) {
      $image_data = $_POST['image_data'];
      $date_now = new DateTime();
      $timestamp = $date_now->getTimestamp();
      $image_name = 'img_' . $user_id . '_' . $timestamp . '.jpg';
      $path = "files/images/" . $image_name;

      // Adjust path as per your environment
      $root_path = $_SERVER['DOCUMENT_ROOT'] . "/events/";
      $actualpath = $root_path . $path;

      file_put_contents($actualpath, base64_decode($image_data));
      echo " | 🖼️ Image saved: " . $path;
    }

  } catch (Exception $e) {
    echo "❌ Error inserting event: " . $e->getMessage();
  }


 
    // UPDATE SECTION
  }else if($action == "update_event"){

    try {
      $posted_date = date("Y-m-d",strtotime("today") );

      $updated_date = date("Y-m-d",strtotime("today") );
      echo "Updated date: " .$updated_date . '</br>';


      if(isset($_POST ['event_id'])){
        $event_id = mysqli_real_escape_string($conn, $_POST ['event_id']);
      }

      if( !$stmt = $conn -> prepare("UPDATE eventz SET ename=?,edetails=?,start_date=?,end_date=?, start_time=?,evenue=?, district=? ,etype=?,ecategory=?,isitfree=?,efee=?, curreny_type=? ,posted_by=?,ebarnar=?,ewebsite=?,user_id=?,updated_on=? WHERE event_id = ? ")){
        die( "Error preparing: (" .$conn->errno . ") " . $conn->error);
      }


      if (!$stmt -> bind_param("sssssssssssssssssi",$ename,$edetails,$start_date,$end_date, $start_time,$evenue, $district ,$etype,$ecategory,$isitfree,$efee, $currency ,$posted_by,$ebarnar,$ewebsite,$user_id, $updated_date,$event_id)) {
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

      // $stmt->execute();
      // echo "Deleted event: " . $event_id;

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
  echo "Action is not set! Please send a POST request with the 'action' parameter.";
}

@mysqli_close($conn)

?>
