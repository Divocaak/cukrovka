<?php
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'mage');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link,"utf8");

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// NOTE settings
// game
$gameName = "Mage <i>(mby přejmenovat)</i> " . $version;
$version = "<i>(dev state)</i>";
// trees
$maxPoints = 10;
// elements
$elementGlossary = [
    "f" => "fire param",
    "ff" => "fire param 2",
    "fff" => "fire param 3",
    "w" => "water param",
    "ww" => "water param 2",
    "www" => "water param 3",
    "e" => "earth param",
    "ee" => "earth param 2",
    "eee" => "earth param 3",
    "a" => "air param",
    "aa" => "air param 2",
    "aaa" => "air param 3"
];
// casuals
$maxOpenBattles = 5;
$maxWinRateDifference = 5; // TODO chceme v caualech i podle winrateu?
$maxLevelDifference = 5;
$elementsPerHand = 5;
?>