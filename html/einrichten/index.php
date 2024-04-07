<!DOCTYPE html>
<html lang="de">
<head>
<title>Einrichten - Solarwichtel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">
<meta name="Keywords" content="Solarwichtel, Solar, Balkonkraftwerk, Shelly, Steckdose">
<meta name="Description" content="Solarwichtel - Nutzen Sie den Strom Ihres Balkonkraftwerks selbst, wo auch immer Sie wohnen. Deutschlandweit. Automatisiert.">
<link rel="stylesheet" href="/external/stylesheets.css">
</head>

<body>
<nav class="w3-border-bottom w3-blue-gray">
    <div class="w3-content w3-container w3-display-container w3-bar">
        <div class="w3-bar-item w3-left w3-mobile">
           <a href="/" style="padding:0;text-decoration:none;" class="w3-xlarge w3-bar-item w3-mobile"><img src="/img/w30.png" style="height:30px;margin-bottom:6px;" class="w3-middle" alt=""> Solarwichtel</a>
        </div>
        
        <div class="w3-bar-item w3-right w3-mobile">
            <a href="/" class="w3-button">Startseite</a>
            <a href="/einrichten" class="w3-button">Einrichten</a>
            <a href="/hintergrund.html" class="w3-button">Hintergrund</a>
            <a href="/kontakt.html" class="w3-button">Kontakt / Impressum</a>
        </div>
    </div>
</nav>

<div class="w3-content w3-padding-64 w3-container">
    <h1>Einrichten</h1>
    <p>Als smarte Steckdosen nehmen wir die von Shelly, z.B. <a href="https://www.shelly.com/de/products/shop/shelly-plus-plug-s-1">Shelly Plug Plus S</a> (ca. 25 €)</p>
    <p>Andere Steckdosen <b>von Shelly</b> haben wir nicht getestet, sie werden aber wahrscheinlich funktionieren. Steckdosen <b>anderer Hersteller</b> werden nicht unterstützt.</p>
    <hr>
    <h2>Am PC oder Laptop</h2>
    <p>Anleitung als PDF: <a href="solarwichtel_einrichtung_pc.pdf">Hier klicken</a></p>
    <hr>
    <h2>Am Handy</h2>
    <p>Schauen Sie sich das Video an: <a href="solarwichtel_einrichtung_handy.mp4">Hier klicken</a></p>
    
    <hr>
    <h2>MQTT Einrichtung für Ungeduldige mit Erfahrung:</h2>
    <ul>
    <li>Server: "solarwichtel.de:1883"</li>
    <li>MQTT Prefix: Abhängig von Ihrer Stadt, dazu auf der Startseite die Stadt eingeben, Steckdose aktivieren falls noch nicht geschehen. Dann lautet der MQTT Prefix "solarwichtel_XXXX_XXXX"</li>
    <li>Bei der Client ID die Voreinstellung lassen</li>
    <li>Username und Passwort bleiben leer.</li>
    </ul>
    <img src="/img/shelly_einrichtung.jpg" style="width:100%;">
</div>

<footer class="w3-container w3-dark-gray w3-padding-8 w3-border-top">
    <p class="w3-small">Solarwichtel, März - April 2024. <a href="kontakt.html">Impressum und Datenschutz</a></p>
</footer>
</body>
</html>
