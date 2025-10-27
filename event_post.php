<?php
include_once('Connections/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(["success" => 0, "error_msg" => "No data received"]);
        exit;
    }

    // Extract fields
    $ename = $data['ename'] ?? '';
    $edetails = $data['edetails'] ?? '';
    $start_date = $data['start_date'] ?? '';
    $start_time = $data['start_time'] ?? '';
    $evenue = $data['evenue'] ?? '';
    $etype = $data['etype'] ?? '';
    $ecategory = $data['ecategory'] ?? '';
    $efee = $data['efee'] ?? '';
    $posted_date = date("Y-m-d");
    $posted_by = $data['posted_by'] ?? '';
    $ewebsite = $data['ewebsite'] ?? '';
    $user_id = $data['user_id'] ?? '';
    
    $ebarnar = ''; // filename

    // Handle image if sent
    if (isset($data['image']) && isset($data['image_name'])) {
        $imageData = $data['image'];
        $imageName = $data['image_name']; // e.g., img_123456.png

        $path = "files/images/$imageName";
        if (file_put_contents($path, base64_decode($imageData))) {
            $ebarnar = $imageName;
        } else {
            echo json_encode(["success" => 0, "error_msg" => "Image upload failed"]);
            exit;
        }
    }

    // Insert into database
    $insertData = "INSERT INTO eventz
        (ename, edetails, start_date, start_time, evenue, etype, ecategory, efee, posted_date, posted_by, ebarnar, ewebsite, user_id)
        VALUES
        ('$ename', '$edetails', '$start_date', '$start_time', '$evenue', '$etype', '$ecategory', '$efee', '$posted_date', '$posted_by', '$ebarnar', '$ewebsite', '$user_id')";

    if ($con->query($insertData) === TRUE) {
        echo json_encode(["success" => 1, "error_msg" => "Upload completed!"]);
    } else {
        echo json_encode(["success" => 0, "error_msg" => "Transaction did not complete"]);
    }

    @mysqli_close($con);
} else {
    echo json_encode(["success" => 0, "error_msg" => "Invalid request"]);
}
?>