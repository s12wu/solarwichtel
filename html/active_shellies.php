<?php
$connection = new mysqli('localhost','[user]','[password]','solarwichtel');
$connection -> set_charset('utf8mb4');


$stmt = $connection -> prepare("SELECT DISTINCT shelly FROM zipcode WHERE exist=1");

$stmt -> execute();
$stmt -> bind_result($shelly);

while ($stmt -> fetch()) {
    echo $shelly . ",";
}

$stmt -> free_result();
?>
