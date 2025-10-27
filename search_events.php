<?php
include_once('Connections/conn.php');

// temporary CORS headers for testing
header("Access-control-Allow-Origin: *");
header("Access-control-Allow-Methods: GET, POST, OPTIONS");
header("Access-control-Allow-Headers: *");

if (isset($_GET['action']) || isset($_GET['initialize'])) {
    $action = $_GET['action'] ?? '';
    $eventType = $_GET['eventTypes'] ?? '';
    $eventCategory = $_GET['eventCategory'] ?? '';
    $lastId = $_GET['last_id'] ?? 0;
    $userId = $_GET['userId'] ?? 0;
    $initialize = $_GET['initialize'] ?? 0;
    $searchQuery = isset($_GET['query']) ? mysqli_real_escape_string($con, $_GET['query']) : '';

    $yesterdayz = strtotime("yesterday");
    $yesterday = date("Y-m-d", $yesterdayz);

    // Main SQL builder
    $getData = "";

    if ($initialize == 1) {
        $getData = "SELECT * FROM eventz WHERE estatus = 1 AND end_date >= '$yesterday'";
    } else if ($action == "user_events") {
        $getData = "SELECT * FROM eventz WHERE posted_by = $userId AND estatus = 0 ORDER BY event_id DESC LIMIT 300";
    } else if ($action == 'user' && $lastId > 0) {
        $getData = "SELECT * FROM eventz WHERE estatus = 1 AND event_id > $lastId";
    } else if ($action == 'admin' && $lastId > 0) {
        $getData = "SELECT * FROM eventz WHERE event_id > $lastId";
    } else if ($action == 'allTypes') {
        $getData = "SELECT * FROM eventz WHERE estatus = 1 ORDER BY event_id DESC LIMIT 1000";
    } else if ($action == 'allTypesAdmin') {
        $getData = "SELECT * FROM eventz";
    } else if ($action == 'oneTypeonly') {
        $getData = "SELECT * FROM eventz WHERE estatus = 1 AND etype = '$eventType'";
    } else if ($action == 'oneTypeOneCategory') {
        $getData = "SELECT * FROM eventz WHERE estatus = 1 AND etype = '$eventType' AND ecategory = '$eventCategory'";
    } else if ($action == 'moderateEvents') {
        $getData = "SELECT * FROM eventz WHERE estatus = 0";
    } else if ($action == 'search' && !empty($searchQuery)) {
        $getData = "SELECT * FROM eventz 
            WHERE estatus = 1 AND (
                ename LIKE '%$searchQuery%' OR 
                edetails LIKE '%$searchQuery%' OR 
                start_date LIKE '%$searchQuery%'
            )
            ORDER BY start_date DESC
            LIMIT 200";
    }

    // Only run query if $getData was defined
    if (!empty($getData)) {
        $sql = mysqli_query($con, $getData);
        $events = [];

        while ($rows = mysqli_fetch_array($sql)) {
            $eventId = $rows['event_id'];

            // Calculate likes, dislikes, comments
            $likesQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM event_likes WHERE event_id = '$eventId' AND event_like_status = 1");
            $dislikesQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM event_likes WHERE event_id = '$eventId' AND event_like_status = 2");
            $commentsQuery = mysqli_query($con, "SELECT COUNT(*) AS total FROM comments WHERE event_id = '$eventId'");

            $likes = mysqli_fetch_assoc($likesQuery)['total'] ?? 0;
            $dislikes = mysqli_fetch_assoc($dislikesQuery)['total'] ?? 0;
            $comments = mysqli_fetch_assoc($commentsQuery)['total'] ?? 0;

            $events[] = array(
                "event_id" => $rows['event_id'],
                "ename" => mb_convert_encoding($rows['ename'], 'UTF-8', 'UTF-8'),
                "edetails" => mb_convert_encoding($rows['edetails'], 'UTF-8', 'UTF-8'),
                "start_date" => $rows['start_date'],
                "end_date" => $rows['end_date'],
                "start_time" => $rows['start_time'],
                "evenue" => $rows['evenue'],
                "etype" => $rows['etype'],
                "ecategory" => $rows['ecategory'],
                "efee" => $rows['efee'],
                "posted_by" => $rows['posted_by'],
                "posted_date" => $rows['posted_date'],
                "updated_on" => $rows['updated_on'],
                "ebarnar" => $rows['ebarnar'],
                "ewebsite" => $rows['ewebsite'],
                "estatus" => $rows['estatus'],
                "user_id" => $rows['user_id'],
                "curreny_type" => $rows['curreny_type'],
                "district" => $rows['district'],
                "likes" => $likes,
                "dis_likes" => $dislikes,
                "comments" => $comments,
                "phone" => $rows['phone']
            );
        }

        echo json_encode($events);
    } else {
        echo json_encode([]);
    }
}

@mysqli_close($con);
?>
