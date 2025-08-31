<?php
include 'config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $searchResults = [];

    // Search in Users table
    $sql = "SELECT id, username, email, phone, created_at, role FROM users 
            WHERE username ILIKE :query 
            OR email ILIKE :query 
            OR phone ILIKE :query 
            OR TO_CHAR(created_at, 'YYYY-MM-DD') ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $searchResults['Users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comprehensive Search Results - Admin Dashboard</title>
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center">Admin Panel</h4>
        <a href="sample2.html">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_pets.php">Manage Pets</a>
        <a href="manage_adoptions.php">Manage Adoptions</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="bookings_history.php">Bookings History</a>
        <a href="settings.php">Manage Contacts</a>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>
    <div class="content">
        <h2>Comprehensive Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <?php foreach ($searchResults as $table => $results): ?>
            <?php if (!empty($results)): ?>
                <h3><?php echo htmlspecialchars($table); ?></h3>
                <table border="1">
                    <tr>
                        <?php foreach (array_keys($results[0]) as $col): ?>
                            <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No results found in <?php echo htmlspecialchars($table); ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>