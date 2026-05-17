<html>
<head>
    <title>Lookup Booking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Lookup Booking</h2>

<form action="lookupbooking.php" method="POST">
    <label>Enter Purchase Number:</label>
    <input type="text" name="purchase_number" required>
    <button type="submit">Lookup</button>
</form>

<br>

<?php
require_once("database.php");

if (isset($_POST['purchase_number'])) {

    $pn = $_POST['purchase_number'];

    // First check active bookings
    $sql = "SELECT 
                b.purchase_number,
                b.flight_id,
                b.return_flight_id,
                b.seats_booked,

                outbound.route AS outbound_route,
                outbound.flight_date AS outbound_date,
                outbound.departure_time AS outbound_time,
                outbound.status AS outbound_status,

                returnflight.route AS return_route,
                returnflight.flight_date AS return_date,
                returnflight.departure_time AS return_time,
                returnflight.status AS return_status

            FROM bookings b
            JOIN flights outbound ON b.flight_id = outbound.id
            JOIN flights returnflight ON b.return_flight_id = returnflight.id
            WHERE b.purchase_number = '$pn'";

    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        echo "<h3>Booking Found</h3>";
        echo "<p><b>Purchase Number:</b> " . $row['purchase_number'] . "</p>";
        echo "<p><b>Seats Booked:</b> " . $row['seats_booked'] . "</p>";

        echo "<h3>Outbound Flight</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Route</th><th>Date</th><th>Departure Time</th><th>Status</th></tr>";
        echo "<tr>";
        echo "<td>" . $row['outbound_route'] . "</td>";
        echo "<td>" . $row['outbound_date'] . "</td>";
        echo "<td>" . $row['outbound_time'] . "</td>";
        echo "<td>" . $row['outbound_status'] . "</td>";
        echo "</tr>";
        echo "</table>";

        echo "<br>";

        $outbound_manifest_sql = "SELECT first_name, last_name
                                  FROM passengers
                                  WHERE purchase_number = '$pn'
                                  AND flight_id = " . $row['flight_id'];

        $outbound_manifest_result = mysqli_query($mysqli, $outbound_manifest_sql);

        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>First Name</th><th>Last Name</th></tr>";

        while ($passenger = mysqli_fetch_assoc($outbound_manifest_result)) {
            echo "<tr>";
            echo "<td>" . $passenger['first_name'] . "</td>";
            echo "<td>" . $passenger['last_name'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        echo "<h3>Return Flight</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Route</th><th>Date</th><th>Departure Time</th><th>Status</th></tr>";
        echo "<tr>";
        echo "<td>" . $row['return_route'] . "</td>";
        echo "<td>" . $row['return_date'] . "</td>";
        echo "<td>" . $row['return_time'] . "</td>";
        echo "<td>" . $row['return_status'] . "</td>";
        echo "</tr>";
        echo "</table>";

        echo "<br>";

        $return_manifest_sql = "SELECT first_name, last_name
                                FROM passengers
                                WHERE purchase_number = '$pn'
                                AND flight_id = " . $row['return_flight_id'];

        $return_manifest_result = mysqli_query($mysqli, $return_manifest_sql);

        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>First Name</th><th>Last Name</th></tr>";

        while ($passenger = mysqli_fetch_assoc($return_manifest_result)) {
            echo "<tr>";
            echo "<td>" . $passenger['first_name'] . "</td>";
            echo "<td>" . $passenger['last_name'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

    } else {

        // If not active, check canceled bookings
        $cancel_sql = "SELECT * FROM canceled_bookings WHERE purchase_number = '$pn'";
        $cancel_result = mysqli_query($mysqli, $cancel_sql);

        if ($cancel_result && mysqli_num_rows($cancel_result) > 0) {

            $cancel = mysqli_fetch_assoc($cancel_result);

            echo "<h3>Booking Canceled</h3>";
            echo "<p><b>Purchase Number:</b> " . $cancel['purchase_number'] . "</p>";
            echo "<p>This booking was canceled on: <b>" . $cancel['canceled_date'] . "</b></p>";

        } else {

            echo "<p>Booking not found.</p>";
        }
    }
}
?>

<br>
<a href="index.php">Home</a>

</body>
</html>
