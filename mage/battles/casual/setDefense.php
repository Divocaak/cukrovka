<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
require_once "../../objects/casualBattle.php";
session_start();

/* 
 [ ] get attacker elements
 [ ] get defender elements
 [ ] render used elements
 [ ] calculate winner
 [ ] update casual battle where id
 [ ] show winner
 */

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

$winner;
if($player->id == $battle->attacker->id){
    if($attackDmg > $defenseDmg){
        $ret = "<h2>win!</h2>" . $ret;
    }else{
        $ret = "<h2>lose!</h2>" . $ret;
    }
}else{
    if($attackDmg < $defenseDmg){
        $ret = "<h2>win!</h2>" . $ret;
    }else{
        $ret = "<h2>lose!</h2>" . $ret;
    }
}

echo json_encode($ret);
?>