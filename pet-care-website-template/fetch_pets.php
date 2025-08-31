<?php
include 'config.php'; // Database connection
/*$pet_type = isset($_GET['pet_type']) ? trim($_GET['pet_type']) : '';
$state = isset($_GET['state']) ? trim($_GET['state']) : '';
$city = isset($_GET['city']) ? trim($_GET['city']) : '';*/

$sql = "SELECT id, pet_name, pet_type, breed, age, gender, images,city FROM pets  WHERE status = 'Approved'  ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pets as &$pet) {
    if (!empty($pet['images']) && $pet['images'] !== '{}') {
        $images = str_replace(['{', '}', ' '], '', $pet['images']); 
        $pet['images'] = explode(',', $images);
    } else {
        $pet['images'] = []; // Set to an empty array if no images
    }
}


header('Content-Type: application/json');
echo json_encode($pets);



?>
