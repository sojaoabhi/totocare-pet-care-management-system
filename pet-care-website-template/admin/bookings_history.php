<?php
session_start();
include 'config.php';

// Fetch completed, missed, or cancelled bookings
$sql = "SELECT b.booking_id, b.pet_name, b.pet_breed, b.service_type, b.reservation_date, b.reservation_time, b.status AS booking_status, p.amount, p.payment_status, u.username AS user_name, u.email AS user_email
        FROM bookings b
        JOIN payments p ON b.booking_id = p.booking_id
        JOIN users u ON b.user_id = u.id
        WHERE b.status IN ('Completed', 'Missed', 'Cancelled')
        ORDER BY b.reservation_date DESC, b.reservation_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking History - TotoCare</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="css/userstyle.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #333;
            color: #fff;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        p {
            text-align: center;
            font-size: 20px;
            color: #555;
        }
    </style>
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
            <h2>Booking History</h2>
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
            <?php if (!empty($bookings)): ?>
                <table>
                    <tr>
                        <th>Booking ID</th>
                        <th>Pet Name</th>
                        <th>Pet Breed</th>
                        <th>Service Type</th>
                        <th>Reservation Date</th>
                        <th>Reservation Time</th>
                        <th>Amount</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['booking_id']; ?></td>
                            <td><?php echo $booking['pet_name']; ?></td>
                            <td><?php echo $booking['pet_breed']; ?></td>
                            <td><?php echo $booking['service_type']; ?></td>
                            <td><?php echo $booking['reservation_date']; ?></td>
                            <td><?php echo $booking['reservation_time']; ?></td>
                            <td><?php echo $booking['amount']; ?></td>
                            <td><?php echo $booking['user_name']; ?></td>
                            <td><?php echo $booking['user_email']; ?></td>
                            <td><?php echo $booking['booking_status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No completed, missed, or cancelled bookings found.</p>
            <?php endif; ?>
        </div>
</div>
</body>
</html>
