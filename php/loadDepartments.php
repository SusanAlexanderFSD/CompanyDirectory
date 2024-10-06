<?php
// Set error reporting for debugging purposes
ini_set('display_errors', 'On');
error_reporting(E_ALL);

// Start measuring execution time
$executionStartTime = microtime(true);

// Include database configuration
include('../config/config.php');

// Set response header
header('Content-Type: application/json; charset=UTF-8');

// Create database connection
$conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port, $cd_socket);

// Check for database connection errors
if (mysqli_connect_errno()) {
    $output['status']['code'] = "300";
    $output['status']['name'] = "failure";
    $output['status']['description'] = "database unavailable";
    $output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
    $output['data'] = [];

    mysqli_close($conn);
    echo json_encode($output);
    exit;
}

// Define SQL query
$query = 'SELECT p.lastName, p.firstName, p.email, d.name AS department, l.name AS location
          FROM personnel p
          LEFT JOIN department d ON (d.departmentID = p.departmentID)
          LEFT JOIN location l ON (l.locationID = d.locationID)
          ORDER BY p.lastName, p.firstName, d.name, l.name';

// Execute query
$result = $conn->query($query);

// Check if query execution failed
if (!$result) {
    $output['status']['code'] = "400";
    $output['status']['name'] = "executed";
    $output['status']['description'] = "query failed";
    $output['data'] = [];

    mysqli_close($conn);

    echo json_encode($output);
    exit;
}

// Fetch data and build the output array
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = $data;

// Close database connection
mysqli_close($conn);

// Output JSON response
echo json_encode($output);
