<?php
/**
 * La classe esegue una ricerca presso il sito Google Movies
 * Analizza il DOM della pagina e restituisce i risultati
 * Il formato del dom che viene analizzato è stato preso il 5/07/2013
 */
//error_reporting(-1);
class GMovies
{
	/*
	 * Esegue la ricerca
	 * @near: Indica il punto dove eseguire la ricerca (Esempio: Puglia, Sicilia o Venezia sono validi.)
	 * Per il futuro:
	 * secondo la queyr è possibile personalizzare l'orario, magari si può aggiungere un params per questo
	 * e anche una scelta nella pagina della regione? :)
	 *
	 * args gestiti manualmente
	 */
	public static function search($near, $args)
	{
		$results = array ( );
		
		// creo la struttura
		//$results["theaters"] = array ( );
		$idx = 0; // ogni teatro ha un ID univoco nell'array
		
		// inizializzo il dom, in futuro aggiungere più funzionalità
		// del tipo: fascia oraria, cinema specifico (magari salvare anche l'id?)
		
		$dom = new DOMDocument;
		// evito errori sull'html
		@$dom->loadHTMLFile ("http://www.google.it/movies?near=" . $near . $args);
		
		$divs = $dom->getElementsByTagName("div");
		
		foreach($divs as $div)
		{
			// ho trovato un teatro
			// inizio a creare la struttura
			$classe = $div->getAttribute("class");
			
			if ($classe == "theater") 
			{
				//$results[$idx]["name"]
				// In questo div ho sub-div con i i film in programma e il nome del cinema
				
				// Bisognerebbe utilizzare una classe migliore per la lettura del DOM
				// Questa per come la sto usando io è troppo limitativa
				
				// div punta a <div class="theater"> il primo div è quello che mi interessa
				// in questo momento
				
				$results[$idx]["name"] = @$div->getElementsByTagName("div")->item(0)->getElementsByTagName("h2")->item(0)->getElementsByTagName("a")->item(0)->nodeValue;
				
				if (is_null($results[$idx]["name"]))
				{
					// Se non riesco a prendere il nome
					// può essere che il cinema sia chiuso momentaniamente
					// cosi analizzo il dom della chiusura
					
					$results[$idx]["name"] = @$div->getElementsByTagName("div")->item(0)->getElementsByTagName("h2")->item(0)->nodeValue;
					
					// Dovrebbe capire tutti i casi (chiusura etc.)
					// 
					
					// La key "more_info" viene generata solamente in questo caso
					// Contiene informazioni speciali riguardo il motivo di questo "caso eccezionale"
					$results[$idx]["more_info"] = @$div->getElementsByTagName("div")->item(0)->getElementsByTagName("span")->item(0)->nodeValue;
					// Utilizzare isset per verificare la sua esistenza
					// vedere regione.php per un esempio di come utilizzare questa key "speciale"
				}
				
				// prendo la location che sta nel div sottostante
				$results[$idx]["location"] = $div->getElementsByTagName("div")->item(0)->getElementsByTagName("div")->item(0)->nodeValue;
					
				$movies_idx = 0; // Contiene l'ID del movie nell'array
				$results[$idx]["movies"] = array ( ); // Inizializza la struttura
				
				// per i movies devo per forza inventarmi un altro modo per accedervi..
				// tutta questa merda sarebbe stata più facile se creavo una funzione del tipo
				// getdomfromclass all'inizio bah
				// riscrivi sta merda quando hai tempo
				
				//echo $div->getElementsByTagName("div")->item(2)->c14N(false, true);
				
				$movies = $div->getElementsByTagName("div")->item(2)->getElementsByTagName("div");
				
				foreach($movies as $movie)
				{
					if ($movie->getAttribute("class") == "movie")
					{
						//echo $movie->c14N(false, true);

						$results[$idx]["movies"][$movies_idx]["name"] = $movie->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->nodeValue;
						
						$results[$idx]["movies"][$movies_idx]["info"] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $movie->getElementsByTagName("span")->item(0)->nodeValue);
						
						$results[$idx]["movies"][$movies_idx]["orari"] = array ( );
						
						foreach 
							(
								$movie->getElementsByTagName("div")->item(1)->getElementsByTagName("span") 
								as
								$hours
							)
						{
							$orario = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-:]/s', '', trim($hours->nodeValue));
							if (strlen($orario) > 5)// la stringa è parecchio infetta - bisogna pulirla meglio
							{
								$results[$idx]["movies"][$movies_idx]["orari"][] = $orario;
							}
						}
						
						$movies_idx++;				
					}
				}
				
				$idx++; // cambio cinema
			}
		}
		
		return $results;
	}
}
?>
