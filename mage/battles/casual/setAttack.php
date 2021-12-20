<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
session_start();

$player = unserialize($_SESSION["player"]);

$sql = "INSERT INTO casual_games (attacker_id, attack, defender_id) VALUES
    (" . $player->id . ", '" . $_POST["attackIds"] . "', " . $_POST["defenderId"] . ");";
if (!mysqli_query($link, $sql)) {
    echo json_encode("ERROR");
}else{
    unset($_SESSION["handElements"]);
    echo json_encode("Attack initiated!");
}
?>