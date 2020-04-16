<!DOCTYPE HTML>
<?php
	$request_method = $_SERVER['REQUEST_METHOD'];
	$videoDownloadVerzeichnis = "/PATH/TO/DOWNLOADS/";
	if ($request_method == 'POST') {
		//Maximal 10 Links sind erlaubt.
		$linkvars = array("link1","link2","link3","link4","link5","link6","link7","link8","link9","link10");
		//Prüfe, ob POST-Daten korrekt sind und mind. einen richtigen Wert haben.
		if (!count($_POST) > 10 || !empty($_POST) || count(array_intersect($linkvars, $_POST)) > 0){
			//Counter zählt die Anzahl der validen Links.
			$counter = 0;
			//Schneide jeden validen Link zurecht, um das Video herunterladen zu können.
			//Erlaubt sind momentan: youtube.com & youtu.be Links.
			//Unterstützte Links können beliebig innerhalb der for-Schleife erweitert werden.
			for($i = 1; $i <= count($_POST); $i++) {
				${"link" . $i} = $_POST['link'.$i];
				if (substr(${"link" . $i}, 0, 32) === "https://www.youtube.com/watch?v=") {
					$counter++;
					${"link" . $i} = substr(${"link" . $i},0,43);
				}
				elseif (substr(${"link" . $i}, 0, 17) === "https://youtu.be/") {
					$counter++;
					${"link" . $i} = substr(${"link" . $i},0,28);
				}
				else
				{
					die("Mindestens eine URL ist kein gültiges Youtube-Video: \"" . ${"link" . $i} . "\"</br>Die URL sollte folgendermaßen aussehen: https://www.youtube.com/watch?v=[VIDEO-ID] <b>ODER</b> https://youtu.be/[VIDEO-ID]</br><a href=\"index.html\">Back</a></br>");
				}
			}
			//Generiere eine eindeutige ID für die Sitzung. (Später für den Download wichtig!)
			$id = md5(uniqid().mt_rand());
			//Erstelle, falls nicht vorhanden, das Video-Downloadverzeichnis mit der eindeutigen ID.
			mkdir($videoDownloadVerzeichnis.$id, 0755, true);
			$linksForYTDL = "";
			//Addiere alle validen YT-Links zusammen in eine Variable und trenne die Links mit einer Leertaste.
			for($j = 1; $j <= $counter; $j++) {
				$linksForYTDL .= ${"link" . $j} . " ";
			}
			$downloadDirectory = $videoDownloadVerzeichnis.$id."/";
			//Führe youtube-dl aus und konvertiere die Videos zu MP3s.
			passthru("youtube-dl --cookies ".$videoDownloadVerzeichnis."cookies.txt --extract-audio --embed-thumbnail --restrict-filenames --audio-format mp3 --audio-quality 0 --output \"".$videoDownloadVerzeichnis.$id."/%(title)s.%(ext)s\" ".$linksForYTDL."> /dev/null");
			//Prüfe die Anzahl der Dateien im Downloadverzeichnis.
			$files = glob($downloadDirectory. '*.mp3');
			if ($files !== false) {
				$filecount = count($files);
				//Falls die Anzahl der Dateien im Downloadverzeichnis größer als 1 ist, dann zippe die MP3s. Ansonsten lade die MP3 direkt herunter.
				//Falls Videos denselben Titel haben, wird die Datei überschrieben. (TODO: Fix!)
				if ($filecount > 1) {
					shell_exec("cd ".$videoDownloadVerzeichnis.$id." && zip -r ytmp3-download.zip *");
					shell_exec("rm -rf -d ".$videoDownloadVerzeichnis.$id."/*.mp3");
					echo "<body style=\"background-color:#212529\"><h1 style=\"text-align:center;color:white\">MP3s herunterladen</h1><div style=\"text-align:center\"><a href=\"download.php?type=multiple&id=".$id."\" target=\"_blank\"><button style=\"top:50%;width:270px;background-color:DodgerBlue;border:none;color:white;padding:12px 30px;cursor:pointer;font-size:20px\">Download</button></a></div></body>";
				}
				else
				{
					echo "<body style=\"background-color:#212529\"><h1 style=\"text-align:center;color:white\">MP3 herunterladen</h1><div style=\"text-align:center\"><a href=\"download.php?type=single&id=".$id."\" target=\"_blank\"><button style=\"top:50%;width:270px;background-color:DodgerBlue;border:none;color:white;padding:12px 30px;cursor:pointer;font-size:20px\">Download</button></a></div></body>";
				}
			}
			//Falls keine Dateien im Downloadverzeichnis sind, gib eine Fehlermeldung aus.
			else
			{
				die("Keine Dateien gefunden!");
			}
		}
		//Gib eine Fehlermeldung aus, falls keine validen Links gesendet wurden.
		else
		{
			die("Ein Fehler ist aufgetreten. Bitte erneut versuchen.</br><a href=\"index.html\">Back</a>");
		}
	}
	else
	{
	// Nur POST erlaubt!
		header('Location: index.html');
	}
?>
