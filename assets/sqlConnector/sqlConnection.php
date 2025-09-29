<?php
/**
 * Database Connection Script
 * * This file connects to the MySQL database using the modern object-oriented style of MySQLi.
 */

// --- Database Credentials ---
// It's good practice to define these. Replace with your actual details.
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'root'; // On many local setups (like XAMPP), the password is an empty string: ''
$dbName = 'hostello';

// --- Create Connection (Object-Oriented Style) ---
$con = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// --- Check Connection ---
// Check if the connection attempt resulted in an error.
if ($con->connect_error) {
    // If it fails, stop the script completely (die) and show a detailed error message.
    // In a live production environment, you would log this error instead of showing it to the user.
    die("Database Connection Failed: " . $con->connect_error);
}

// Optional but recommended: Set the character set to utf8mb4 for full Unicode support (emojis, etc.)
$con->set_charset("utf8mb4");

?>
