<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
        
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
    </head>
    
    <body>
    	<div data-role="page" id="home">
            <div data-role="header">
            	<a href="javascript:nearby()" data-icon="star">Vicino a me</a>
            	<h1>Cinema</h1>
            </div> 
            <div data-role="content">
            	<div id="geo-info">Premi "Consenti" per accettare la geo localizzazione.</div>
                <br />
            	<ul data-role="listview" data-filter="true" data-filter-placeholder="Cerca regione" data-inset="true">
                    <li><a href="regioni/regione.php?s=Piemonte" data-prefetch="true">Piemonte</a></li>
                    <li><a href="regioni/regione.php?s=Valle Aosta" data-prefetch="true">Valle d'Aosta</a></li>
                    <li><a href="regioni/regione.php?s=Liguria" data-prefetch="true">Liguria</a></li>
                    <li><a href="regioni/regione.php?s=Lombardia" data-prefetch="true">Lombardia</a></li>
                    <li><a href="regioni/regione.php?s=Trentino" data-prefetch="true">Trentino-Alto Adige</a></li>
                    <li><a href="regioni/regione.php?s=Veneto" data-prefetch="true">Veneto</a></li>
                    <li><a href="regioni/regione.php?s=Friuli" data-prefetch="true">Friuli-Venezia Giulia</a></li>
                    <li><a href="regioni/regione.php?s=Emilia" data-prefetch="true">Emilia-Romagna</a></li>
                    <li><a href="regioni/regione.php?s=Marche" data-prefetch="true">Marche</a></li>
                    <li><a href="regioni/regione.php?s=Toscana" data-prefetch="true">Toscana</a></li>
                    <li><a href="regioni/regione.php?s=Umbria" data-prefetch="true">Umbria</a></li>
                    <li><a href="regioni/regione.php?s=Lazio" data-prefetch="true">Lazio</a></li>
                    <li><a href="regioni/regione.php?s=Abruzzo" data-prefetch="true">Abruzzo</a></li>
                    <li><a href="regioni/regione.php?s=Molise" data-prefetch="true">Molise</a></li>
                    <li><a href="regioni/regione.php?s=Campania" data-prefetch="true">Campania</a></li>
                    <li><a href="regioni/regione.php?s=Puglia" data-prefetch="true">Puglia</a></li>
                    <li><a href="regioni/regione.php?s=Basilicata" data-prefetch="true">Basilicata</a></li>
                    <li><a href="regioni/regione.php?s=Calabria" data-prefetch="true">Calabria</a></li>
                    <li><a href="regioni/regione.php?s=Sicilia" data-prefetch="true">Sicilia</a></li>
                    <li><a href="regioni/regione.php?s=Sardegna" data-prefetch="true">Sardegna</a></li>
            	</ul>
            </div> 
            <div data-role="footer">
            	<h1>Scegli la regione! - revo</h1>
            </div>     
        </div>
        
    <div id="warnings" name="warnings" data-role="dialog">
        <div data-role="header" data-theme="b">
        	<h1 id="header-clients">Geo</h1>
        </div>
        <div data-role="content">
            <p>La geo localizzazione &eacute; fallita. Motivo: <div id="reason"></div></p>
        </div>
    </div>    
        <script type="text/javascript">
		var place;
		
		function nearby()
		{
			if (captured)
			{
				$.mobile.changePage( "regioni/regione.php?s=" + place, {transition: "slideup"} );
			}
			else
			{
				$.mobile.changePage( "#warnings", { transition: 'pop', role: "dialog" } );	
			}
		}
		
		var captured = false;
		
		if (navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(userPosition, errorGeo, {timeout: 5000});
			
			function userPosition(posizione)
			{
				$.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng=" + posizione.coords.latitude + "," + posizione.coords.longitude + "&sensor=true", function(data) {
					place = data.results[0].address_components[4].long_name;
					captured = true;
					document.getElementById('geo-info').innerHTML = "Posizione trovata (<b>" + place + "</b>)";
				});
			}
			
			function errorGeo(error) {
				captured = false;
				
				switch (error.code) 
				{
					case 1: {
						document.getElementById('geo-info').innerHTML = document.getElementById('reason').innerHTML = "Hai negato l'accesso alla geolocalizzazione, non potremo fornirti informazioni sui cinema vicini a te.";	
					}
					case 2, 3: {
						document.getElementById('geo-info').innerHTML = document.getElementById('reason').innerHTML = "Si &eacute; verificato un problema nel localizzarti";	
					}
					default: {
						document.getElementById('geo-info').innerHTML = document.getElementById('reason').innerHTML = "Si &eacute; verificato un problema nel localizzarti (" + error.message + ")";						
					}
				}
			}
		}
		else
		{
			document.getElementById('geo-info').innerHTML = document.getElementById('reason').innerHTML = "Il tuo browser non supporta la geo localizzazione.";	
			
			captured = false;
		}
		</script>
    </body>
</html>