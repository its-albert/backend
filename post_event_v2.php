<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('Connections/conn.php');

// Create upload folder if it doesn’t exist
$uploadDir = __DIR__ . '/uploads/events/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $response = array("action" => $action, "success" => 0, "error" => 0);

    // =========================================================
    // 🟢 POST NEW EVENT
    // =========================================================
    if ($action == "post_event") {

        $posted_date = date("Y-m-d");

        // Collect and sanitize inputs
        $ename        = $_GET['ename'] ?? '';
        $edetails     = $_GET['edetails'] ?? '';
        $start_date   = $_GET['start_date'] ?? '';
        $end_date     = $_GET['end_date'] ?? NULL;
        $start_time   = $_GET['start_time'] ?? '';
        $evenue       = $_GET['evenue'] ?? '';
        $district     = $_GET['district'] ?? 'Kampala';
        $etype        = $_GET['etype'] ?? '';
        $ecategory    = $_GET['ecategory'] ?? '';
        $efee         = $_GET['efee'] ?? '0';
        $posted_by    = $_GET['posted_by'] ?? '';
        $ewebsite     = $_GET['ewebsite'] ?? '';
        $user_id      = $_GET['user_id'] ?? '';
        $curreny_type = $_GET['curreny_type'] ?? '/-';
        $estatus      = 0;
        $deleted      = 0;
        $isitfree     = ((float)$efee == 0) ? 'yes' : 'no';

        // Handle banner name and image  
        $ebarnar = isset($_GET['ebarnar']) ? $_GET['ebarnar'] . '.png' : 'default.png';
        $imageBase64 = $_GET['image'] ?? '';

        // Save image if provided (Base64)
        if (!empty($imageBase64)) {
            $imageData = base64_decode($imageBase64);
            $imagePath = $uploadDir . $ebarnar;
            if (file_put_contents($imagePath, $imageData) === false) {
                $response["error_msg"] = "Failed to save image file.";
                echo json_encode($response);
                exit;
            }
        }

        // Prepare SQL
        $stmt = $con->prepare("
            INSERT INTO eventz (
                ename, edetails, start_date, end_date, start_time, evenue, district,
                etype, ecategory, isitfree, efee, curreny_type, posted_by, posted_date,
                ebarnar, ewebsite, estatus, user_id, deleted
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        if (!$stmt) {
            $response["error_msg"] = "Prepare failed: " . $con->error;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param(
            "sssssssssssssssiiii",
            $ename, $edetails, $start_date, $end_date, $start_time, $evenue, $district,
            $etype, $ecategory, $isitfree, $efee, $curreny_type, $posted_by,
            $posted_date, $ebarnar, $ewebsite, $estatus, $user_id, $deleted
        );

        if ($stmt->execute()) {
            $response["success"] = 1;
            $response["error_msg"] = "Event posted successfully!";
        } else {
            $response["error_msg"] = "Insert failed: " . $stmt->error;
        }

        echo json_encode($response);
        $stmt->close();
    }

    //  APPROVE EVENT
    
    else if ($action == "approve_event" && isset($_GET['event_id'])) {
        $eventId = intval($_GET['event_id']);
        $stmt = $con->prepare("UPDATE eventz SET estatus = 1 WHERE event_id = ?");
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $response["success"] = 1;
            $response["error_msg"] = "Event approved!";
        } else {
            $response["error_msg"] = "Approval failed!";
        }
        echo json_encode($response);
        $stmt->close();
    }

    // DISAPPROVE EVENT
    else if ($action == "disapprove_event" && isset($_GET['event_id'])) {
        $eventId = intval($_GET['event_id']);
        $stmt = $con->prepare("UPDATE eventz SET estatus = 0 WHERE event_id = ?");
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $response["success"] = 1;
            $response["error_msg"] = "Event disapproved!";
        } else {
            $response["error_msg"] = "Disapproval failed!";
        }
        echo json_encode($response);
        $stmt->close();
    }

    //  DELETE EVENT
    
    else if ($action == "delete" && isset($_GET['event_id'])) {
        $eventId = intval($_GET['event_id']);
        $stmt = $con->prepare("DELETE FROM eventz WHERE event_id = ?");
        $stmt->bind_param("i", $eventId);
        if ($stmt->execute()) {
            $response["success"] = 1;
            $response["error_msg"] = "Event deleted!";
        } else {
            $response["error_msg"] = "Event not deleted!";
        }
        echo json_encode($response);
        $stmt->close();
    }

    //  UPDATE EVENT
    else if ($action == "update_event" && isset($_GET['event_id'])) {
        $event_id    = $_GET['event_id'];
        $ename       = $_GET['ename'] ?? '';
        $edetails    = $_GET['edetails'] ?? '';
        $start_date  = $_GET['start_date'] ?? '';
        $start_time  = $_GET['start_time'] ?? '';
        $evenue      = $_GET['evenue'] ?? '';
        $etype       = $_GET['etype'] ?? '';
        $ecategory   = $_GET['ecategory'] ?? '';
        $efee        = $_GET['efee'] ?? '0';
        $posted_by   = $_GET['posted_by'] ?? '';
        $ewebsite    = $_GET['ewebsite'] ?? '';
        $user_id     = $_GET['user_id'] ?? '';

        $isitfree = ((float)$efee == 0) ? 'yes' : 'no';

        $stmt = $con->prepare("
            UPDATE eventz SET 
                ename = ?, edetails = ?, start_date = ?, start_time = ?, evenue = ?, 
                etype = ?, ecategory = ?, efee = ?, isitfree = ?, posted_by = ?, 
                ewebsite = ?, user_id = ? 
            WHERE event_id = ?
        ");

        $stmt->bind_param(
            "sssssssssssi",
            $ename, $edetails, $start_date, $start_time, $evenue,
            $etype, $ecategory, $efee, $isitfree, $posted_by,
            $ewebsite, $user_id, $event_id
        );

        if ($stmt->execute()) {
            $response["success"] = 1;
            $response["error_msg"] = "Event updated successfully!";
        } else {
            $response["error_msg"] = "Update failed: " . $stmt->error;
        }

        echo json_encode($response);
        $stmt->close();
    }
}

$con->close();
?>
