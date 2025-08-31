<?php
include 'config.php';

$sql = "SELECT id, pet_name, pet_type, breed, status FROM pets ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Pets</title>
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
            <h2>Manage Pets</h2>
            <?php if (!empty($pets)): ?>
            <table class="table table-striped">
                <tr>
                    <th>ID</th><th>Name</th><th>Type</th><th>Breed</th><th>Status</th><th>Actions</th>
                </tr>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><?= $pet['id'] ?></td>
                        <td><?= htmlspecialchars($pet['pet_name']) ?></td>
                        <td><?= htmlspecialchars($pet['pet_type']) ?></td>
                        <td><?= htmlspecialchars($pet['breed']) ?></td>
                        <td><?= htmlspecialchars($pet['status']) ?></td>
                        <td>
                            <a href="approve_pet.php?id=<?= $pet['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="delete_pet.php?id=<?= $pet['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>No pets for adoption found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>            
</body>
</html>
