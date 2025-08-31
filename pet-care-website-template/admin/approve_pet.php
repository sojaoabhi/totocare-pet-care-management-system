<?php
include 'config.php'; // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if (!isset($_GET['id'])) {
    die("Invalid Pet ID");
}

$pet_id = $_GET['id'];

$sql = "UPDATE pets SET status = 'Approved' WHERE id = :pet_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':pet_id' => $pet_id]);


$sql = "SELECT u.email AS owner_email 
        FROM users u
        JOIN pets p ON u.id = p.owner_id
        WHERE p.id = :pet_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':pet_id' => $pet_id]);

// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $owner_email = $result['owner_email'];
    //echo "Owner Email: " . $owner_email; // Just to check if it works
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
    $mail->Subject = "Your Pet Listing Has Been Approved - TotoCare Adoption Page";
    $mail->Body = " <h3>Dear $owner_name,</h3>
            <p>We are thrilled to inform you that your pet listing has been successfully approved and is now live on the TotoCare adoption page.</p>
            <p>Your pet is now available for potential adopters to view and connect with you.</p>
            <p>We truly appreciate your effort to find a loving home and for choosing TotoCare to assist in the adoption process.</p>
            <br>
            <p>Best regards,<br>The TotoCare Team<br>totocare.contact@gmail.com</p>
            ";
    $mail->send();
    //echo "<script>alert('Adoption request submitted! The pet owner will be notified.'); window.location.href='listing.html';</script>";
    echo "<script>alert('Pet approved successfully!'); window.location.href='manage_pets.php';</script>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
//echo "<script>alert('Pet approved successfully!'); window.location.href='manage_pets.php';</script>";


} else {
    echo "Owner email not found.";
}
?>
