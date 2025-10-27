<?php
// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Directory where your images are stored
$baseDir = __DIR__ . '/files/ads_` images/';

// Get the image name from the query parameter
if (isset($_GET['img'])) {
    $filename = basename($_GET['img']); // sanitize
    $filepath = $baseDir . $filename;

    if (file_exists($filepath)) {
        // Get extension and map to MIME type
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $mimeType = 'image/jpeg';
                break;
            case 'png':
                $mimeType = 'image/png';
                break;
            case 'gif':
                $mimeType = 'image/gif';
                break;
            case 'webp':
                $mimeType = 'image/webp';
                break;
            default:
                $mimeType = 'application/octet-stream';
        }

        // Send the correct headers
        header("Content-Type: $mimeType");

        // Output the image
        readfile($filepath);
        exit;
    } else {
        // File not found
        http_response_code(404);
        echo "Image not found.";
    }
} else {
    // No filename provided
    http_response_code(400);
    echo "No image specified.";
}