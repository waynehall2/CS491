<?php

define("HOST", "localhost");
define("USER", "fotinfo_2603BlueUser");
define("PASSWORD", "d00edjuhme");
define("DATABASE", "fotinfo_2603Blue");

$mysqli = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}
?>