<?php
header('Content-Type: application/json; charset=utf-8');
include_once('Connections/conn.php');

// Combine GET + POST (works from web, Postman, or Flutter)
$request = array_merge($_GET, $_POST);

if (isset($request['action'])) {

    $action = $request['action'];

    // ======================================================
    //    1. FETCH LIKE DATA
    // ======================================================
    if ($action == "likes") {
        // Call your stored procedure (make sure it filters out unlikes)
        $sql = mysqli_query($con, "CALL sp_get_like_datails()");
        $likesData = [];

        while ($row = mysqli_fetch_assoc($sql)) {
            $likesData[] = [
                "event_id"       => $row['event_id'],
                "likes"          => $row['likes'],
                "dis_likes"      => $row['dis_likes'],
                "comments"       => $row['comments'],
                "event_end_date" => $row['event_end_date']
            ];
        }

        echo json_encode([
            "success" => 1,
            "event"   => $likesData
        ]);
    }

    // ======================================================
    // 🟢 2. HANDLE LIKE / DISLIKE / UNLIKE
    // ======================================================
    else if ($action == "like") {

        $user_id           = $request['user_id'] ?? '';
        $event_id          = $request['event_id'] ?? '';
        $event_like_status = $request['event_like_status'] ?? '';
        $event_end_date    = $request['event_end_date'] ?? '';
        $like_date         = date("Y-m-d H:i:s");

        if (empty($user_id) || empty($event_id)) {
            echo json_encode([
                "success" => 0,
                "message" => "Missing required parameters"
            ]);
            exit;
        }

        // 🟠 If user chooses "unlike" (event_like_status = 0)
        if ($event_like_status == "0") {
            $deleteQuery = "DELETE FROM event_likes WHERE user_id='$user_id' AND event_id='$event_id'";
            if ($con->query($deleteQuery) === TRUE) {
                echo json_encode([
                    "success" => 1,
                    "message" => "Unliked successfully"
                ]);
            } else {
                echo json_encode([
                    "success" => 0,
                    "message" => "Failed to unlike: " . mysqli_error($con)
                ]);
            }
        }

        //  Otherwise, insert or update like/dislike
        else {
            $insertData = "
                INSERT INTO event_likes (user_id, event_id, event_like_status, like_date, event_end_date)
                VALUES ('$user_id', '$event_id', '$event_like_status', '$like_date', '$event_end_date')
                ON DUPLICATE KEY UPDATE
                    event_like_status = '$event_like_status',
                    like_date = '$like_date'
            ";

            if ($con->query($insertData) === TRUE) {
                echo json_encode([
                    "success" => 1,
                    "message" => "Reaction updated successfully"
                ]);
            } else {
                echo json_encode([
                    "success" => 0,
                    "message" => "Failed: " . mysqli_error($con)
                ]);
            }
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