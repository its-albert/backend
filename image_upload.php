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

  $path = "files/images/$name.png";


  //$actualpath =  "http://hansumi.com/hansu/eventsonlineapp/$path";
  $actualpath =  "hansu/eventsonlineapp/$path";


  if(isset($name) && isset($image) && isset($actualpath)){
  //  try{
      file_put_contents($path,base64_decode($image));
      echo "success";
  //  }catch(Exception $e){
  //    echo 'Message:' . $e -> getMessage();
  //  }
  }else{
   echo "No image details";
}

}else{
  echo "Error Uploading image";

}
