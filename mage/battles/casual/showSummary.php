<?php
require_once "../../utils/config.php";
require_once "../../objects/player.php";
require_once "../../objects/casualBattle.php";
session_start();

$player = unserialize($_SESSION["player"]);

$sql = "SELECT id, start, end, winner_id, attacker_id, attack, defender_id, defense FROM casual_games WHERE id=" . $_POST["battleId"] . ";";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $battle = new CasualBattle($row[0], $row[1], $row[2], $row[3],
            get_player_by_id($row[4], $link), $row[5],
            get_player_by_id($row[6], $link), $row[7]);
    }
    mysqli_free_result($result);
}

$attackerElements = $battle->get_used_elements($link, false);
$defenderElements = $battle->get_used_elements($link, true);

$ret = '<tr class="table-danger"><td colspan="5"><b>' . $battle->attacker->username . '</b>\'s attack:</td></tr>';
foreach($attackerElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary, "");
}

$ret .= '<tr class="table-danger"><td colspan="5"><b>' . $battle->defender->username . '</b>\'s defense:</td></tr>';
foreach($defenderElements as $key => $element){
    $ret .= $element->renderAttackRow($elementGlossary, "");
}

$ret = "<h2>" . ($player->id == $battle->winner ? "win!" : "lose!") . "</h2>" . $ret;

if(isset($_POST["isHistory"])){
    echo json_encode($ret);
} else{
    $sql = "UPDATE casual_games SET attacker_seen=1 WHERE id=" . $battle->id . ";";
    if(!mysqli_query($link, $sql)){
        echo json_encode("ERROR " . $sql);
    }else{
        echo json_encode($ret);
    }
}
