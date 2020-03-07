<!DOCTYPE HTML>
<?php
	$request_method = $_SERVER['REQUEST_METHOD'];
	if ($request_method == 'POST') {
		$linkvars = array("link1","link2","link3","link4","link5","link6","link7","link8","link9","link10");
		if (!count($_POST) > 10 || !empty($_POST) || count(array_intersect($linkvars, $_POST)) > 0){
			$counter = 0;
			for($i = 1; $i <= count($_POST); $i++) {
				${"link" . $i} = $_POST['link'.$i];
				if (strlen(${"link" . $i}) != 43 || substr(${"link" . $i}, 0, 32) !== "https://www.youtube.com/watch?v=") {
					die("Mindestens eine URL ist kein gültiges Youtube-Video: \"" . ${"link" . $i} . "\"</br>URL sollte so aussehen: https://www.youtube.com/watch?v=[VIDEO-ID]</br><a href=\"index.html\">Back</a></br>");
				}
				$counter++;
			}
			echo $counter . " gültige Links gefunden.</br>";
		}
		else
		{
			die("Error. Bitte erneut versuchen.</br><a href=\"index.html\">Back</a>");
		}
	}
	else
	{
	// Nur POST erlaubt!
        http_response_code(405);
		echo "Nope! Try \"POST\" instead.</br><a href=\"index.html\">Back</a>";
	}
?>
