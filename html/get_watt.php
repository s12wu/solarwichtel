<?php
if(!isset($_GET["s"])) {
    echo "get_watt.php benötigt eine solarwichtel-Adresse";
    exit;
}

$config = parse_ini_file('solarwichtel_config.ini', true);

function getValueFromCSV($filename, $name) {
    // Read the CSV file into an array of lines
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return false; // Error reading file
    }

    // Iterate over each line and parse CSV
    foreach ($lines as $line) {
        // Parse the CSV line
        $data = str_getcsv($line, ';');

        // Check if the first column matches the given name
        if ($data[0] === $name) {
            return $data[1]; // Return the value from the second column
        }
    }

    return false; // Name not found
}


$filename = '/mnt/ramdisk/zipcode_status.csv';
$name = $_GET["s"];
$value = getValueFromCSV($filename, $name);

$power600 = min(600, intval($value * $config['balkon_600']['factor']));

echo "aktuell etwa $power600 W<br><i>liefert ein ideal ausgerichtetes 600W-Balkonmodul</i>";

?>
