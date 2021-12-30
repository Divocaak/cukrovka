<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
require_once "../../objects/casualBattle.php";
session_start();

$player = unserialize($_SESSION["player"]);
$battle = unserialize($_SESSION["battle"]);

$attackerElements = [];
$sql = 'SELECT e.id, e.name, e.params, e.tier_id, ti.name, ty.name 
FROM ELEMENTS e INNER JOIN tiers ti ON ti.id=e.tier_id 
INNER JOIN types ty ON ty.id=e.type_id 
WHERE e.id IN (' . str_replace("-", ", ", $battle->attack) .');';
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $attackerElements[] = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }
    mysqli_free_result($result);
}

$defenderElements = [];
$sql = 'SELECT e.id, e.name, e.params, e.tier_id, ti.name, ty.name 
FROM ELEMENTS e INNER JOIN tiers ti ON ti.id=e.tier_id 
INNER JOIN types ty ON ty.id=e.type_id 
WHERE e.id IN (' . str_replace("-", ", ", $_POST["defendIds"]) .');';
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $defenderElements[] = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }
    mysqli_free_result($result);
}

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