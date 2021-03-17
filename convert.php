<!DOCTYPE HTML>
<?php
	$request_method = $_SERVER['REQUEST_METHOD'];
	$videoDownloadVerzeichnis = "/path/to/ytmp3-downloads/";
	$cookiesVerzeichnis = "/path/to/ytmp3-downloads-cookies.txt/";
	if ($request_method == 'POST') {
		//Maximum of 10 links supported for now.
		$linkvars = array("link1","link2","link3","link4","link5","link6","link7","link8","link9","link10");
		//Check, if POST values are correct.
		if (!count($_POST) > 10 || !empty($_POST) || count(array_intersect($linkvars, $_POST)) > 0){
			//Counter counts the amount of valid links.
			$counter = 0;
			//Cut links to their minimal required length.
			//Allowed are currently: youtube.com & youtu.be.
			//youtube-dl supported videolinks can be added here in the future.
			for($i = 1; $i <= count($_POST); $i++) {
				${"link" . $i} = $_POST['link'.$i];
				if (substr(${"link" . $i}, 0, 32) === "https://www.youtube.com/watch?v=" && preg_match("/[0-9A-Za-z_-]{10}[048AEIMQUYcgkosw]/", substr(${"link" . $i}, 32, 11))) {
					$counter++;
					${"link" . $i} = substr(${"link" . $i},0,43);
				}
				elseif (substr(${"link" . $i}, 0, 17) === "https://youtu.be/" && preg_match("/[0-9A-Za-z_-]{10}[048AEIMQUYcgkosw]/", substr(${"link" . $i}, 17, 11))) {
					$counter++;
					${"link" . $i} = substr(${"link" . $i},0,28);
				}
				else
				{
					die("At least one videolink is not correct: \"" . ${"link" . $i} . "\"</br>The link should look like this: https://www.youtube.com/watch?v=[VIDEO-ID] <b>OR</b> https://youtu.be/[VIDEO-ID]</br><a href=\"index.html\">Back</a></br>");
				}
			}
			//Generate unique ID. 
			$id = md5(uniqid().mt_rand());
			//Create download folder with unique ID.
			mkdir($videoDownloadVerzeichnis.$id, 0755, true);
			$linksForYTDL = "";
			//Add links together and seperate by whitespaces.
			for($j = 1; $j <= $counter; $j++) {
				$linksForYTDL .= ${"link" . $j} . " ";
			}
			$downloadDirectory = $videoDownloadVerzeichnis.$id."/";
			//Execute youtube-dl on shell
			passthru("python3 /usr/bin/youtube-dl --cookies ".$cookiesVerzeichnis."cookies.txt --extract-audio --restrict-filenames --audio-format mp3 --audio-quality 0 --output \"".$videoDownloadVerzeichnis.$id."/%(title)s.%(ext)s\" ".$linksForYTDL."> /dev/null");
			//Check filecount in download directory.
			$files = glob($downloadDirectory.'*.mp3');
			if ($files !== false) {
				$filecount = count($files);
				//If more than one file, download zipped file.
				//Videos with the same video title are overwritten by the last entry. (To avoid multiple downloads of the same video)
				if ($filecount > 1) {
					shell_exec("cd ".$videoDownloadVerzeichnis.$id." && zip -r ytmp3-download.zip *");
					shell_exec("rm -rf -d ".$videoDownloadVerzeichnis.$id."/*.mp3");
					echo "<body style=\"background-color:#212529\"><h1 style=\"text-align:center;color:white\">Download MP3s</h1><div style=\"text-align:center\"><a href=\"download.php?type=multiple&id=".$id."\" target=\"_blank\"><button style=\"top:50%;width:270px;background-color:DodgerBlue;border:none;color:white;padding:12px 30px;cursor:pointer;font-size:20px\">Download</button></a></div></body>";
				}
				else
				{
					echo "<body style=\"background-color:#212529\"><h1 style=\"text-align:center;color:white\">Download MP3</h1><div style=\"text-align:center\"><a href=\"download.php?type=single&id=".$id."\" target=\"_blank\"><button style=\"top:50%;width:270px;background-color:DodgerBlue;border:none;color:white;padding:12px 30px;cursor:pointer;font-size:20px\">Download</button></a></div></body>";
				}
			}
			//No files in download directory
			else
			{
				die("No files found in directory!");
			}
		}
		//Throw error if no valid links found
		else
		{
			die("An error occured. Please try again.</br><a href=\"index.html\">Back</a>");
		}
	}
	else
	{
	// Only allow POST
		header('Location: index.html');
	}
?>
