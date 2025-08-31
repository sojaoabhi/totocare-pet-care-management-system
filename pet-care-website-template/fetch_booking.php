<?php

session_start();
header('Content-Type: application/json');

if (isset($_SESSION['booking']) && isset($_SESSION['booking']['service_type'])) {
    $service_type = $_SESSION['booking']['service_type'];
    $amount = 0;

    // Calculate the amount based on the service type
    switch (strtolower($service_type)) {  // Use strtolower to handle case insensitivity
        case "pet grooming":
            $amount = 500;
            break;
        case "pet boarding":  // Fixed typo here
            $amount = 1000;
            break;
        case "pet feeding":
            $amount = 300;
            break;
        case "pet training":
            $amount = 800;
            break;
        default:
            echo json_encode(['error' => 'Unknown service type']);
            exit;
    }

    echo json_encode([
        'service_type' => ucfirst($service_type),
        'amount' => $amount
    ]);
} else {
    echo json_encode(['error' => 'No booking data found']);
}


?>
