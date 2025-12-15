<?php
$cd_host = "db5019201462.hosting-data.io ";  // Your database server
$cd_port = 3306;
$cd_socket = "";

$cd_dbname = "company directory";
$cd_user = "Susan";
$cd_password = "M4s0ns4sh4";

// Create connection
$conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port, $cd_socket);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
