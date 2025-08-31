<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - TotoCare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/userstyle.css">
</head>
<body>

<div class="sidebar">
        <div class="logo">
            <h2 style="color: #ED6436; ">Toto<span style="color: #fff;">Care</span></h2>
        </div>
        <a href="user_dashboard.php"><i class="fa fa-home"></i> Home</a>
        <a href="manage_adoptions2.php"><i class="fa fa-paw"></i> Manage Adoptions</a>
        <a href="scheduled_bookings.php"><i class="fa fa-calendar"></i> Scheduled Bookings</a>
        <a href="user_history.php"><i class="fa fa-history"></i> History</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
    <h2>Welcome to TotoCare Dashboard</h2>
    <p>Select an option from the sidebar to manage your activities.</p>
</div>

<script>
    function loadContent(section) {
        const contentArea = document.getElementById('content-area');
        fetch(section + '.php')
            .then(response => response.text())
            .then(data => contentArea.innerHTML = data)
            .catch(error => contentArea.innerHTML = "Error loading content.");
    }
</script>

</body>
</html>
