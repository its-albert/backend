<?php
//error_reporting(0);
//include_once('Connections/conn.php');

if($_SERVER['REQUEST_METHOD']=='POST'){

  if(isset($_POST['image'])){
    $image = $_POST['image'];
  }

  if(isset($_POST['name'])){
    $name = $_POST['name'];
  }
  if(isset($_POST['action'])){
    $action = $_POST['action'];
  }

  if($action == "ad"){

    $path = "files/ads_images/$name.gif";

  }else if($action == "event"){
    $path = "files/images/$name.png";
  }

  //$actualpath =  "http://hansumi.com/hansu/eventsonlineapp/$path";
  $actualpath =  "hansu/eventsonlineapp/$path";

  if(isset($name) && isset($image) && isset($actualpath)){
    //
    if(file_exists($actualpath)){

      if(unlink($actualpath)){

        file_put_contents($path,base64_decode($image));
        echo "success";

      }else{

        echo "file exists";
      }

    }else{

      file_put_contents($path,base64_decode($image));
      echo "success";
    }

  }else{
    echo "No image details";
  }

}else{
  echo "Error Uploading image";

}
