<?php

if (!isset($_GET['rating_name'])) {
	header('Location: search.php'); // redirect to homepage if there is no querystring
}

?>

<head>
	<title>DVD Search</title>
	<link rel="stylesheet" href="style.css" />
</head>

<?php

$rating = $_GET['rating_name'];

// connection variables
$host = 'itp460.usc.edu';
$dbname = 'dvd';
$user = 'student';
$password = 'ttrojan';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password); // use double quotes in order to throw in variables

// sql query
$sql = "
	SELECT title, genre_name, rating_name, format_name
	FROM dvds
	INNER JOIN formats
	ON dvds.format_id = formats.id
	INNER JOIN genres
	ON dvds.genre_id = genres.id
	INNER JOIN ratings
	ON dvds.rating_id = ratings.id AND ratings.rating_name = ?
";

// pdo prepared statements
$statement = $pdo->prepare($sql);
$statement->bindParam(1, $rating);
$statement->execute();
$dvds = $statement->fetchAll(PDO::FETCH_OBJ);

?>

<?php

echo '<div id="results">';
echo 'Found ' . sizeof($dvds) . ' results with a rating of "' . $rating . '"<br><table><tr><td><b><u>TITLE</u></td><td><b><u>GENRE</u></b></td><td><b><u>FORMAT</u></b></td><td><b><u>RATING</u></b></td></tr>';
foreach ($dvds as $dvd) {
	echo '<tr><td>' . $dvd->title . '</td>';
	echo '<td>' . $dvd->genre_name . '</td>';
	echo '<td>' . $dvd->format_name . '</td>';
	echo '<td><a href="ratings.php?rating_name=' . $dvd->rating_name . '">' . $dvd->rating_name . '</a></td></tr></div>';
}
echo '</table></div';

?>