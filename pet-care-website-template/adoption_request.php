<?php
session_start();
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access. Please log in.");
}

if (!isset($_GET['id'])) {
    die("Invalid pet ID.");
}

$user_id = $_SESSION['user_id'];
$pet_id = $_GET['id'];
$status = "Pending"; // Default status

// Check if adoption request already exists
$check_sql = "SELECT * FROM adoption_requests WHERE user_id = :user_id AND pet_id = :pet_id";
$check_stmt = $pdo->prepare($check_sql);
$check_stmt->execute([':user_id' => $user_id, ':pet_id' => $pet_id]);

if ($check_stmt->rowCount() > 0) {
    die("You have already requested adoption for this pet.");
}

// Insert adoption request
$sql = "INSERT INTO adoption_requests (user_id, pet_id, status) VALUES (:user_id, :pet_id, :status)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id, ':pet_id' => $pet_id, ':status' => $status]);

// Notify pet owner (optional)
$sql_owner = "SELECT users.email FROM users JOIN pets ON users.id = pets.owner_id WHERE pets.id = :pet_id";
$stmt_owner = $pdo->prepare($sql_owner);
$stmt_owner->execute([':pet_id' => $pet_id]);
$owner = $stmt_owner->fetch(PDO::FETCH_ASSOC);

if ($owner) {
    $owner_email = $owner['email'];
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'totocare.contact@gmail.com';
        $mail->Password = 'osdkllcqdffaumwb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('totocare.contact@gmail.com', 'TotoCare');
        $mail->addAddress($owner_email);
        $mail->isHTML(true);
        $mail->Subject = "Adoption Request for Your Pet";
        $mail->Body = "Someone has requested to adopt your pet. Please check your dashboard.";
        $mail->send();
        echo "<script>alert('Adoption request submitted! The pet owner will be notified.'); window.location.href='listing.html';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

//echo "<script>alert('Adoption request submitted! The pet owner will be notified.'); window.location.href='listing.html';</script>";
?>