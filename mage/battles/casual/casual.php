<?php
require_once "../../objects/player.php";
require_once "../../utils/config.php";
require_once "../../utils/elements.php";
session_start();

$player = unserialize($_SESSION["player"]);

$defs = [];
$sql = 'SELECT id, username, level FROM players WHERE id != ' . $player->id . ' AND level BETWEEN ' . ($player->level - $maxLevelDifference) . ' AND ' . ($player->level + $maxLevelDifference) . ';';
if ($result = mysqli_query($link, $sql)) {
    $index = 0;
    while ($row = mysqli_fetch_row($result)) {
        $defs[$row[0]] = ["username" => $row[1], "level" => $row[2]];
        $index++;
    }
    mysqli_free_result($result);
}

$idsToFight = [];
if ($defs != []) {
    $ids = "";
    foreach(array_keys($defs) as $key => $id) $ids .= $id . (end(array_keys($defs)) == $key ? "," : "");
    $sql = 'SELECT defender_id, COUNT(id) FROM casual_games WHERE winner_id IS NULL AND defender_id IN (' . $ids . ') GROUP BY defender_id;';
    if ($result = mysqli_query($link, $sql)) {
        if(mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_row($result)) {
                if ($row[1] <= $maxOpenBattles) {
                    $idsToFight[] = $row[0];
                }
            }
        }else{
            $idsToFight = array_keys($defs);
        }
        mysqli_free_result($result);
    }
}

$defenderId = 0;
if ($idsToFight != []) {
    $defenderId = $idsToFight[array_rand($idsToFight)];

    $defenderTrees = $player->get_trees_all($link, $defenderId);
}

$unlockedElements = [];
foreach ($player->get_trees_all($link) as $tree) {
    foreach ($tree->get_elements_all($link) as $element) {
        if ($element->tier_id <= $tree->points_spent) {
            array_push($unlockedElements, $element);
        }
    }
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
            <?php echo "<h2 id='defenderData' data-defender-id='" . $defenderId . "'>" . $defs[$defenderId]["username"] . "'s (lvl: " . $defs[$defenderId]["level"] . ") trees</h2>"; ?>
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
        <div class="col-12 mt-5">
            <div class="row">
                <div class="col-9">
                    <h2>My hand</h2>
                </div>
                <div class="col-3">
                    <a class="btn btn-danger" id="attackBtn">Attack!</a>
                </div>
            </div>
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
                <tbody id="handBody">
                    <?php
                    if ($_SESSION["handElements"] == [] || !isset($_SESSION["handElements"])) {
                        $hand = [];
                        for ($i = 0; $i < $drawIterations; $i++) {
                            $hand = array_merge($hand, array_rand($unlockedElements, $elementsPerIteration));
                        }

                        foreach ($hand as $key => $elementId) {
                            $hand[$key] = $unlockedElements[$elementId];
                            $unlockedElements[$elementId]->renderHandRow($elementGlossary, $key);
                        }

                        $_SESSION["handElements"] = $hand;
                    } else {
                        $hand = $_SESSION["handElements"];
                        foreach ($_SESSION["handElements"] as $key => $element) {
                            $element->renderHandRow($elementGlossary, $key);
                        }
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
                    <button type="button" class="btn btn-success" id="confirmMerge">Merge</button>
                </div>
            </div>
        </div>
    </div>

    [ ] ošetřit quitnutí před zahájením battleu<br>
    [x] zíksat defender_id a jeho trees<br>
    [x] vybrat random moje elementy<br>
    [x] skládání<br>
    [x] get ids na attack<br>
    [x] INSERT INTO casual_games<br>
    [ ] Battles na HomePage
        [ ] Attacker - outgoing attacks
        [ ] Defender - incoming attacks
    [ ] UPDATE casual_games o defense<br>
    [ ] poslat zprávu hráčům o výherci<br>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            let selectedColor = "table-warning";
            $(".useBtn").click(function() {
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

            let firstElementKey;
            let firstElementId;
            $(".mergeBtn").click(function() {
                var thisBtn = $(this);
                var thisBtnXthParent = thisBtn.parent().parent().parent();
                let thisBtnClass = thisBtn.attr("class");
                var elementId = thisBtnXthParent.data("elementId");
                var elementKey = thisBtnXthParent.data("handKey");

                if (thisBtnClass == "btn btn-info mx-2 mergeBtn") {
                    $(".mergeBtn").removeClass().addClass("btn btn-outline-info mx-2 mergeBtn");
                    thisBtn.removeClass().addClass("btn btn-danger mx-2 mergeBtn");
                    thisBtn.text("Cancel");

                    firstElementKey = elementKey;
                    firstElementId = elementId;

                    $(".useBtn").hide();
                } else if (thisBtnClass == "btn btn-danger mx-2 mergeBtn") {
                    $(".mergeBtn").removeClass().addClass("btn btn-info mx-2 mergeBtn");
                    thisBtn.text("Merge");

                    firstElementKey = null;
                    firstElementId = null;

                    $(".useBtn").show();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'getCombination.php',
                        data: {
                            id1: firstElementId,
                            id2: elementId,
                            key1: firstElementKey,
                            key2: elementKey
                        },
                        success: function(response) {
                            if (response != "ERROR") {
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

            $("#confirmMerge").click(function() {
                let combId = $("#mergePreview").data("resultId");
                let key1 = $("#comb1").data("handKey");
                let key2 = $("#comb2").data("handKey");
                if (combId == 0) {
                    alert("Cannot combine");
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'confirmCombination.php',
                        data: {
                            combinationId: combId,
                            key1: key1,
                            key2: key2
                        },
                        success: function(response) {
                            $("#handBody").load(location.href + " #handBody");
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        },
                        dataType: "json"
                    });

                }
                $("#mergeModal").modal("hide");
            });

            $("#attackBtn").click(function() {
                var attackIds = "";
                $('#handBody > tr').each(function() {
                    if($(this).attr("class") == selectedColor){
                        attackIds += $(this).data("elementId") + "-";
                    }
                });

                if(attackIds != ""){
                    attackIds = attackIds.substring(0, attackIds.length - 1);
                    var defenderId = parseInt($("#defenderData").data("defenderId"));
                    $.ajax({
                        type: 'POST',
                        url: 'setAttack.php',
                        data: {
                            attackIds: attackIds,
                            defenderId: defenderId
                        },
                        success: function(response) {
                            alert(response);
                            location.href = '../../home.php';
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        },
                        dataType: "json"
                    });
                }
                else{
                    alert("You have to select at least 1 element to use");
                }
            });
        });
    </script>

    <?php footer($gameName); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</body>

</html>