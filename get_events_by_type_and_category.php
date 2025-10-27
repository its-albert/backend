<?php
// Include the database connection
include_once('Connections/conn.php');

// Set headers for CORS support
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");

// Check if 'action' is provided in the request
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Handle the "getEventsByTypeAndCategory" action
    if ($action == 'getEventsByTypeAndCategory') {

        // Get the event type and category from the query parameters
        $eventType = isset($_GET['eventTypes']) ? $_GET['eventTypes'] : '';
        $eventCategory = isset($_GET['eventCategory']) ? $_GET['eventCategory'] : '';

        // Validate the inputs to prevent SQL injection
        $eventType = mysqli_real_escape_string($con, $eventType);
        $eventCategory = mysqli_real_escape_string($con, $eventCategory);

        // Construct the SQL query to fetch events of the specified type and category
        $getData = "SELECT eventz.event_id, ename, edetails, start_date, end_date, start_time, evenue, etype, ecategory,
                    efee, posted_by, posted_date, updated_on, ebarnar, ewebsite, estatus, eventz.user_id, curreny_type, district, 
                    end_date, phone,
                    (SELECT COUNT(event_likes.event_like_status) FROM event_likes WHERE event_like_status = 1 AND event_id = eventz.event_id) AS likes,
                    (SELECT COUNT(event_likes.event_like_status) FROM event_likes WHERE event_like_status = 2 AND event_id = eventz.event_id) AS dis_likes,
                    (SELECT COUNT(comments.user_comment) FROM comments WHERE comments.event_id = eventz.event_id) AS comments
                FROM eventz
                WHERE estatus = 1 AND etype = '$eventType' AND ecategory = '$eventCategory'";

        // Execute the query
        $sql = mysqli_query($con, $getData);

        // Initialize an array to store the events
        $events = [];

        // Fetch the events and add them to the array
        while ($rows = mysqli_fetch_array($sql)) {
            $dislike = $rows['dis_likes'] ?? 0;
            $likes = $rows['likes'] ?? 0;
            $district = $rows['district'] ?? '';
            $comments = $rows['comments'] ?? '';

            // Add event details to the events array
            $events[] = array(
                "event_id" => $rows['event_id'],
                "ename" => mb_convert_encoding($rows['ename'], 'UTF-8', 'UTF-8'),
                "edetails" => mb_convert_encoding($rows['edetails'], 'UTF-8', 'UTF-8'),
                "start_date" => mb_convert_encoding($rows['start_date'], 'UTF-8', 'UTF-8'),
                "end_date" => mb_convert_encoding($rows['end_date'], 'UTF-8', 'UTF-8'),
                "start_time" => mb_convert_encoding($rows['start_time'], 'UTF-8', 'UTF-8'),
                "evenue" => mb_convert_encoding($rows['evenue'], 'UTF-8', 'UTF-8'),
                "etype" => mb_convert_encoding($rows['etype'], 'UTF-8', 'UTF-8'),
                "ecategory" => mb_convert_encoding($rows['ecategory'], 'UTF-8', 'UTF-8'),
                "efee" => mb_convert_encoding($rows['efee'], 'UTF-8', 'UTF-8'),
                "posted_by" => mb_convert_encoding($rows['posted_by'], 'UTF-8', 'UTF-8'),
                "posted_date" => mb_convert_encoding($rows['posted_date'], 'UTF-8', 'UTF-8'),
                "updated_on" => $rows['updated_on'],
                "ebarnar" => mb_convert_encoding($rows['ebarnar'], 'UTF-8', 'UTF-8'),
                "ewebsite" => mb_convert_encoding($rows['ewebsite'], 'UTF-8', 'UTF-8'),
                "estatus" => mb_convert_encoding($rows['estatus'], 'UTF-8', 'UTF-8'),
                "user_id" => mb_convert_encoding($rows['user_id'], 'UTF-8', 'UTF-8'),
                "curreny_type" => mb_convert_encoding($rows['curreny_type'], 'UTF-8', 'UTF-8'),
                "district" => mb_convert_encoding($district, 'UTF-8', 'UTF-8'),
                "likes" => mb_convert_encoding($likes, 'UTF-8', 'UTF-8'),
                "dis_likes" => mb_convert_encoding($dislike, 'UTF-8', 'UTF-8'),
                "comments" => mb_convert_encoding($comments, 'UTF-8', 'UTF-8'),
                "phone" => $rows['phone']
            );
        }

        // Return the events as a JSON response
        if (isset($events) && !empty($events)) {
            echo json_encode($events);
        } else {
            // No events found, return a message
            echo json_encode(array("message" => "No events found for the specified type and category."));
        }
    } else {
        // Invalid action provided
        echo json_encode(array("message" => "Invalid action specified."));
    }
} else {
    // Action parameter not provided in the request
    echo json_encode(array("message" => "Action parameter is required."));
}

// Close the database connection
@mysqli_close($con);
?>
