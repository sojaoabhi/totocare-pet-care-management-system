<?php
/**
 * Example configuration file for Totocare
 * 
 * Copy this file to config.php and update the values with your local setup.
 */

// Database connection (PostgreSQL)
$host = "localhost";
$port = "5432";
$dbname = "totocare";
$user = "postgres";
$password = "your_password_here";

// Connect to PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}
?>
