<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($phone) && !empty($password)) {
        try {
            // Check if the username or email already exists
            $checkSql = "SELECT COUNT(*) FROM users WHERE email = ? OR username = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$email, $username]);
            $userExists = $checkStmt->fetchColumn();

            if ($userExists > 0) {
                echo "<script>alert('Email or Phone Number already exists! Please try logging in.'); window.location.href = 'login.html';</script>";
                exit;
            }

            // Hash password before storing
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $phone, $hashedPassword]);

            echo "<script>alert('Registration successful! Redirecting to login.'); window.location.href = 'login.html';</script>";
            exit;
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}



?>