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

    // Search in Bookings table
    $sql = "SELECT booking_id, pet_name, pet_breed, service_type, reservation_date, reservation_time, status 
            FROM bookings
            WHERE pet_name ILIKE :query 
            OR pet_breed ILIKE :query 
            OR service_type ILIKE :query 
            OR status ILIKE :query
            OR TO_CHAR(reservation_date, 'YYYY-MM-DD') ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $searchResults['Bookings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search in Adoption Requests table
    $sql = "SELECT id, user_id, pet_id, status, created_at 
            FROM adoption_requests
            WHERE status ILIKE :query
            OR TO_CHAR(created_at, 'YYYY-MM-DD') ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $searchResults['Adoption Requests'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search in Payments table
    $sql = "SELECT payment_id, booking_id, amount, payment_for, payment_status, payment_date 
            FROM payments
            WHERE payment_for ILIKE :query 
            OR payment_status ILIKE :query
            OR TO_CHAR(payment_date, 'YYYY-MM-DD') ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $searchResults['Payments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search in Contact Messages table
    $sql = "SELECT id, name, email, subject, message, created_at, username 
            FROM contact_messages
            WHERE name ILIKE :query 
            OR email ILIKE :query 
            OR subject ILIKE :query 
            OR message ILIKE :query
            OR username ILIKE :query
            OR TO_CHAR(created_at, 'YYYY-MM-DD') ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':query' => '%' . $query . '%']);
    $searchResults['Contact Messages'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
</body>
</html>
