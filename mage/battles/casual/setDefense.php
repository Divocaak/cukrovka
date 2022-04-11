<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
require_once "../../objects/casualBattle.php";
session_start();

$player = unserialize($_SESSION["player"]);
$battle = unserialize($_SESSION["battle"]);

$attackerElements = $battle->get_used_elements($link, false);
$defenderElements = $battle->get_used_elements($link, true);

$attackDmg = 0;
$ret = '<tr class="table-danger"><td colspan="5"><b>' . $battle->attacker->username . '</b>\'s attack:</td></tr>';
foreach($attackerElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary);
    $attackDmg += $element->params["dmg"];
}

$defenseDmg = 0;
$ret .= '<tr class="table-danger"><td colspan="5"><b>' . $battle->defender->username . '</b>\'s defense:</td></tr>';
foreach($defenderElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary);
    $defenseDmg += $element->params["dmg"];
}

if($player->id == $battle->attacker->id){
    $ret = "<h2>" . ($attackDmg > $defenseDmg ? "win!" : "lose!") . "</h2>" . $ret;
    $winner = ($attackDmg > $defenseDmg ? $player->id : $battle->attacker->id);
}else{
    $ret = "<h2>" . ($attackDmg < $defenseDmg ? "win!" : "lose!") . "</h2>" . $ret;
    $winner = ($attackDmg < $defenseDmg ? $player->id : $battle->attacker->id);
}

$sql = "UPDATE casual_games SET end=CURRENT_TIMESTAMP, winner_id=" . $winner . ", defense='" . $_POST["defendIds"] .  "' WHERE id=" . $battle->id . ";";
if(!mysqli_query($link, $sql)){
    echo json_encode("ERROR " . $sql);
}else{
    echo json_encode($ret);
}
?>