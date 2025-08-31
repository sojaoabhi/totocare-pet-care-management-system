<?php
include 'config.php'; // Database connection

// Fetch total counts
$sql = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM pets) AS total_pets,
        (SELECT COUNT(*) FROM pets WHERE status = 'Pending') AS pending_requests,
        (SELECT COUNT(*) FROM adoption_requests WHERE status = 'Approved') AS total_adoptions,
        (SELECT COUNT(*) FROM bookings WHERE reservation_date >= CURRENT_DATE - INTERVAL '1 month' AND reservation_date < CURRENT_DATE) As today_month_bookings

";

try {
    $stmt = $pdo->query($sql);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ensure stats are set, otherwise return zero
    $stats = $stats ? $stats : [
        'total_users' => 0,
        'total_pets' => 0,
        'pending_requests' => 0,
        'total_adoptions' => 0,
        'today_month_bookings' => 0
    ];
    
    header('Content-Type: application/json');
    echo json_encode($stats);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>

