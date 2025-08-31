<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$pet_id = $_GET['id'];

// Fetch pet details from the database
$sql = "SELECT pets.*, users.username AS owner_name, users.email AS owner_email
        FROM pets
        JOIN users ON pets.owner_id = users.id
        WHERE pets.id = :pet_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':pet_id' => $pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    die("Pet not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .pet-image { width: 100%; border-radius: 10px; }
        .info { margin-top: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background: green; color: white; text-decoration: none; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($pet['pet_name']); ?></h1>
        <img class="pet-image" src="<?= explode(',', trim($pet['images'], '{}'))[0] ?>" alt="<?= htmlspecialchars($pet['pet_name']); ?>">
        
        <div class="info">
            <p><strong>Type:</strong> <?= htmlspecialchars($pet['pet_type']); ?></p>
            <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']); ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($pet['gender']); ?></p>
            <p><strong>Reason for Adoption:</strong> <?= htmlspecialchars($pet['reason_for_adoption']); ?></p>
            <p><strong>Owner:</strong> <?= htmlspecialchars($pet['owner_name']); ?></p>
        </div>

        <a href="listing.html" class="btn">Back to Listings</a>
        <a href="adoption_request.php?id=<?= $pet['id']; ?>" class="btn btn-success">Adopt Now</a>

    </div>
</body>
</html>
