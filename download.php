<?php
if(isset($_GET['type']) && isset($_GET['id']) && strlen($_GET['id']) == 32 && $_GET['type'] == "single" || $_GET['type'] == "multiple") {
	$id = $_GET['id'];
	$type = $_GET['type'];
	$videoDownloadVerzeichnis = "/PATH/TO/DOWNLOADS/";
	//Exkludiere alle "Nicht-Wortzeichen" in RegEx-style ;-)
	$blacklistedCHARs = "/[\W]+/";
	if ($type == "multiple" && !preg_match($blacklistedCHARs, $id) && file_exists($videoDownloadVerzeichnis.$id)) {
		$file_path = $videoDownloadVerzeichnis.$id."/ytmp3-download.zip";
		header('Content-Type: application/zip');
		header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"" . basename($file_path) . "\"");
		readfile($file_path);
		exit();
	}
	elseif ($type == "single" && !preg_match($blacklistedCHARs, $id) && file_exists($videoDownloadVerzeichnis.$id)) {
		$dir = $videoDownloadVerzeichnis.$id."/";
		$dirList = scandir($dir);
		//Array Wert 2, da 0: ".", 1: ".." & 2: "gesuchte.mp3".
		$mp3File = $dirList[2];
		$totalPathTosingleMp3File = $dir . $mp3File;
		header('Content-Type: audio/mpeg');
                header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"" . basename($totalPathTosingleMp3File) . "\"");
                readfile($totalPathTosingleMp3File);
                exit();
	}
	//Gib eine Fehlermeldung raus, falls die ID nicht existiert.
	else
	{
		die("Ungültige ID.</br><a href=\"index.html\">Back</a>");
	}
}
//Falls keine der zwei URL Parameter übergeben werden, leite an die Hauptseite weiter.
else
{
	header('Location: index.html');
}
?>
