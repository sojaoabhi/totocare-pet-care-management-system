<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store booking data in session
    $_SESSION['booking'] = [
        'pet_name' => $_POST['pet_name'],
        'pet_breed' => $_POST['pet_breed'],
        'pet_age' => $_POST['pet_age'],
        'service_type' => $_POST['service_type'],
        'reservation_date' => $_POST['reservation_date'],
        'reservation_time' => $_POST['reservation_time']
    ];

    // Redirect to payment page
    header("Location: payment.html");
    exit();
}
?>
