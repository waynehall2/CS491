<?php

define("HOST", "localhost");     // The host you want to connect to.
define("USER", "fotinfo_2603BlueUser");    // The database username. 
define("PASSWORD", "d00edjuhme");    // The database password. 
define("DATABASE", "fotinfo_2603Blue");    // The database name.

// Create connection
$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

// Check connection
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
