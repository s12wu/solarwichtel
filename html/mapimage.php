<?php
$config = parse_ini_file('solarwichtel_config.ini', true);
$threshold = $config['radiation']['threshold'];

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


$connection = new mysqli('localhost','[user]','[password]','solarwichtel');
$connection -> set_charset('utf8mb4');

$stmt = $connection->prepare("SELECT shelly, latitude, longitude, exist FROM zipcode
WHERE primary_key IN (
  SELECT MAX(primary_key) FROM zipcode GROUP BY shelly
)
ORDER BY exist");
$stmt->execute();
$stmt->bind_result($shelly, $lat, $lon, $exist);



$aspect = 1.35;
$width = 700; // its SVG anyway
$height = $width * $aspect;

$lat_low = 47.4;
$lat_high = 55.02;
$lon_low = 5.92;
$lon_high = 15.0;

// Start building SVG markup
header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>';
echo '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';

// Define the raindrop shape
$raindrop = 'M20,0 C8.95,0,0,8.95,0,20 C0,35,20,70,20,70 S40,35,40,20 C40,8.95,31.05,0,20,0z';

$dropShadowFilter = '<filter id="drop-shadow">
                      <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
                      <feOffset dx="2" dy="2" result="offsetblur"/>
                      <feFlood flood-color="rgba(0,0,0,0.9)"/>
                      <feComposite in2="offsetblur" operator="in"/>
                      <feMerge>
                        <feMergeNode/>
                        <feMergeNode in="SourceGraphic"/>
                      </feMerge>
                    </filter>';
echo $dropShadowFilter;

// Plot points as circles
while ($stmt->fetch()) {
    // Scale latitude and longitude to fit within 1000x1000 canvas
    $scaled_y = ($lat - $lat_low) * ($height / ($lat_high - $lat_low)); // Scale latitude
    $scaled_x = ($lon - $lon_low) * ($width / ($lon_high - $lon_low)); // Scale longitude

    $scaled_y = $height-$scaled_y;

    // Draw raindrop or circle for each point based on $exist
    if ($exist) {
        $filename = '/mnt/ramdisk/zipcode_status.csv';
        $name = $shelly;
        $value = getValueFromCSV($filename, $name);

        if($value > $threshold) $col = "lime";
        else             $col = "red";

        $translate_x = $scaled_x - 20; // Subtract half the raindrop width
        $translate_y = $scaled_y - 72; // Subtract full raindrop height
        echo '<g filter="url(#drop-shadow)">';
        echo '<g transform="translate(' . intval($translate_x) . ',' . intval($translate_y) . ')"><path fill="' . $col . '" d="' . $raindrop . '"/></g>';
        echo '</g>';
    }

    $col = "white";
    echo '<circle cx="' . intval($scaled_x) . '" cy="' . intval($scaled_y) . '" r="2" fill="' . $col . '"/>';
}

// Close SVG markup
echo '</svg>';

// Free database resources
$stmt->free_result();

// Close database connection
$connection->close();
?>
