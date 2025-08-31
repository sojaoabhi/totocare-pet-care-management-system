<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch upcoming bookings for the logged-in user
$sql = "SELECT booking_id, pet_name, pet_breed, service_type, reservation_date, reservation_time, status
        FROM bookings
        WHERE user_id = :user_id AND reservation_date >= CURRENT_DATE AND status = 'Pending'
        ORDER BY reservation_date, reservation_time";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheduled Bookings - TotoCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
        <h2 style="color: #ED6436;">Toto<span style="color: #fff;">Care</span></h2>
        </div>
        <a href="user_dashboard.php"><i class="fa fa-home"></i> Home</a>
        <a href="manage_adoptions2.php"><i class="fa fa-paw"></i> Manage Adoptions</a>
        <a href="scheduled_bookings.php"><i class="fa fa-calendar"></i> Scheduled Bookings</a>
        <a href="user_history.php"><i class="fa fa-history"></i> History</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <h2>Scheduled Bookings</h2>
        <?php if (!empty($bookings)): ?>
            <table border="1">
                <tr>
                    <th>Booking ID</th>
                    <th>Pet Name</th>
                    <th>Breed</th>
                    <th>Service Type</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Status</th>
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
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No upcoming bookings found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
