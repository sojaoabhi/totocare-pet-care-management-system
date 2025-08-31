<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Invalid request!'); window.location.href = 'login.html';</script>";
    exit;
}

// Debugging: Check what is received
if (empty($_POST)) {
    echo "<script>alert('No data received!');</script>";
    exit;
}

// Check if username and password exist
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

/*if (!empty($username) && !empty($password)) {
    try {
        // Check if user exists
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            echo "<script>alert('Login successful!'); window.location.href = 'index1.php';</script>";
           // echo "<script>alert('Login successful!');header('Location: index.html'); exit();;</script>";
            exit;
        } else {
            echo "<script>alert('Invalid username or password!'); window.location.href = 'login.html';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
} else {
    echo "<script>alert('All fields are required!');</script>";
}*/
if (!empty($username) && !empty($password)) {
    try {
        // Check if user exists and fetch role
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store role in session

            // Redirect based on role
            if ($user['role'] === 'admin') {
                echo "<script>alert('Login successful! Redirecting to Admin Dashboard.'); window.location.href = 'admin/index.php';</script>";
            } else {
                echo "<script>alert('Login successful!'); window.location.href = 'index1.php';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Invalid username or password!'); window.location.href = 'login.html';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
} else {
    echo "<script>alert('All fields are required!');</script>";
}

?>