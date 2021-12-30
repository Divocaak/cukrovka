<?php
session_start();
require_once "../objects/element.php";
require_once "../objects/tree.php";
require_once "../objects/player.php";
require_once "../utils/config.php";
require_once "../utils/elements.php";

$player = unserialize($_SESSION["player"]);
$trees = $player->get_trees_all($link);
$tree = $trees[$_GET["type"]];
$elements = $tree->get_elements_all($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Element tree</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="m-5">
        <a class="btn btn-outline-secondary" id="backBtn" data-tree-id=<?php echo $tree->id; ?> data-start-points=<?php echo $tree->points_spent; ?>><i class="bi bi-arrow-left"></i><i class="bi bi-save"></i></a>
        <h1 class="mt-5"><?php echo $tree->type_name; ?></h1>
        <div class="row mt-5">
            <div class="col-6">
                <?php
                echo '<h3 >Spent points: <b id="spentPoints">' . $tree->points_spent . '</b>, remaining: <b id="remainingPoints">' . $player->get_remaining_points($link, $maxPoints) . '</b></h3>';
                $overall = "";
                for ($i = 0; $i < count($trees); $i++) {
                    $thisTree = $i == $_GET["type"];
                    $overall .= "<i>" . ($trees[$i]->type_name . ($thisTree ? "</i>(<b id='overallSpentPoints'>" : "(") . $trees[$i]->points_spent . ($thisTree ? "</b>) " : ")</i> "));
                }
                echo '<p>in all trees: ' . $overall . '</p>';
                ?>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-center">
                <a class="btn btn-danger mx-2" id="pointMinus"><i class="bi bi-dash-circle"></i></a>
                <a class="btn btn-success mx-2" id="pointPlus" data-el-count=<?php echo count($elements); ?>><i class="bi bi-plus-circle"></i></a>
            </div>
            <div class="col-12">
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Tier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($elements); $i++) {
                            $el = $elements[$i];
                            $el->renderRow($elementGlossary, intval($el->tier_id) <= intval($tree->points_spent), $i);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#pointMinus").click(function() {
                var spentPointsText = $("#spentPoints");
                let spentPoints = parseInt(spentPointsText.text());
                if (spentPoints > 0) {
                    let newSpentPoints = spentPoints - 1;

                    var element = $("#element" + newSpentPoints);
                    let beforeColor = element.data("before-color");
                    if (beforeColor == "table-success") beforeColor = "table-warning";
                    element.removeClass().addClass(beforeColor);

                    spentPointsText.text(newSpentPoints);
                    $("#overallSpentPoints").text(newSpentPoints);
                    $("#remainingPoints").text(parseInt($("#remainingPoints").text()) + 1);
                }
            });

            $("#pointPlus").click(function() {
                var spentPointsText = $("#spentPoints");
                let spentPoints = parseInt(spentPointsText.text());
                if ((spentPoints + 1) <= parseInt($(this).data("elCount"))) {
                    var remPointsText = $("#remainingPoints");
                    let remPoints = parseInt(remPointsText.text());
                    if (remPoints > 0) {
                        var element = $("#element" + spentPoints);
                        let newColor = (element.attr("class") == "table-danger" ? "table-info" : "table-success");
                        element.removeClass().addClass(newColor);

                        let newPoints = spentPoints + 1
                        spentPointsText.text(newPoints);
                        $("#overallSpentPoints").text(newPoints);
                        remPointsText.text(remPoints - 1);
                    }
                }
            });

            $("#backBtn").click(function() {
                let spentPoints = parseInt($("#spentPoints").text());
                if (spentPoints != parseInt($(this).data("startPoints"))) {
                    let treeId = parseInt($(this).data("treeId"));
                    $.ajax({
                        type: 'POST',
                        url: 'saveTree.php',
                        data: {
                            id: treeId,
                            spentPoints: spentPoints
                        },
                        success: function(response) {
                            location.href = '../home.php';
                            alert(response);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        },
                        dataType: "json"
                    });
                } else {
                    location.href = '../home.php';
                }
            });
        });
    </script>

    <?php footer($gameName); ?>
</body>

</html>