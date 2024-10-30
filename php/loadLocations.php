<?php

     ob_start();

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    $executionStartTime = microtime(true);

    include('../config/config.php');

    header('Content-Type: application/json; charset=UTF-8');

    $conn = new mysqli($cd_host, $cd_user, $cd_password, $cd_dbname, $cd_port, $cd_socket);

    if (mysqli_connect_errno()) {
        $output = [
            'status' => [
                'code' => "300",
                'name' => "failure",
                'description' => "database unavailable",
                'returnedIn' => (microtime(true) - $executionStartTime) / 1000 . " ms"
            ],
            'data' => []
        ];
        mysqli_close($conn);
        echo json_encode($output);
        exit;
    }

    $query = 'SELECT id, name FROM location ORDER BY name';
    $result = $conn->query($query);

    if (!$result) {
        $output = [
            'status' => [
                'code' => "400",
                'name' => "error",
                'description' => "query failed",
                'returnedIn' => (microtime(true) - $executionStartTime) / 1000 . " ms"
            ],
            'data' => []
        ];
        mysqli_close($conn);
        echo json_encode($output);
        exit;
    }

    $locations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $locations[] = $row;
    }

    $output = [
        'status' => [
            'code' => "200",
            'name' => "ok",
            'description' => "success",
            'returnedIn' => (microtime(true) - $executionStartTime) / 1000 . " ms"
        ],
        'data' => $locations
    ];

    mysqli_close($conn);
    ob_end_clean();
    echo json_encode($output);