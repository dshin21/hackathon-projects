<?php
session_start();
extract($_POST);

$myfile = fopen("testfile.txt", "a");
$txt = $name . " break ";
fwrite($myfile, $txt);
$adrs = $address . " " . $address2 . " " . $city . " " . $province . " " . $zip . "\r\n";
$_SESSION['adrs'] = $adrs;
fwrite($myfile, $adrs);
fclose($myfile);
header('Location: index.php');
?>

