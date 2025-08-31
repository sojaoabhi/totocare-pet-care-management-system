<?php
/*session_start();
include 'config.php';  // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to make a booking.'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_SESSION['user_id'];  // Get the logged-in user's ID
    $pet_name = $_POST['pet_name'];
    $pet_breed = $_POST['pet_breed'];
    $pet_age = $_POST['pet_age'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $service_type = $_POST['service_type'];

    // Convert date to the correct format (YYYY-MM-DD)
    $formatted_date = date("Y-m-d", strtotime($reservation_date));

    // SQL query to insert booking details into the database
    $sql = "INSERT INTO bookings (user_id, pet_name, pet_breed, pet_age, reservation_date, reservation_time, service_type) 
            VALUES (:user_id, :pet_name, :pet_breed, :pet_age, :reservation_date, :reservation_time, :service_type)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':pet_name' => $pet_name,
        ':pet_breed' => $pet_breed,
        ':pet_age' => $pet_age,
        ':reservation_date' => $formatted_date,
        ':reservation_time' => $reservation_time,
        ':service_type' => $service_type,
    ]);

    echo "<script>alert('Booking successful!Please check schedule in dashboard'); window.location.href='dashboard.php';</script>";
}*/
?>
