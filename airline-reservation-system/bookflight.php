<?php
require_once("database.php");

// STEP 2: Finalize booking after passenger info is submitted
if (isset($_POST['confirm_booking'])) {

    $flight_id = $_POST['flight_id'];
    $return_flight_id = $_POST['return_flight_id'];
    $seats = $_POST['seats'];

    if ($seats <= 0 || $seats > 5) {
        echo "Invalid number of seats.";
        exit;
    }

    $pn = "PN" . bin2hex(random_bytes(4));

    $sql = "SELECT * FROM flights WHERE id = $flight_id";
    $result = mysqli_query($mysqli, $sql);
    $outbound = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM flights WHERE id = $return_flight_id";
    $result = mysqli_query($mysqli, $sql);
    $return = mysqli_fetch_assoc($result);

    if (!$outbound || !$return) {
        echo "Invalid flight selection.";
        exit;
    }

    if ($return['flight_date'] <= $outbound['flight_date']) {
        echo "Return flight must be after outbound flight.";
        exit;
    }

    if ($outbound['seats_available'] < $seats) {
        echo "Not enough seats available on outbound flight.";
        exit;
    }

    if ($return['seats_available'] < $seats) {
        echo "Not enough seats available on return flight.";
        exit;
    }

    $sql = "INSERT INTO bookings (purchase_number, flight_id, return_flight_id, seats_booked)
            VALUES ('$pn', $flight_id, $return_flight_id, $seats)";
    mysqli_query($mysqli, $sql);

    for ($i = 1; $i <= $seats; $i++) {
        $first_name = $_POST["first_name_$i"];
        $last_name = $_POST["last_name_$i"];

        $sql = "INSERT INTO passengers (purchase_number, flight_id, first_name, last_name)
                VALUES ('$pn', $flight_id, '$first_name', '$last_name')";
        mysqli_query($mysqli, $sql);

        $sql = "INSERT INTO passengers (purchase_number, flight_id, first_name, last_name)
                VALUES ('$pn', $return_flight_id, '$first_name', '$last_name')";
        mysqli_query($mysqli, $sql);
    }

    $sql = "UPDATE flights 
            SET seats_available = seats_available - $seats 
            WHERE id = $flight_id";
    mysqli_query($mysqli, $sql);

    $sql = "UPDATE flights 
            SET seats_available = seats_available - $seats 
            WHERE id = $return_flight_id";
    mysqli_query($mysqli, $sql);

    echo "<html>";
    echo "<head>";
    echo "<title>Booking Confirmed</title>";
    echo "<link rel='stylesheet' href='css/style.css'>";
    echo "</head>";
    echo "<body>";

    echo "<h2>Booking Confirmed</h2>";
    echo "<p>Purchase Number: <b>$pn</b></p>";
    echo "<p>Seats booked: $seats</p>";

    echo "<h3>Outbound Flight</h3>";
    echo "<p>Route: " . $outbound['route'] . "</p>";
    echo "<p>Date: " . $outbound['flight_date'] . "</p>";
    echo "<p>Departure Time: " . $outbound['departure_time'] . "</p>";

    echo "<h3>Return Flight</h3>";
    echo "<p>Route: " . $return['route'] . "</p>";
    echo "<p>Date: " . $return['flight_date'] . "</p>";
    echo "<p>Departure Time: " . $return['departure_time'] . "</p>";

    echo "<h3>Passenger Manifest</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>First Name</th><th>Last Name</th></tr>";

    for ($i = 1; $i <= $seats; $i++) {
        echo "<tr>";
        echo "<td>" . $_POST["first_name_$i"] . "</td>";
        echo "<td>" . $_POST["last_name_$i"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<br><a href='index.php'>Home</a>";
    echo "</body>";
    echo "</html>";

    exit;
}

// STEP 1: Show passenger information form after user selects flight
$flight_id = $_POST['flight_id'];
$return_flight_id = $_POST['return_flight_id'];
$seats = $_POST['seats'];

$sql = "SELECT * FROM flights WHERE id = $flight_id";
$result = mysqli_query($mysqli, $sql);
$outbound = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM flights WHERE id = $return_flight_id";
$result = mysqli_query($mysqli, $sql);
$return = mysqli_fetch_assoc($result);
?>

<html>
<head>
    <title>Passenger Information</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Passenger Information</h2>

<h3>Selected Outbound Flight</h3>
<p>Route: <?php echo $outbound['route']; ?></p>
<p>Date: <?php echo $outbound['flight_date']; ?></p>
<p>Departure Time: <?php echo $outbound['departure_time']; ?></p>

<h3>Selected Return Flight</h3>
<p>Route: <?php echo $return['route']; ?></p>
<p>Date: <?php echo $return['flight_date']; ?></p>
<p>Departure Time: <?php echo $return['departure_time']; ?></p>

<form action="bookflight.php" method="POST">
    <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
    <input type="hidden" name="return_flight_id" value="<?php echo $return_flight_id; ?>">
    <input type="hidden" name="seats" value="<?php echo $seats; ?>">
    <input type="hidden" name="confirm_booking" value="1">

    <?php
    for ($i = 1; $i <= $seats; $i++) {
        echo "<h3>Passenger $i</h3>";
        echo "<label>First Name:</label><br>";
        echo "<input type='text' name='first_name_$i' required><br><br>";

        echo "<label>Last Name:</label><br>";
        echo "<input type='text' name='last_name_$i' required><br><br>";
    }
    ?>

    <button type="submit">Confirm Booking</button>
</form>

<br>
<a href="viewflights.php">Back to Flights</a>

</body>
</html>
