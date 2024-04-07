<?php
$connection = new mysqli('localhost','[user]','[password]','solarwichtel');
$connection -> set_charset('utf8mb4');

$stmt = $connection -> prepare("UPDATE zipcode SET exist=1 WHERE shelly = ?");

$stmt -> bind_param("s", $_GET["s"]);
$stmt -> execute();
$stmt -> free_result();

echo 'Aktivierungswunsch wurde gespeichert. <a href="/">Zurück zur Startseite</a>';
?>
