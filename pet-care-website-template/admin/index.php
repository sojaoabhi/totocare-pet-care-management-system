<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TotoCare</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="css/userstyle.css">
    <style>
        
        /*.card {
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }*/
    
    /*body {
        font-family: 'Roboto', sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        padding: 0;
    }*/

    /*.sidebar {
        height: 100vh;
        width: 250px;
        background-color: #333;
        color: #ecf0f1;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
    }

    .sidebar h4 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: #ecf0f1;
    }

    .sidebar a {
        color: #ecf0f1;
        text-decoration: none;
        display: block;
        padding: 12px;
        border-radius: 5px;
        margin: 5px 0;
        transition: background-color 0.3s ease;
    }

    .sidebar a:hover {
        background-color: #34495e;
    }

    .sidebar a.text-danger:hover {
        background-color: #c0392b;
    }*/
    .sidebar a:hover {
    background-color: #555;
}

    .container-fluid {
        margin-left: 250px;
        padding: 20px;
    }

    h2 {
        color: #34495e;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .card {
        /*display: inline-block;
        width: calc(33% - 20px); /* Three cards per row with spacing */
        margin: 10px;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #ecf0f1;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .card h5 {
        font-size: 20px;
        color: #2c3e50;
        margin: 0;
    }

    .card h3 {
        font-size: 36px;
        font-weight: bold;
        margin: 10px 0;
        color: #2980b9;
    }

    .card:hover {
        background-color: #bdc3c7;
        transform: scale(1.05);
    }

    .text-danger:hover {
        color: #e74c3c;
    }
</style>

</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center">Admin Panel</h4>
        <a href="#">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_pets.php">Manage Pets</a>
        <a href="manage_adoptions.php">Manage Adoptions</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="bookings_history.php">Bookings History</a>
        <a href="settings.php">Manage Contacts</a>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Main Content -->

        <div class="container-fluid p-4">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row">
                
                
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h5>Total Users</h5>
                        <h3 id="total-users">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h5>Total Pets Listed</h5>
                        <h3 id="total-pets">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h5>Total Adoptions</h5>
                        <h3 id="total-adoptions">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h5>Past Month Bookings</h5>
                        <h3 id="past-month-bookings">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h5>Pending Requests</h5>
                        <h3 id="pending-requests">0</h3>
                    </div>
                </div>
                <!--<div class="search-container">
                    <form method="GET" action="search.php">
                        <input type="text" name="query" placeholder="Search by username, email, phone, or date" required>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>-->
            </div>
        </div>
    
</div>

<script>
    // Fetch admin stats from backend
    fetch("admin_stats.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("total-users").innerText = data.total_users;
            document.getElementById("total-pets").innerText = data.total_pets;
            document.getElementById("total-adoptions").innerText = data.total_adoptions;
            document.getElementById("pending-requests").innerText = data.pending_requests;
            document.getElementById("past-month-bookings").innerText = data.today_month_bookings;
        })
        .catch(error => console.error("Error fetching admin stats:", error));
</script>

</body>
</html>
