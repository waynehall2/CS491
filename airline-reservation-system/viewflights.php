<html>
<head>
    <title>View Flights</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2>Search Available Flights</h2>

<form action="viewflights.php" method="POST">
    <label>Select Departure Date:</label>
    <input type="date" name="flight_date" required>
    <button type="submit">Search Flights</button>
</form>

<br>

<?php
require_once("database.php");

if (isset($_POST['flight_date'])) {

    $selected_date = $_POST['flight_date'];

    echo "<h3>Available Outbound Flights for $selected_date</h3>";

    $sql = "SELECT * FROM flights 
            WHERE route='COS-SAT' 
            AND flight_date = '$selected_date'
            ORDER BY departure_time";

    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        echo "<table border='1' cellpadding='10'>";
        echo "<tr>
                <th>Flight ID</th>
                <th>Route</th>
                <th>Date</th>
                <th>Departure Time</th>
                <th>Seats Available</th>
                <th>Status</th>
                <th>Book Round Trip</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['route'] . "</td>";
            echo "<td>" . $row['flight_date'] . "</td>";
            echo "<td>" . $row['departure_time'] . "</td>";
            echo "<td>" . $row['seats_available'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";

            echo "<td>";
            echo "<form action='bookflight.php' method='POST'>";
            echo "<input type='hidden' name='flight_id' value='" . $row['id'] . "'>";

            echo "<label>Seats:</label><br>";
            echo "<input type='number' name='seats' min='1' max='5' required>";

            echo "<br><br>";
            echo "<label>Return Flight:</label><br>";
            echo "<select name='return_flight_id' required>";

            $outbound_date = $row['flight_date'];

            $return_sql = "SELECT * FROM flights 
                           WHERE route='SAT-COS' 
                           AND flight_date > '$outbound_date'
                           ORDER BY flight_date, departure_time";

            $return_result = mysqli_query($mysqli, $return_sql);

            while ($return = mysqli_fetch_assoc($return_result)) {
                echo "<option value='" . $return['id'] . "'>";
                echo $return['route'] . " - " . $return['flight_date'] . " - " . $return['departure_time'] . " - Seats: " . $return['seats_available'];
                echo "</option>";
            }

            echo "</select>";

            echo "<br><br>";
            echo "<button type='submit'>Book Round Trip</button>";
            echo "</form>";
            echo "</td>";

            echo "</tr>";
        }

        echo "</table>";

    } else {
        echo "<p>No outbound flights found for this date.</p>";
    }
}
?>

<br>
<a href="index.php">Home</a>

</body>
</html>
