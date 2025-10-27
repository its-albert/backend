<?php
header('Content-Type: application/json; charset=utf-8');
include_once('Connections/conn.php');

// Merge both GET and POST (so it works from web and Flutter)
$request = array_merge($_GET, $_POST);

if (isset($request['action'])) {

    $action = $request['action'];

    if ($action == "likes") {
        $getComments = "CALL sp_get_like_datails()";
        $sql = mysqli_query($con, $getComments);

        $comments = [];
        while ($rows = mysqli_fetch_array($sql)) {
            $comments[] = [
                "event_id"       => $rows['event_id'],
                "likes"          => $rows['likes'],
                "dis_like"       => $rows['dis_likes'],
                "comments"       => $rows['comments'],
                "event_end_date" => $rows['event_end_date']
            ];
        }

        echo json_encode([
            "success" => 1,
            "event"   => $comments
        ]);
    }

    else if ($action == "like") {
        $user_id          = $request['user_id'] ?? '';
        $event_id         = $request['event_id'] ?? '';
        $event_like_status= $request['event_like_status'] ?? '';
        $event_end_date   = $request['event_end_date'] ?? '';
        $like_date        = date("Y-m-d H:i:s");

        $insertData = "
            INSERT INTO event_likes(user_id, event_id, event_like_status, like_date, event_end_date)
            VALUES ('$user_id','$event_id','$event_like_status','$like_date','$event_end_date')
            ON DUPLICATE KEY UPDATE
                event_like_status = '$event_like_status',
                like_date = '$like_date'
        ";

        if ($con->query($insertData) === TRUE) {
            echo json_encode([
                "success" => 1,
                "message" => "Liked!"
            ]);
        } else {
            echo json_encode([
                "success" => 0,
                "message" => "Error: " . mysqli_error($con)
            ]);
        }
    }

} else {
    echo json_encode([
        "success" => 0,
        "message" => "No action parameter provided"
    ]);
}

@mysqli_close($con);
?>