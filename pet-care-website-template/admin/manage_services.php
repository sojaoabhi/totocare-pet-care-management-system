<?php
include 'config.php'; // Database connection

$sql = "SELECT id, name, email, phone, service_type, status FROM service_providers ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Service Providers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Manage Service Providers</h2>
        <table class="table table-striped">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Service Type</th><th>Status</th><th>Actions</th>
            </tr>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= $service['id'] ?></td>
                    <td><?= htmlspecialchars($service['name']) ?></td>
                    <td><?= htmlspecialchars($service['email']) ?></td>
                    <td><?= htmlspecialchars($service['phone']) ?></td>
                    <td><?= htmlspecialchars($service['service_type']) ?></td>
                    <td><?= htmlspecialchars($service['status']) ?></td>
                    <td>
                        <a href="approve_service.php?id=<?= $service['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-danger btn-sm">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
