<?php
session_start();
include 'config.php';
//include 'auth.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Fetch all completed bookings with payment status as "Paid"
$sql = "SELECT b.booking_id, b.pet_name, b.pet_breed, b.service_type, b.reservation_date, b.reservation_time, b.status, p.amount, u.username AS user_name, u.email AS user_email
        FROM bookings b
        JOIN payments p ON b.booking_id = p.booking_id
        JOIN users u ON b.user_id = u.id
        WHERE p.payment_status = 'Paid' AND b.status = 'Pending'
        ORDER BY b.reservation_date";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle booking status update or cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['booking_id'])) {
    $action = $_POST['action'];
    $booking_id = $_POST['booking_id'];
    $status = ($action === 'complete') ? 'Completed' : (($action === 'missed') ? 'Missed' : 'Cancelled');

    // Update booking status
    $sql = "UPDATE bookings SET status = :status WHERE booking_id = :booking_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $status, ':booking_id' => $booking_id]);

    // Get user details for email
    $user_email = $_POST['user_email'];
    $user_name = $_POST['user_name'];
    $pet_name = $_POST['pet_name'];
    $service_type = $_POST['service_type'];

    // Send email notification to the user
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
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = "Booking Status Update - TotoCare";
        
        if ($action === 'cancel') {
            $refund_message = "Your booking has been cancelled. A refund process has been initiated.";
        } else {
            $refund_message = "";
        }

        $mail->Body = "
            <h3>Dear $user_name,</h3>
            <p>Your booking for <b>$service_type</b> service for your pet <b>$pet_name</b> has been updated to <b>$status</b>.</p>
            <p>$refund_message</p>
            <p>Thank you for choosing TotoCare!</p>
        ";
        
        $mail->send();
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }

    header("Location: manage_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings - TotoCare</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center">Admin Panel</h4>
        <a href="index.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_pets.php">Manage Pets</a>
        <a href="manage_adoptions.php">Manage Adoptions</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="bookings_history.php">Bookings History</a>
        <a href="settings.php">Manage Contacts</a>
        <a href="logout.php" class="text-danger">Logout</a>
        </div>
    <div class="content">
        <h2>Manage Bookings</h2>
        <!--<div class="google-search-bar" style="margin: 20px auto; display: flex; justify-content: center; align-items: center;">
                    <form method="GET" action="search_bookings.php" style="display: flex; align-items: center; width: 100%; max-width: 500px;">
                        <input type="text" name="query" placeholder="Search by booking id" required
                               style="width: 100%; padding: 12px 20px; border-radius: 50px; border: 1px solid #ddd; outline: none; font-size: 18px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <button type="submit" class="btn btn-primary" 
                                style="margin-left: -50px; padding: 12px 20px; border: none; background-color: #4285f4; color: white; border-radius: 50%; cursor: pointer; transition: background-color 0.3s ease;">
                            &#128269;
                        </button>
                    </form>
                </div>-->
        <?php if (!empty($bookings)): ?>
            <div class="google-search-bar" style="margin: 20px auto; display: flex; justify-content: center; align-items: center;">
                    <form method="GET" action="search_bookings.php" style="display: flex; align-items: center; width: 100%; max-width: 500px;">
                        <input type="text" name="query" placeholder="Search by booking id" required
                               style="width: 100%; padding: 12px 20px; border-radius: 50px; border: 1px solid #ddd; outline: none; font-size: 18px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <button type="submit" class="btn btn-primary" 
                                style="margin-left: -50px; padding: 12px 20px; border: none; background-color: #4285f4; color: white; border-radius: 50%; cursor: pointer; transition: background-color 0.3s ease;">
                            &#128269;
                        </button>
                    </form>
                </div>
            <table border="1">
                <tr>
                    <th>Booking ID</th>
                    <th>Pet Name</th>
                    <th>Breed</th>
                    <th>Service Type</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['pet_breed']); ?></td>
                        <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                        <td><?php echo htmlspecialchars($booking['reservation_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['reservation_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td><?php echo htmlspecialchars($booking['amount']); ?> INR</td>
                        <td>
                            <?php if ($booking['status'] !== 'Completed' && $booking['status'] !== 'Cancelled'): ?>
                                <form method="post">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                    <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($booking['user_email']); ?>">
                                    <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($booking['user_name']); ?>">
                                    <input type="hidden" name="pet_name" value="<?php echo htmlspecialchars($booking['pet_name']); ?>">
                                    <input type="hidden" name="service_type" value="<?php echo htmlspecialchars($booking['service_type']); ?>">
                                    <button type="submit" name="action" value="complete" class="approve">Complete</button>
                                    <button type="submit" name="action" value="missed" class="missed">Missed</button>
                                    <button type="submit" name="action" value="cancel" class="reject">Cancel</button>
                                </form>
                            <?php else: ?>
                                <?php echo htmlspecialchars($booking['status']); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
