<html>
<head>
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Cancel Booking</h2>

<form action="cancelbooking.php" method="POST">
    <label>Enter Purchase Number:</label>
    <input type="text" name="purchase_number" required>
    <button type="submit">Cancel Booking</button>
</form>

<br>

<?php
require_once("database.php");

if (isset($_POST['purchase_number'])) {

    $pn = $_POST['purchase_number'];

    $sql = "SELECT * FROM bookings WHERE purchase_number = '$pn'";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        $booking = mysqli_fetch_assoc($result);

        $flight_id = $booking['flight_id'];
        $return_flight_id = $booking['return_flight_id'];
        $seats_booked = $booking['seats_booked'];

        // Save canceled booking history
        $sql_cancel = "INSERT INTO canceled_bookings 
                       (purchase_number, flight_id, return_flight_id, seats_booked, canceled_date)
                       VALUES 
                       ('$pn', $flight_id, $return_flight_id, $seats_booked, NOW())";
        mysqli_query($mysqli, $sql_cancel);

        // Restore outbound seats
        $sql_restore_outbound = "UPDATE flights 
                                 SET seats_available = seats_available + $seats_booked 
                                 WHERE id = $flight_id";
        mysqli_query($mysqli, $sql_restore_outbound);

        // Restore return seats
        $sql_restore_return = "UPDATE flights 
                               SET seats_available = seats_available + $seats_booked 
                               WHERE id = $return_flight_id";
        mysqli_query($mysqli, $sql_restore_return);

        // Delete passenger records
        $sql_delete_passengers = "DELETE FROM passengers WHERE purchase_number = '$pn'";
        mysqli_query($mysqli, $sql_delete_passengers);

        // Delete booking
        $sql_delete_booking = "DELETE FROM bookings WHERE purchase_number = '$pn'";
        mysqli_query($mysqli, $sql_delete_booking);

        echo "<h3>Booking Canceled</h3>";
        echo "<p>Purchase Number <b>$pn</b> has been canceled.</p>";
        echo "<p>$seats_booked seat(s) have been restored to the outbound flight.</p>";
        echo "<p>$seats_booked seat(s) have been restored to the return flight.</p>";
        echo "<p>This cancellation has been saved for future lookup.</p>";

    } else {
        echo "<p>Booking not found.</p>";
    }
}
?>

<br>
<a href="index.php">Home</a>

</body>
</html>
