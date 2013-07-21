<?php
	if (!isset($_GET['s']))
	{
		// redrict to index.php
		die();
	}
	
	$regione = $_GET['s'];
	
	// e' chiaro che se si usa un database mysql per il controllo delle provincie / ottenere le informazioni
	// questo controllo diventa superfluo dato che bastarà fare un num_rows < 1 per vedere se la regione
	// esiste o meno
	/*
	if ( 
		$regione != "Piemonte" &&
		$regione != "Valle Aosta" &&
		$regione != "Liguria" &&
		$regione != "Lombardia" &&
		$regione != "Trentino" &&
		$regione != "Veneto" &&
		$regione != "Friuli" && 
		$regione != "Emilia" &&
		$regione != "Marche" &&
		$regione != "Toscana" &&
		$regione != "Umbria" && 
		$regione != "Lazio" &&
		$regione != "Abruzzo" &&
		$regione != "Molise" &&
		$regione != "Campania" &&
		$regione != "Puglia" &&
		$regione != "Basilicata" &&
		$regione != "Calabria" &&
		$regione != "Sicilia" &&
		$regione != "Sardegna")
	{
		// back to index.php
		echo "Back to index, wrong.";
		die ();	
	}
	*/
	include "../parseresult.php";
	
	$special_args = "";
	if (isset($_GET['next_day'])) {
		$next_day = $_GET['next_day'];
		
		if ($next_day == "on") {
			$next_day = 1;
			$special_args .= "&date=1";	
		}
	}
	
	if (isset($_GET['fascia_oraria'])) {
		$fascia = $_GET['fascia_oraria'];
		if ($fascia == 3 || $fascia == 4 || $fascia == 2) {
			$special_args .= "&time=" . $fascia;
		}
	}
?>

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
            	<a href="../index.php" data-icon="home">Home</a>
            	<h2><?php echo $regione; ?></h2>
            </div>
            <div data-role="content">
                Questa pagina contiene tutte le informazioni sui film in programmazione nella giornata di oggi dei cinema elencati. <br /> Se vuoi personalizzare la ricerca, apri il box sottostante.
            	<div data-role="collapsible-set" data-inset="false">
                    <div data-role="collapsible">
                    	<h1>Ricerca</h1>
                        Personalizza la ricerca: <br />
                        <form action="regione.php" type="GET">
                            <label><input <?php echo ($next_day==1 ? 'checked' : '') ?> type="checkbox" name="next_day" data-mini="true">Cerca per domani</label>
                            <label><input type="radio" <?php echo ($fascia==0 ? 'checked' : '') ?> name="fascia_oraria" data-mini="true" value="0" />Tutti gli orari</label>
                            <label><input type="radio" <?php echo ($fascia==2 ? 'checked' : '') ?> name="fascia_oraria" data-mini="true" value="2" />Pomeriggio</label>
                            <label><input type="radio" <?php echo ($fascia==3 ? 'checked' : '') ?> name="fascia_oraria" data-mini="true" value="3" />Sera</label>
                            <label><input type="radio" <?php echo ($fascia==4 ? 'checked' : '') ?> name="fascia_oraria" data-mini="true" value="4" />Notte</label>
                            
                            <input type="hidden" value="<?php echo $regione; ?>" name="s" />
                            
                            <input type="submit" value="Esegui" data-mini="true"  />
                        </form>
              		</div>
                </div>
            	<div data-role="collapsible-set" data-inset="false">
                	<?php
					$cinemas = GMovies::search($regione, $special_args);
					
					foreach($cinemas as $cinema)
					{
						echo '<div data-role="collapsible">';
						echo '<h2>' . 
						$cinema["name"] .
						(isset($cinema["more_info"]) ? ' (' . $cinema["more_info"] . ')' : ' ')
						.'</h2>';
						echo '<p>Dove si trova: <b>'.$cinema["location"].'</b></p>';
						echo '<hr>';
						echo '<p>Programmazione:</p>';
						echo '<table>';
						echo '<tr><td>Nome film</td><td>Orari</td><td>Informazioni</td></tr>';
						//echo '<tr><td colspan="2"></td></tr>';
						
						foreach($cinema["movies"] as $movie)
						{
							echo '<tr>';
							echo '<td>'.$movie["name"].'</td>';
							echo '<td>'.join(",", $movie["orari"]).'</td>';
							echo '<td>'.$movie["info"].'</td>';
							echo '</tr>';
							
						//	$n++;
						}
						
						/*
						
						if () {
							echo '<tr><td colspan="3">Nessun informazione disponibile.</td></tr>';	
						}*/
						
						// troppo grande per un display di un telefono, da ripensare
						// |---------------------------------|
						// |nome film | orari | informazioni |
						// | film A	  | 00000 |              |
						// |---------------------------------|
						
						
						echo '</table>';
						echo '</div>';
					}
					?>
            	</div>
            </div> 
            <div data-role="footer">
            	<h1>Scegli il cinema!</h1>
            </div>         
        </div>
    </body>
</html>