<!DOCTYPE html>
<html lang="de">
<head>
<title>Solarwichtel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">
<meta name="Keywords" content="Solarwichtel, Solar, Balkonkraftwerk, Shelly, Steckdose">
<meta name="Description" content="Solarwichtel - Nutzen Sie den Strom Ihres Balkonkraftwerks selbst, wo auch immer Sie wohnen. Deutschlandweit. Automatisiert.">
<link rel="stylesheet" href="/external/stylesheets.css">

<style>
#selectedcity_container {display:none;}

@media screen and (min-width: 601px) {
    .so-funktionierts p.w3-container {border-left:1px solid #ccc!important;}
}
@media screen and (max-width: 600px) {
    .so-funktionierts {border-top:1px solid #ccc!important;}
}

::placeholder {color: #ddd;opacity: 1;}
</style>

<script src="/external/jquery_and_jquery_ui.js"></script>
<script>
$( function() {
    $( "#city" ).autocomplete({
        autoFocus: true,
        minLength: 0,
        source: "search.php",
        focus: function( event, ui ) {
            return false;
        },
        select: function( event, ui ) {
            $( "#city" ).val( ui.item.label );

            $("#selectedcity_plz").text(String(ui.item.id).padStart(5, '0'));
            $("#selectedcity_name").text(ui.item.label);

            if (ui.item.exist) {
                $("#selectedcity_shelly").html('Ihr lokaler Shelly heißt: <code class="w3-codespan">' + ui.item.shelly + '</code><br>Diesen Shelly einrichten? <a href="/einrichten">Zur Anleitung</a>');
            }
            else {
                $("#selectedcity_shelly").html('Noch keine Steckdose eingerichtet <a class="w3-button w3-teal" href="activate.php?s='
                                               + ui.item.shelly
                                               + '" style="margin-left:1em" id="selectedcity_activate">Aktivieren</a>');
            }

            $("#selectedcity_container").show();

            $("#selectedcity_power").load("get_watt.php?s=" + ui.item.shelly);

            return false;
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div>" + String(item.id).padStart(5, '0') + " <b>" + item.label + "</b></div>" )
        .appendTo( ul );
    };
});
</script>
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

<div class="w3-container w3-blue-gray w3-padding-64">
    <div class="w3-content">
        <div class="w3-container w3-half">
            <div class="w3-container">
                <h1 style="font-size:3.5em;font-weight:bold;margin-top:1em;margin-bottom:1em;">Weck den Sparfuchs in Dir</h1>
                <p class="w3-xlarge"><b>Nutzen Sie den Strom Ihres Balkonkraftwerks selbst, wo auch immer Sie wohnen.</b></p>
                <p class="w3-xlarge"><b>Deutschlandweit.</b></p>
                <p class="w3-xlarge"><b>Automatisiert.</b></p>
                <p></p>
                <p>Als Wichtel werden Wesen bezeichnet, die im Allgemeinen den Menschen gegenüber freundlich gesinnt sind
                und ihnen meist im Verborgenen bei der täglichen Arbeit helfen.<br><i>frei nach Wikipedia</i></p>
            </div>
        </div>
        <div class="w3-half">
            <img src="mapimage.php" style="width:100%" alt="Karte von Deutschland, auf der die eingerichteten Steckdosen markiert sind">
            <div class="w3-container">
            <p class="w3-small w3-right w3-text-light-gray w3-text-right">Eingerichtete Steckdosen - Grün bedeutet angeschaltet <br> Hintergrund: Verteilung der Postleitzahlen - geonames.org</p>
            </div>
            <div class="w3-display-container">
                <input class="w3-input w3-blue-gray w3-margin" type="text" placeholder="Ihre Stadt" id="city">
                <span class="w3-display-right fa fa-search"></span>
            </div>
        </div>
    </div>
</div>


<div class="w3-container w3-blue-gray" style="padding-bottom: 1em" id="selectedcity_container">
    <div class="w3-container w3-content w3-card w3-mobile" style="background-color: rgba(255,255,255, 0.1)" style="width:75%;margin:auto">
        <div class="w3-half">
            <h1 id="selectedcity_name"></h1>
            <p class="w3-large" id="selectedcity_power"></p>
        </div>
        <div class="w3-half w3-right-align w3-right">
            <p><span id="selectedcity_plz"></span></p>
            <p id="selectedcity_shelly"></p>
        </div>
    </div>
</div>

<svg width="100%" viewBox="0 0 100 100" preserveAspectRatio="none" style="background-color:#ffffff;" height="50">
  <path id="wavepath" d="M0,0  L110,0C35,150 35,0 0,100z" fill="#607d8b"></path>
</svg>

<div class="w3-container w3-padding-64">
    <div class="w3-content" style="padding-1em;">
        <h1>So funktionierts</h1>

        
        <div class="w3-row">
            <div class="w3-col s3 w3-mobile">
                <div class="w3-center"><p style="font-size:10em; margin:0;">☀️</p></div>
                <p class="w3-container">Scheint die Sonne, erzeugt Ihr Solarmodul Strom.</p>
            </div>
            <div class="w3-col s3 w3-mobile so-funktionierts">
                <div class="w3-center"><p style="font-size:10em; margin:0;">🌍</p></div>
                <p class="w3-container">Die Strahlungsdaten des Deutschen Wetterdienstes zeigen die Sonneneinstrahlung an jedem Punkt in Deutschland.</p>
            </div>
            <div class="w3-col s3 w3-mobile so-funktionierts">
                <div class="w3-center"><p style="font-size:10em; margin:0;">🔌</p></div>
                <p class="w3-container">Ihre smarte Steckdose schaltet sich genau dann ein, wenn Ihr Balkonkraftwerk Energie liefert.</p>
            </div>
            <div class="w3-col s3 w3-mobile so-funktionierts">
                <div class="w3-center"><p style="font-size:10em; margin:0;">🔋</p></div>
                <p class="w3-container">Damit werden Ihre Geräte zu dem Zeitpunkt aufgeladen, wenn Sie Ihren eigenen Strom erzeugen – gut für Umwelt und Geldbeutel!</p>
            </div>
        </div>
        
        
        <div class="w3-padding-32"></div>
        <hr>
        <div class="w3-padding-32"></div>
        
        <h1>Für wen?</h1>
        
        <p class="w3-center w3-xlarge">Für alle, die mehr vom eigenen Strom nutzen wollen.</p>
        <p class="w3-center w3-xlarge">Ideal für E-Bike, Akku-Staubsauger, Laptop und Co.</p>
        <p class="w3-center">...alles, was man jetzt einsteckt und erst später braucht</p>
        
    </div>
</div>

<div class="w3-container w3-dark-gray w3-padding-64">
    <div class="w3-content w3-center">
        <h1>Jetzt einrichten!</h1>
        <a class="w3-button w3-teal" href="/einrichten">Zur Anleitung</a>
    </div>
</div>

<footer class="w3-container w3-dark-gray w3-padding-8 w3-border-top">
    <p class="w3-small">Solarwichtel, März - April 2024. <a href="kontakt.html">Impressum und Datenschutz</a></p>
</footer>

</body>
</html>
