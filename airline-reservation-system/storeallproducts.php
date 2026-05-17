<html>

<head><title><i>Supernatural</i> Store-EM420</title>
<link href="tstore.css" rel="stylesheet" type = "text/css">
</head>



<body>


<center><img src="img/banner.jpg"></center><br>
<h1>Welcome!</h1>


<center>
<img src="img/spnxmas.jpg" width=600><br>
<table cellpadding = 10>
<tr><td valign='top'><h2>Licensed Merchandise</h2><br><form action='storebycategory.php' method='Post'><button type='submit' name='Category' value='Licensed'/><img src="img/spn-joinhunt.jpg" width=200></button></form></td>
<td valign='top'><h2>Graphics</h2><br><form action='storebycategory.php' method='Post'><button type='submit' name='Category' value='Graphics'/><img src="img/spn-graphics.gif" width=200></button></form></td>
<td valign='top'><h2>Plushies</h2><br><form action='storebycategory.php' method='Post'><button type='submit' name='Category' value='Plushies'/><img src="img/spn-plushies.jpg" width=200></button></form></td>
<td valign='top'><h2>Collectibles</h2><br><form action='storebycategory.php' method='Post'><button type='submit' name='Category' value='Collectibles'/><img src="img/spn-collectibles.jpg" width=200></button></form></td>
<td valign='top'><h2>Everything!</h2><br><form action='storeallproducts.php' method='Post'><button type='submit' name='Category' value='Everything'/><img src="img/pie.jpg" width=200></button></form></td>
</tr>
</table>
<?php

require_once("database.php");

//create table around the information
$sql="SELECT * FROM flights";
$result = $mysqli->query($sql);
//create table around the information

echo "<table cellpadding=10><tr><td>Thumbnail</td><td>ID</td><td>Name</td><td>Description</td><td>Price</td><td>Curious?</td></tr>";

//while ($arrProdRecords = mysql_fetch_array($dbProdRecords)) 
while ($row = $result->fetch_assoc())
{

    echo "<tr><td><img src='img/" . $row["picture"] . "' width=100></td> ";

    echo "<td>" . $row["id"] . "</td> ";
    $Id = $row["id"];
    echo "<td>" . $row["departure"] . "</td> ";

    echo "<td>" . $row["arrival"] . "</td> ";

    echo "<td>" . $row["d-date"] . "</td>";
    
    echo "<td>" . $row["d-time"] . "</td>";
    
    echo "<td>" . $row["a-date"] . "</td>";
    echo "<td>" . $row["a-time"] . "</td>";
    echo "<td>" . $row["seats"] . "</td>";

    echo "<td><form action='storeoneproduct.php' method='Post'> <button type='submit' name='Id' value='$Id'/>More Info!</button></form></td></tr>";

}

echo "</table>";



?>

<p id="footer">
<i>
Supernatural </i>
is a supernatural drama on the CW. The Winchester brothers are on the hunt for their father as they hunt demons, ghosts, monsters and other ghouls of the supernatural world.  This store is a nonprofit demo website run and authored by a Computer Science instructor for a class on database-driven websites. Running costs come out of the instructor's pocket. While the products displayed are real, there is no advertising on this site nor links to actually purchase the items.

No copyright infringement is intended and no profit made by the Store. The administrators acknowledge that Supernatural is the property of The CW Network, and the use of Supernatural images and text intend to fall under "fair use" and commentary.
<br>&copy;Tchris the Grate!</p>
</body>
</html>
