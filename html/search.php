<?php
// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;

$q = strtolower($_GET["term"]);

$connection = new mysqli('localhost','[user]','[password]','solarwichtel');
$connection -> set_charset('utf8mb4');

$reg = "[[:<:]]$q";
$zc = "$q%";

$stmt = $connection -> prepare("SELECT zipcode,city,shelly,exist FROM zipcode WHERE city REGEXP ? OR LPAD(CONVERT(zipcode, CHAR), 5, '0') LIKE ? LIMIT 11");

$stmt -> bind_param("ss", $reg, $zc);
$stmt -> execute();
$stmt -> bind_result($zipcode,$city,$shelly,$exist);


$result = array();
while ($stmt -> fetch()) {
    array_push($result, array("id"=>$zipcode, "label"=>$city, "shelly"=>$shelly, "exist"=>$exist));
}

$stmt -> free_result();


$output = json_encode($result);
echo $output;

?>
