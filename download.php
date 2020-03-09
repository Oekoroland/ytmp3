<?php
//If required GET vars are not set, empty or wrong, die.
if(isset($_GET['type']) && isset($_GET['id']) && strlen($_GET['id']) == 32 && $_GET['type'] == "single" || $_GET['type'] == "multiple") {
	$id = $_GET['id'];
	$type = $_GET['type'];
	if ($type == "multiple" && file_exists("/PATH/TO/DOWNLOADS/".$id)) {
		$file_path = "/PATH/TO/DOWNLOADS/".$id."/ytmp3-download.zip";
		header('Content-Type: application/zip');
		header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"" . basename($file_path) . "\"");
		readfile($file_path);
		exit();
	}
	elseif ($type == "single" && file_exists("/PATH/TO/DOWNLOADS/".$id)) {
		$dir = "/PATH/TO/DOWNLOADS/".$id."/";
		$dirList = scandir($dir);
		$mp3File = $dirList[2];
		$totalPathTosingleMp3File = $dir . $mp3File;
		header('Content-Type: audio/mpeg');
                header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=\"" . basename($totalPathTosingleMp3File) . "\"");
                readfile($totalPathTosingleMp3File);
                exit();
	}
	else
	{
		die("Ungültige ID.</br><a href=\"index.html\">Back</a>");
	}
}
else
{
	header('Location: index.html');
}
?>
