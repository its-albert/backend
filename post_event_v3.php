<?php
// Display all errors (for debugging – turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include DB connection
include_once('Connections/conn.php');

// Set upload directory
$uploadDir = __DIR__ . '/uploads/events/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Utility function for JSON response
function respond($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Make sure action is set
$action = $_REQUEST['action'] ?? null;
if (!$action) {
    respond(["success" => 0, "error" => 1, "message" => "Action not specified."]);
}

$response = ["action" => $action, "success" => 0, "error" => 0];

switch ($action) {

    // =========================================================
    // CREATE EVENT
    // =========================================================
    case "post_event":
        $ename        = $_POST['ename'] ?? '';
        $edetails     = $_POST['edetails'] ?? '';
        $start_date   = $_POST['start_date'] ?? '';
        $end_date     = $_POST['end_date'] ?? null;
        $start_time   = $_POST['start_time'] ?? '';
        $evenue       = $_POST['evenue'] ?? '';
        $district     = $_POST['district'] ?? 'Kampala';
        $etype        = $_POST['etype'] ?? '';
        $ecategory    = $_POST['ecategory'] ?? '';
        $efee         = $_POST['efee'] ?? '0';
        $posted_by    = $_POST['posted_by'] ?? '';
        $ewebsite     = $_POST['ewebsite'] ?? '';
        $user_id      = $_POST['user_id'] ?? '';
        $curreny_type = $_POST['curreny_type'] ?? '/-';
        $imageBase64  = $_POST['image'] ?? '';
        $ebarnar      = $_POST['ebarnar'] ?? 'default';
        $posted_date  = date("Y-m-d");

        $isitfree     = ((float)$efee == 0) ? 'yes' : 'no';
        $ebarnar_file = $ebarnar . '.png';

        // Save image if provided
        if (!empty($imageBase64)) {
            $imageData = base64_decode($imageBase64);
            $imagePath = $uploadDir . $ebarnar_file;
            if (file_put_contents($imagePath, $imageData) === false) {
                respond(["success" => 0, "error" => 1, "message" => "Image upload failed."]);
            }
        }

        $stmt = $con->prepare("
            INSERT INTO eventz (
                ename, edetails, start_date, end_date, start_time, evenue, district,
                etype, ecategory, isitfree, efee, curreny_type, posted_by, posted_date,
                ebarnar, ewebsite, estatus, user_id, deleted
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        $estatus = 0;
        $deleted = 0;

        $stmt->bind_param(
            "sssssssssssssssiiii",
            $ename, $edetails, $start_date, $end_date, $start_time, $evenue, $district,
            $etype, $ecategory, $isitfree, $efee, $curreny_type, $posted_by,
            $posted_date, $ebarnar_file, $ewebsite, $estatus, $user_id, $deleted
        );

        if ($stmt->execute()) {
            respond(["success" => 1, "message" => "Event posted successfully."]);
        } else {
            respond(["success" => 0, "error" => 1, "message" => "Database insert failed: " . $stmt->error]);
        }

        break;

    // =========================================================
    // APPROVE EVENT
    // =========================================================
    case "approve_event":
        $event_id = intval($_GET['event_id'] ?? 0);
        if ($event_id > 0) {
            $stmt = $con->prepare("UPDATE eventz SET estatus = 1 WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if ($stmt->execute()) {
                respond(["success" => 1, "message" => "Event approved."]);
            } else {
                respond(["success" => 0, "message" => "Approval failed."]);
            }
        }
        break;

    // =========================================================
    // DISAPPROVE EVENT
    // =========================================================
    case "disapprove_event":
        $event_id = intval($_GET['event_id'] ?? 0);
        if ($event_id > 0) {
            $stmt = $con->prepare("UPDATE eventz SET estatus = 0 WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if ($stmt->execute()) {
                respond(["success" => 1, "message" => "Event disapproved."]);
            } else {
                respond(["success" => 0, "message" => "Disapproval failed."]);
            }
        }
        break;

    // =========================================================
    // DELETE EVENT
    // =========================================================
    case "delete_event":
        $event_id = intval($_GET['event_id'] ?? 0);
        if ($event_id > 0) {
            $stmt = $con->prepare("DELETE FROM eventz WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if ($stmt->execute()) {
                respond(["success" => 1, "message" => "Event deleted."]);
            } else {
                respond(["success" => 0, "message" => "Deletion failed."]);
            }
        }
        break;

    // =========================================================
    // UPDATE EVENT
    // =========================================================
    case "update_event":
        $event_id    = $_POST['event_id'] ?? 0;
        $ename       = $_POST['ename'] ?? '';
        $edetails    = $_POST['edetails'] ?? '';
        $start_date  = $_POST['start_date'] ?? '';
        $start_time  = $_POST['start_time'] ?? '';
        $evenue      = $_POST['evenue'] ?? '';
        $etype       = $_POST['etype'] ?? '';
        $ecategory   = $_POST['ecategory'] ?? '';
        $efee        = $_POST['efee'] ?? '0';
        $posted_by   = $_POST['posted_by'] ?? '';
        $ewebsite    = $_POST['ewebsite'] ?? '';
        $user_id     = $_POST['user_id'] ?? '';

        $isitfree = ((float)$efee == 0) ? 'yes' : 'no';

        $stmt = $con->prepare("
            UPDATE eventz SET 
                ename = ?, edetails = ?, start_date = ?, start_time = ?, evenue = ?, 
                etype = ?, ecategory = ?, efee = ?, isitfree = ?, posted_by = ?, 
                ewebsite = ?, user_id = ? 
            WHERE event_id = ?
        ");

        $stmt->bind_param(
            "ssssssssssssi",
            $ename, $edetails, $start_date, $start_time, $evenue,
            $etype, $ecategory, $efee, $isitfree, $posted_by,
            $ewebsite, $user_id, $event_id
        );

        if ($stmt->execute()) {
            respond(["success" => 1, "message" => "Event updated successfully."]);
        } else {
            respond(["success" => 0, "message" => "Update failed: " . $stmt->error]);
        }

        break;

    default:
        respond(["success" => 0, "error" => 1, "message" => "Invalid action."]);
        break;
}

$con->close();
?>