<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");

include_once('Connections/conn.php');

// --- Fetch all comments for a specific event ---
if (isset($_GET['action']) && $_GET['action'] == "comments" && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $comments = [];

    $sql = mysqli_query($con, "
        SELECT 
            c.comment_id, 
            c.user_id, 
            c.event_id, 
            c.user_comment, 
            c.comment_date, 
            u.name
        FROM comments c
        INNER JOIN users u ON c.user_id = u.user_id
        WHERE c.event_id = $event_id
        ORDER BY c.comment_id DESC
    ");

    while ($row = mysqli_fetch_assoc($sql)) {
        $comments[] = $row;
    }

    echo json_encode($comments);
    exit;
}

// --- Add a new comment ---
if (isset($_POST['action']) && $_POST['action'] == "comment") {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $user_comment = $_POST['user_comment'];
    $comment_date = date("Y-m-d");

    $stmt = $con->prepare("INSERT INTO comments (user_id, event_id, user_comment, comment_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $event_id, $user_comment, $comment_date);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => 1,
            'message' => 'Comment added successfully',
            'comment' => [ 
              
                'comment_id' => $stmt->insert_id,
                'user_id' => $user_id,
                'event_id' => $event_id,
                'user_comment' => $user_comment,
                'comment_date' => $comment_date
            ]
        ]);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Failed to add comment']);
    }
    exit;
}

mysqli_close($con);
?>