<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['image']) && isset($_POST['name'])) {
        $image = $_POST['image'];
        $name = basename($_POST['name']); // sanitize

        // Directory for saving images
        $dir = __DIR__ . "/files/images/";

        // Make sure the folder exists
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Save image as provided (don’t force .png)
        $path = $dir . $name;

        // Save the decoded file
        if (file_put_contents($path, base64_decode($image))) {
            echo "success";
        } else {
            echo "failed to save file";
        }

    } else {
        echo "missing parameters";
    }

} else {
    echo "invalid request";
}
?>