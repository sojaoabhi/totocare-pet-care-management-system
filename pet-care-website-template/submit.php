<?php
/*echo "<pre>";
print_r($_POST);
echo "</pre>";
exit();*/
include 'auth.php';
include 'config.php'; // Database connection file

/* Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    //die("Unauthorized access. Please log in.");
    header("Location: login.html"); // Redirect to login if not logged in
    exit();
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $user_id = $_SESSION['user_id'];
        $pet_name = $_POST['pet_name'] ?? '';
        $pet_type = $_POST['pet_type'] ?? '';
        $breed = $_POST['breed'] ?? '';
        $age = $_POST['age'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $other_type = $_POST['other_type'] ?? '';
        $reason = $_POST['reason_for_adoption'] ?? '';

        // Health Details (Convert "Yes" to TRUE, "No" to FALSE)
        $vaccinated = $_POST['vaccinated'] ?? 'no';
        $neutered = $_POST['neutered'] ?? 'no';
        $spayed = $_POST['spayed'] ?? 'no';
        $shots = $_POST['shots'] ?? 'no';
        $dewormed = $_POST['dewormed'] ?? 'no';

        // Behavior & Compatibility
        $energy_level = $_POST['energy_level'] ?? '';
        $trained_level = $_POST['trained_name'] ?? '';
        $good_with_dogs = $_POST['good_with_dogs'] ?? 'no';
        $good_with_cats = $_POST['good_with_cats'] ?? 'no';
        $good_with_kids = $_POST['good_with_kids'] ?? 'no';

        // Address Details
        $address_type = $_POST['address_type'] ?? '';
        $nationality = $_POST['nationality'] ?? '';
        $state = $_POST['state'] ?? '';
        $district = $_POST['district'] ?? '';
        $city = $_POST['city'] ?? '';
        $pin_code = $_POST['pin_code'] ?? '';
        
        // Handle Image Uploads
        $image_paths = [];
        if (!empty($_FILES['pet_images']['name'][0])) {
            foreach ($_FILES['pet_images']['tmp_name'] as $index => $tmp_name) {
                if ($_FILES['pet_images']['error'][$index] === UPLOAD_ERR_OK) {
                    $file_ext = pathinfo($_FILES['pet_images']['name'][$index], PATHINFO_EXTENSION);
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array(strtolower($file_ext), $allowed_exts)) {
                        die("Invalid image format! Only JPG, JPEG, PNG, GIF allowed.");
                    }

                    $file_name = uniqid("pet_", true) . "." . $file_ext;
                    $target_file = "uploads/" . $file_name;
                    
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $image_paths[] = $target_file;
                    } else {
                        die("Error uploading file: " . $_FILES['pet_images']['name'][$index]);
                    }
                }
            }
        }

        // Convert images array to PostgreSQL array format
        $image_paths = '{' . implode(',', $image_paths) . '}';

        // Insert into pets table
        $sql = "INSERT INTO pets (pet_name, pet_type, breed, age, gender, other_type, reason_for_adoption, 
        vaccinated, neutered, spayed, shots, dewormed, good_with_dogs, good_with_cats, good_with_kids, 
        energy_level, trained_level, address_type, nationality, state, district, city, pin_code, images, owner_id) 
        VALUES (:pet_name, :pet_type, :breed, :age, :gender, :other_type, :reason, 
        :vaccinated, :neutered, :spayed, :shots, :dewormed, :good_with_dogs, :good_with_cats, :good_with_kids, 
        :energy_level, :trained_level, :address_type, :nationality, :state, :district, :city, :pin_code, :images, :owner_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pet_name' => $pet_name,
            ':pet_type' => $pet_type,
            ':breed' => $breed,
            ':age' => $age,
            ':gender' => $gender,
            ':other_type' => $other_type,
            ':reason' => $reason,
            ':vaccinated' => $vaccinated,
            ':neutered' => $neutered,
            ':spayed' => $spayed,
            ':shots' => $shots,
            ':dewormed' => $dewormed,
            ':good_with_dogs' => $good_with_dogs,
            ':good_with_cats' => $good_with_cats,
            ':good_with_kids' => $good_with_kids,
            ':energy_level' => $energy_level,
            ':trained_level' => $trained_level,
            ':address_type' => $address_type,
            ':nationality' => $nationality,
            ':state' => $state,
            ':district' => $district,
            ':city' => $city,
            ':pin_code' => $pin_code,
            ':images' => $image_paths,
            ':owner_id' => $user_id
        ]);


        echo "<script>alert('Pet listed successfully!'); window.location.href='index1.php';</script>";
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
