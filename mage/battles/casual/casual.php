<?php
session_start();
require_once "../../objects/player.php";
require_once "../../utils/config.php";
require_once "../../utils/elements.php";

$player = unserialize($_SESSION["player"]);

$defenderIds = "(";
$defs = [];
$sql = 'SELECT id, username, level FROM players WHERE id != ' . $player->id . ' AND level BETWEEN ' . ($player->level - $maxLevelDifference) . ' AND ' . ($player->level + $maxLevelDifference) . ';';
if ($result = mysqli_query($link, $sql)) {
    $index = 0;
    while ($row = mysqli_fetch_row($result)) {
        $defenderIds .= ($index > 0 ? ", " : "") . $row[0];
        $defs[$row[0]] = ["username" => $row[1], "level" => $row[2]];
        $index++;
    }
    $defenderIds .= ")";
    mysqli_free_result($result);
}

$idsToFight = [];
if ($defenderIds != "(") {
    $sql = 'SELECT defender_id, count(id) FROM casual_games WHERE winner_id IS NULL AND defender_id IN ' . $defenderIds . ' GROUP BY defender_id;';
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            if ($row[1] <= $maxOpenBattles) {
                $idsToFight[] = $row[0];
            }
        }
        mysqli_free_result($result);
    }
}

$defenderId = 0;
if ($idsToFight != []) {
    $defenderId = $idsToFight[array_rand($idsToFight)];

    $defenderTrees = $player->get_trees_all($link, $defenderId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Casual battle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="row m-5">
        <div class="col-12">
            <?php echo "<h2>" . $defs[$defenderId]["username"] . "'s (lvl: " . $defs[$defenderId]["level"] . ") trees</h2>"; ?>
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">I</th>
                        <th scope="col">II</th>
                        <th scope="col">III</th>
                        <!-- TODO přidělat další tiery (nebo dynamicky?) -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($defenderTrees as $defenderTree) {
                        $defenderTree->render_elements_simplified($link);
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <h1>Me</h1>
            <h2>Attack</h2>
            <!-- <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody>
                    na vyložení
                </tbody>
            </table> -->
            <h2>Hand</h2>
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Tier</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $allElements = [];
                    foreach ($player->get_trees_all($link) as $tree) {
                        foreach($tree->get_elements_all($link) as $element){
                            if($element->tier_id <= $tree->points_spent){
                                array_push($allElements, $element);
                            }
                        }
                    }

                    $hand = array_rand($allElements, $elementsPerHand);
                    foreach ($hand as $elementId) {
                        $allElements[$elementId]->renderHandRow($elementGlossary);
                    }
                    ?>
                </tbody>
            </table>
        </div>


        [ ] ošetřit quitnutí před zahájením battleu<br>
        [x] zíksat defender_id a jeho trees<br>
        [x] vybrat random moje elementy<br>
        [ ] skládání<br>
        [ ] INSERT INTO attacks<br>
        [ ] zobrazit trees, INSERT INTO casual_games (start, defender_id, attack_id)<br>
        [ ] na defender_id poslat zprávu o útoku<br>
        [ ] INSERT INTO defenses<br>
        [ ] UPDATE casual_games<br>
        [ ] poslat zprávu hráčům<br>
    </div>

    <?php footer($gameName); ?>
</body>

</html>