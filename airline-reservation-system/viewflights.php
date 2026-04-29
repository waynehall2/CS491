<html>
<head>
    <title>View Flights</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2>Available Flights (COS ↔ SAT)</h2>

<?php
require_once("database.php");

$sql = "SELECT * FROM flights";
$result = mysqli_query($mysqli, $sql);

echo "<table border='1' cellpadding='10'>";
echo "<tr>
        <th>Flight ID</th>
        <th>Route</th>
        <th>Departure Time</th>
        <th>Seats Available</th>
        <th>Status</th>
        <th>Book</th>
      </tr>";

while ($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['route'] . "</td>";
    echo "<td>" . $row['departure_time'] . "</td>";
    echo "<td>" . $row['seats_available'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";

    echo "<td>
        <form action='bookflight.php' method='POST'>
            <input type='hidden' name='flight_id' value='" . $row['id'] . "'>
            <input type='number' name='seats' min='1' max='5' required>
            <button type='submit'>Book</button>
        </form>
    </td>";

    echo "</tr>";
}

echo "</table>";
?>

<br>
<a href="index.php">Home</a>

</body>
</html>