<?php

if (!isset($_GET['dvd_title'])) {
	header('Location: search.php'); // redirect to homepage if there is no querystring
}

?>

<head>
	<title>DVD Search</title>
	<link rel="stylesheet" href="style.css" />
</head>

<?php

$dvd_title = $_GET['dvd_title'];

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
	ON dvds.rating_id = ratings.id
	WHERE title LIKE ?
";

// pdo prepared statements
$statement = $pdo->prepare($sql);
$like = '%' . $dvd_title . '%';
$statement->bindParam(1, $like);
$statement->execute();
$dvds = $statement->fetchAll(PDO::FETCH_OBJ);

?>

<?php

if (!empty($dvds)) {
	echo '<div id="results">';
	echo 'Found ' . sizeof($dvds) . ' results for "' . $dvd_title . '"<br><table><tr><td><b><u>TITLE</u></td><td><b><u>GENRE</u></b></td><td><b><u>FORMAT</u></b></td><td><b><u>RATING</u></b></td></tr>';
	foreach ($dvds as $dvd) {
		echo '<div><tr><td>' . $dvd->title . '</td>';
		echo '<td>' . $dvd->genre_name . '</td>';
		echo '<td>' . $dvd->format_name . '</td>';
		echo '<td><a href="ratings.php?rating_name=' . $dvd->rating_name . '">' . $dvd->rating_name . '</a></td></tr></div>';
	}
	echo '</table></div';
} else {
	echo '<div id="content"><div id="error">Lo siento...<br><br>We don\'t seem to have <b>' . $dvd_title . '</b>.<br><br><a href="search.php">Give it another go?</a></div></div>';
}

?>