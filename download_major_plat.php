<?php
require_once "config.php";

if (!isset($_GET['id'])) {
    die("Missing file ID.");
}

$plat_id = intval($_GET['id']);

$conn = getDBConnection();

// Query using the correct new column names
$sql = "SELECT plat_file, plat_file_name, plat_file_type, plat_file_size
        FROM major_subdivision_plat_applications
        WHERE form_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plat_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("File not found.");
}

$stmt->bind_result($fileData, $fileName, $fileType, $fileSize);
$stmt->fetch();

if ($fileData === null) {
    die("No file stored for this record.");
}

// Send file headers
header("Content-Type: " . $fileType);
header("Content-Length: " . $fileSize);
header('Content-Disposition: attachment; filename="' . $fileName . '"');

echo $fileData;
exit;
?>
