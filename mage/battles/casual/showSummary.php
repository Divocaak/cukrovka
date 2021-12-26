<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
require_once "../../objects/casualBattle.php";
session_start();

$player = unserialize($_SESSION["player"]);

$sql = "SELECT id, start, end, winner, winner_id, attacker_id, attack, defender_id, defense
FROM casual_games WHERE id=" . $_POST["battleId"] . ";";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $battle = new CasualBattle($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
    }
    mysqli_free_result($result);
}

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
WHERE e.id IN (' . str_replace("-", ", ", $battle->defense) .');';
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $defenderElements[] = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }
    mysqli_free_result($result);
}

$ret = '<tr class="table-danger"><td colspan="5"><b>' . $battle->attacker->username . '</b>\'s attack:</td></tr>';
foreach($attackerElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary);
}

$ret .= '<tr class="table-danger"><td colspan="5"><b>' . $battle->defender->username . '</b>\'s defense:</td></tr>';
foreach($defenderElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary);
}

$ret = "<h2>" . ($player->id > $battle->winner ? "win!" : "lose!") . "</h2>" . $ret;

$sql = "UPDATE casual_games SET attacker_seen=1, ' WHERE id=" . $battle->id . ";";
if(!mysqli_query($link, $sql)){
    echo json_encode("ERROR " . $sql);
}else{
    echo json_encode($ret);
}
?>