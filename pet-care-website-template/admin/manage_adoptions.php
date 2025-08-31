<?php
session_start();
include 'config.php';
//include 'auth.php';

// Ensure only admin can access
/*if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}*/

// Fetch all approved adoption requests
$sql = "SELECT ar.id, ar.status, ar.created_at, p.pet_name, p.breed, p.age, p.gender, u.username AS adopter_name, u.email AS adopter_email
        FROM adoption_requests ar
        JOIN pets p ON ar.pet_id = p.id
        JOIN users u ON ar.user_id = u.id where ar.status = 'Approved'
        ORDER BY ar.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$approved_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Total Approved Adoptions - TotoCare</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="css/userstyle.css">
    <!--<style>
        body {
        font-family: 'Roboto', sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        height: 100vh;
        width: 250px;
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
    }

    .sidebar h4 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: #ecf0f1;
    }

    .sidebar a {
        color: #ecf0f1;
        text-decoration: none;
        display: block;
        padding: 12px;
        border-radius: 5px;
        margin: 5px 0;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #34495e;
    }

    .sidebar a.text-danger:hover {
        background-color: #c0392b;
    }

    .container-fluid {
        margin-left: 250px;
        padding: 20px;
    }

    h2 {
        color: #34495e;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .card {
        /*display: inline-block;
        width: calc(33% - 20px); /* Three cards per row with spacing */
        margin: 10px;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #ecf0f1;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .card h5 {
        font-size: 20px;
        color: #2c3e50;
        margin: 0;
    }

    .card h3 {
        font-size: 36px;
        font-weight: bold;
        margin: 10px 0;
        color: #2980b9;
    }

    .card:hover {
        background-color: #bdc3c7;
        transform: scale(1.05);
    }

    .text-danger:hover {
        color: #e74c3c;
    }

    </style>-->
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
            <h2>Total Approved Adoptions</h2>
            <?php if (!empty($approved_requests)): ?>
                <table>
                    <tr>
                        <th>Request ID</th>
                        <th>Pet Name</th>
                        <th>Breed</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Adopter Name</th>
                        <th>Adopter Email</th>
                        <th>Approval Date</th>
                    </tr>
                    <?php foreach ($approved_requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['id']); ?></td>
                            <td><?php echo htmlspecialchars($request['pet_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['breed']); ?></td>
                            <td><?php echo htmlspecialchars($request['age']); ?></td>
                            <td><?php echo htmlspecialchars($request['gender']); ?></td>
                            <td><?php echo htmlspecialchars($request['adopter_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['adopter_email']); ?></td>
                            <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No approved adoption requests found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
