<?php
include 'config.php';

$sql = "SELECT id, username, email, phone, created_at FROM users ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
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
            <div class="container mt-4">
                <h2>Manage Users</h2>
                <div class="google-search-bar" style="margin: 20px auto; display: flex; justify-content: center; align-items: center;">
                    <form method="GET" action="search_user.php" style="display: flex; align-items: center; width: 100%; max-width: 500px;">
                        <input type="text" name="query" placeholder="Search by username, email or phone" required
                               style="width: 100%; padding: 12px 20px; border-radius: 50px; border: 1px solid #ddd; outline: none; font-size: 18px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <button type="submit" class="btn btn-primary" 
                                style="margin-left: -50px; padding: 12px 20px; border: none; background-color: #4285f4; color: white; border-radius: 50%; cursor: pointer; transition: background-color 0.3s ease;">
                            &#128269;
                        </button>
                    </form>
                </div>
                <table class="table table-striped">
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td>
                                <!--<a href="delete_user.php?id=<?= $user['id'] ?>" style="color: red;">Delete</a>-->
                                <a href="delete_user.php?id=<?= $user['id'] ?>" 
                                style="display: inline-block; color: white; background-color: #e53e3e; padding: 8px 16px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: all 0.3s ease;">
                                Delete
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
</div>

</body>
</html>
