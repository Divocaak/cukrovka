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
        <h1>Casual battle</h1>
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
                    <?php foreach ($defenderTrees as $defenderTree) {
                        $defenderTree->render_elements_simplified($link);
                    } ?>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <h2>My hand</h2>
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Tier</th>
                        <th scope="col">Description</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $allElements = [];
                    foreach ($player->get_trees_all($link) as $tree) {
                        foreach ($tree->get_elements_all($link) as $element) {
                            if ($element->tier_id <= $tree->points_spent) {
                                array_push($allElements, $element);
                            }
                        }
                    }

                    $hand = [];
                    for ($i = 0; $i < $drawIterations; $i++) {
                        $hand = array_merge($hand, array_rand($allElements, $elementsPerIteration));
                    }

                    $_SESSION["allElements"] = $allElements;
                    $_SESSION["handElementIds"] = $hand;

                    foreach ($hand as $elementId) {
                        $allElements[$elementId]->renderHandRow($elementGlossary);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="mergeModal" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Merge preview</h5>
                </div>
                <div class="modal-body">
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
                        <tbody id="mergeModalBody">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success">Merge</button>
                </div>
            </div>
        </div>
    </div>

    [ ] ošetřit quitnutí před zahájením battleu<br>
    [x] zíksat defender_id a jeho trees<br>
    [x] vybrat random moje elementy<br>
    [ ] skládání<br>
    [ ] get ids na attack<br>
    [ ] INSERT INTO attacks<br>
    [ ] zobrazit trees, INSERT INTO casual_games (start, defender_id, attack_id)<br>
    [ ] na defender_id poslat zprávu o útoku<br>
    [ ] INSERT INTO defenses<br>
    [ ] UPDATE casual_games<br>
    [ ] poslat zprávu hráčům<br>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".useBtn").click(function() {
                let selectedColor = "table-warning";
                var row = $(this).parent().parent().parent();
                var btn = $(this).siblings(".mergeBtn");
                if (row.attr("class") == selectedColor) {
                    btn.show();
                    row.removeClass();
                } else {
                    btn.hide();
                    row.addClass(selectedColor);
                }
            });

            let firstElement;
            $(".mergeBtn").click(function() {
                var thisBtn = $(this);
                let thisBtnClass = thisBtn.attr("class");
                var elementId = thisBtn.parent().parent().parent().data("elementId");

                if (thisBtnClass == "btn btn-info mx-2 mergeBtn") {
                    $(".mergeBtn").removeClass().addClass("btn btn-outline-info mx-2 mergeBtn");
                    thisBtn.removeClass().addClass("btn btn-danger mx-2 mergeBtn");
                    thisBtn.text("Cancel");

                    firstElement = elementId;

                    $(".useBtn").hide();
                } else if (thisBtnClass == "btn btn-danger mx-2 mergeBtn") {
                    $(".mergeBtn").removeClass().addClass("btn btn-info mx-2 mergeBtn");
                    thisBtn.text("Merge");

                    firstElement = null;

                    $(".useBtn").show();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'getCombination.php',
                        data: {
                            element1: firstElement,
                            element2: elementId
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.charAt(0) != "!") {
                                $("#mergeModalBody").html(response);
                                $("#mergeModal").modal("show");
                            } else {
                                alert(response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        },
                        dataType: "json"
                    });
                }
            });
        });
    </script>

    <?php footer($gameName); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>