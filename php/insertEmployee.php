<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$executionStartTime = microtime(true);

include('../config/config.php');

header('Content-Type: application/json; charset=UTF-8');

// Check for required POST fields
if (!isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['department'], $_POST['location'])) {
    $response['status'] = ['code' => 400, 'description' => 'Missing required fields.'];
    echo json_encode($response);
    exit;
}

// Get the data from the AJAX request
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$departmentId = $_POST['department'];  // Ensure this is correctly sent
$locationId = $_POST['location'];  // Location ID

// Prepare the SQL statement
$sql = "INSERT INTO personnel (firstName, lastName, email, department_id, location_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if the statement preparation succeeded
if (!$stmt) {
    $response['status'] = ['code' => 500, 'description' => 'SQL prepare failed: ' . $conn->error];
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ssssss", $firstName, $lastName, $email, $departmentId, $locationId);

// Execute the statement and check for errors
$response = [];
if ($stmt->execute()) {
    $response['status'] = ['code' => 200, 'description' => 'Employee added successfully.'];
} else {
    $response['status'] = ['code' => 500, 'description' => 'Error adding employee: ' . $stmt->error];
}

$stmt->close();
$conn->close();

// Return the response as JSON
echo json_encode($response);
