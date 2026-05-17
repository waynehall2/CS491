<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("database.php");

echo "<h2>Seeding Flights</h2>";

// Add flight_date column if it does not already exist
$check = mysqli_query($mysqli, "SHOW COLUMNS FROM flights LIKE 'flight_date'");

if (mysqli_num_rows($check) == 0) {
    $sql = "ALTER TABLE flights ADD flight_date DATE";
    if (!mysqli_query($mysqli, $sql)) {
        die("Error adding flight_date column: " . mysqli_error($mysqli));
    }
    echo "<p>flight_date column added.</p>";
} else {
    echo "<p>flight_date column already exists.</p>";
}

// Clear old booking data first
if (!mysqli_query($mysqli, "DELETE FROM bookings")) {
    echo "<p>Warning deleting bookings: " . mysqli_error($mysqli) . "</p>";
}

// Clear old flight data
if (!mysqli_query($mysqli, "DELETE FROM flights")) {
    die("Error deleting flights: " . mysqli_error($mysqli));
}

$start = strtotime("2026-06-01");
$end = strtotime("2026-12-31");
$count = 0;

$outboundTimes = ['08:00', '12:00', '16:00'];
$returnTimes = ['11:00', '15:00', '19:00'];

for ($date = $start; $date <= $end; $date = strtotime("+1 day", $date)) {
    $day = date("N", $date); // 1=Monday, 3=Wednesday, 5=Friday

    if ($day == 1 || $day == 3 || $day == 5) {
        $flightDate = date("Y-m-d", $date);

        // Add COS → SAT outbound flights
        foreach ($outboundTimes as $time) {
            $sql = "INSERT INTO flights (route, flight_date, departure_time, seats_available, status)
                    VALUES ('COS-SAT', '$flightDate', '$time', 20, 'On-Time')";

            if (!mysqli_query($mysqli, $sql)) {
                die("Error inserting outbound flight: " . mysqli_error($mysqli));
            }

            $count++;
        }

        // Add SAT → COS return flights
        foreach ($returnTimes as $time) {
            $sql = "INSERT INTO flights (route, flight_date, departure_time, seats_available, status)
                    VALUES ('SAT-COS', '$flightDate', '$time', 20, 'On-Time')";

            if (!mysqli_query($mysqli, $sql)) {
                die("Error inserting return flight: " . mysqli_error($mysqli));
            }

            $count++;
        }
    }
}

echo "<h3>Flights inserted successfully.</h3>";
echo "<p>Total flights inserted: $count</p>";
echo "<p><a href='viewflights.php'>View Flights</a></p>";
?>
