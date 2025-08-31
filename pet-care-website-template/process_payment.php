<?php
session_start();
include 'config.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Fetch booking data from session
        $user_id = $_SESSION['user_id'];
        $booking = $_SESSION['booking'];
        $service = ucfirst($booking['service_type']);
        $amount = $_POST['amount'];
        $payment_status = "Paid";

        // Store booking in the database
        $sql = "INSERT INTO bookings (user_id,pet_name, pet_breed, pet_age, service_type, reservation_date, reservation_time) 
                VALUES (:user_id, :pet_name, :pet_breed, :pet_age, :service_type, :reservation_date, :reservation_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':pet_name' => $booking['pet_name'],
            ':pet_breed' => $booking['pet_breed'],
            ':pet_age' => $booking['pet_age'],
            ':service_type' => $service,
            //':reservation_date' => $booking['reservation_date'],
            ':reservation_date' => DateTime::createFromFormat('m/d/Y', $booking['reservation_date'])->format('Y-m-d'),

            ':reservation_time' => $booking['reservation_time']
        ]);

        // Get the last inserted booking ID
        $booking_id = $pdo->lastInsertId();

        // Store payment details
        $sql = "INSERT INTO payments (booking_id, amount, payment_for, payment_status) 
                VALUES (:booking_id, :amount, :payment_for, :payment_status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':booking_id' => $booking_id,
            ':amount' => $amount,
            ':payment_for' => $service,
            ':payment_status' => $payment_status
        ]);

        /* Get user email (Assuming you store it in the session or database)
        $user_email = $_SESSION['user_email'];
        $user_name = $_SESSION['user_name'];*/
       // $user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

        // Fetch user details from the database
        $sql = "SELECT username, email FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_email = $user['email'];
            $user_name = $user['username'];
        } else {
            echo "<script>alert('User not found.'); window.location.href='login.html';</script>";
            exit();
        }

        /*$sql = "SELECT username, email FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
        echo "User Email: " . $user['email'] . "<br>";
        echo "User Name: " . $user['username'] . "<br>";
        } else {
        echo "No user found with ID: " . $_SESSION['user_id'];
        exit;
        }*/


        // Send Email Confirmation
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'totocare.contact@gmail.com';  // Your Gmail address
            $mail->Password = 'osdkllcqdffaumwb';         // Your Gmail password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('totocare.contact@gmail.com', 'TotoCare');
            $mail->addAddress($user_email, $user_name);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Booking Confirmation - TotoCare';
            $mail->Body = "
                <h2>Booking Confirmation</h2>
                <p>Dear $user_name,</p>
                <p>Thank you for using TotoCare! Your booking has been confirmed successfully.</p>
                <h3>Booking Details:</h3>
                <ul>
                    <li>Booking ID: $booking_id</li>
                    <li>Service Type: $service</li>
                    <li>Amount Paid: ₹$amount</li>
                    <li>Reservation Date: {$booking['reservation_date']}</li>
                    <li>Reservation Time: {$booking['reservation_time']}</li>
                </ul>
                <p>We look forward to serving you and your pet!</p>
                <p>Best Regards,<br>TotoCare Team</p>
            ";

            // Send email
            $mail->send();
            echo "<script>alert('Payment Successful! Booking confirmed. An email confirmation has been sent.'); window.location.href='user_dashboard.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Payment Successful, but email could not be sent. Error: {$mail->ErrorInfo}'); window.location.href='user_dashboard.php';</script>";
        }

        // Clear session data
        unset($_SESSION['booking']);
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
