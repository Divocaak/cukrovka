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

// XXX settings
// game
$version = "(alfa)";
$gameName = "Mage (možná přejmenovat) " . $version;
// packs
$maxCardsPerPack = 33;// TODO delete l8r
$maxCardsInPack = 69;// TODO delete l8r
$cardGlossary = ["param" => "parametr?", "asd" => "asd pro test, klasa", "pls" => "prosím"];
// battles
$maxOpenBattles = 10;
$battleNames = ["Sevirias", "Avicete", "Castedura", "Lleimería", "Binse", "Tarramadura", "Selusia",
    "Varias", "Ciugovia", "Cuevila", "Mácete", "Vallavila", "Cantaria", "Bares", "Raeleros", "Andabria",
    "Castirbella", "Salastián", "Navardoba", "Cartavedra"];
?>