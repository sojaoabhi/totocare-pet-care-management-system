<?php
session_start();
include 'config.php';
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Update status to "Approved"
        $sql = "UPDATE adoption_requests SET status = 'Approved' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Fetch adopter's email
        $sql = "SELECT users.email, users.name FROM adoption_requests
                JOIN users ON adoption_requests.adopter_id = users.id
                WHERE adoption_requests.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $adopter = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($adopter) {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'totocare.contact@gmail.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('totocare.contact@gmail.com', 'TotoCare');
            $mail->addAddress($adopter['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Adoption Request Approved';
            $mail->Body = "Dear " . htmlspecialchars($adopter['name']) . ",<br>Your adoption request has been approved!";

            if ($mail->send()) {
                echo "Adoption request approved and email sent!";
            } else {
                echo "Adoption request approved, but email could not be sent.";
            }
        }

        header("Location: user_dashboard.php");
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
