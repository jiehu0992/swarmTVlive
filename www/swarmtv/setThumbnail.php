<?php
$id=$_GET["id"];
$currentPos=$_GET["currentPos"];

//connect to database
$db = mysql_connect('localhost:3306', 'root', '');
if (!$db) {
	echo "Error: Could not connect to database. Please try again later.";
	exit;
}
mysql_select_db('web38-swarmtv');

$query = "SELECT * FROM `elements` WHERE `id` = '".$id."';";

//echo $query."<br>";
$result = mysql_query($query);
$record = mysql_fetch_assoc($result);
//echo $record['filename']."<br>";

//set string variables for ffmpeg string
$filename = $record['filename'];
$filename = substr($filename, 0, -4);
//$videoDirectory = "/Users/media/Sites/swarmTVlive/www/swarmtv/assets/video/";
$videoDirectory = "/var/www/swarmtv/assets/video/";
//$videopostersDirectory = "/Users/media/Sites/swarmTVlive/www/swarmtv/assets/videoposters/";
$videopostersDirectory = "/var/www/swarmtv/assets/videoposters/";

//create Terminal string for ffmpeg and execute it
chdir('assets/video/');
$makeFrameString = "/usr/local/bin/ffmpeg -i " . $filename . ".mp4";
$makeFrameString = $makeFrameString . " -vframes 1 -an -s 200x115 -ss " . $currentPos . " ";
$makeFrameString = $makeFrameString . $videopostersDirectory . $filename . ".jpg </dev/null >/dev/null 2>/var/log/ffmpeg.log &";
echo "makeFrameString = ".$makeFrameString . "\n";
$execute = shell_exec($makeFrameString);
echo "\n\nmakeFrameString = ".$makeFrameString . "\n";



?>
