<!DOCTYPE HTML>
<?php
	$request_method = $_SERVER['REQUEST_METHOD'];
	if ($request_method == 'POST') {
		$linkvars = array("link1","link2","link3","link4","link5","link6","link7","link8","link9","link10");
		if (!count($_POST) > 10 || !empty($_POST) || count(array_intersect($linkvars, $_POST)) > 0){
			$counter = 0;
			for($i = 1; $i <= count($_POST); $i++) {
				${"link" . $i} = $_POST['link'.$i];
				if (strlen(${"link" . $i}) == 43 && substr(${"link" . $i}, 0, 32) === "https://www.youtube.com/watch?v=" || strlen(${"link" . $i}) == 28 && substr(${"link" . $i}, 0, 17) === "https://youtu.be/") {
					$counter++;
				}
				else
				{
					die("Mindestens eine URL ist kein gültiges Youtube-Video: \"" . ${"link" . $i} . "\"</br>Die URL sollte folgendermaßen aussehen: https://www.youtube.com/watch?v=[VIDEO-ID] <b>ODER</b> https://youtu.be/[VIDEO-ID]</br><a href=\"index.html\">Back</a></br>");
				}
			}
			$id = md5(uniqid().mt_rand());
			mkdir('/etc/yt-webdownloads/'.$id, 0755, true);
			$linksForYTDL = "";
			for($j = 1; $j <= $counter; $j++) {
				$linksForYTDL .= ${"link" . $j} . " ";
			}
			$downloadDirectory = "/etc/yt-webdownloads/".$id."/";
			passthru("youtube-dl --cookies /etc/yt-webdownloads/cookies.txt --extract-audio --embed-thumbnail --audio-format mp3 --audio-quality 0 --output \"/etc/yt-webdownloads/".$id."/%(title)s.%(ext)s\" ".$linksForYTDL."> /dev/null");
			$files = glob($downloadDirectory. '*.mp3');
			if ($files !== false) {
				$filecount = count($files);
				if ($filecount > 1) {
					shell_exec("cd /etc/yt-webdownloads/".$id." && zip -r ytmp3-download.zip *");
					shell_exec("rm -rf -d /etc/yt-webdownloads/".$id."/*.mp3");
					echo "<a style=\"font-size:80px\" href=\"download.php?type=multiple&id=".$id."\" target=\"_blank\">DOWNLOAD!</a>";
				}
				else
				{
					echo "<a style=\"font-size:80px\" href=\"download.php?type=single&id=".$id."\" target=\"_blank\">DOWNLOAD!</a>";
				}
			}
			else
			{
				die("Keine Dateien gefunden!");
			}
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
