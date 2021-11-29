<?php
session_start();
require_once "../objects/element.php";
require_once "../objects/tree.php";
require_once "../objects/player.php";
require_once "../utils/config.php";
require_once "../utils/elements.php";

$tree = unserialize($_SESSION["player"])->get_tree($link, $_GET["type"]);
$elements = $tree->get_elements_all($link);
print_r($elements);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balíčky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="m-5">
        <a class="btn btn-outline-secondary" href="../home.php"><i class="bi bi-arrow-left"></i></a>
        <div class="mt-5">
            <h1>Moje balíčky</h1>
            <div class="row">
                <?php
                if ($packs != []) {
                    echo '<div class="col-2"><a class="btn btn-outline-secondary" href="createPack.php"><i class="bi bi-plus-circle"></i><i class="bi bi-files"></i></a></div>';
                    foreach ($packs as $pack) {
                        echo '<div class="col-12 mt-5"><div class="row">';
                        echo '<div class="col-1"><a class="btn btn-outline-danger deletePackBtn" data-pack-id="' . $pack->id . '"><i class="bi bi-trash"></i></a></div>';
                        echo '<div class="col-11"><h2>' . $pack->name . " (" . count($pack->get_cards($link)) . "/" . $maxCardsInPack . "): " . '</h2></div>';
                        echo '<div class="col-12"><div class="row">';
                        if ($pack->get_cards($link)) {
                            $cardsCounted = [];
                            foreach ($pack->cards as $card) {
                                if (in_array($card->id, $cardsCounted)) {
                                    $cardsCounted[$card->id]++;
                                } else {
                                    $cardsCounted[$card->id]++;
                                }
                            }
                            foreach ($pack->cards as $card) {
                                if($cardsCounted[$card->id] != 0){
                                    $card->renderCard($cardGlossary, false, "../", $cardsCounted[$card->id]);
                                    $cardsCounted[$card->id] = 0;
                                }
                            }
                        } else {
                            echo "<p class='text-muted'>U balíčku '" . $pack->name . "' se něco nepovedlo.</p>";
                        }

                        echo '</div></div></div></div>';
                    }
                } else {
                    echo '<p>Zatím nemáte vytvořený žádný balíček, vytvořte jej zde: <a class="btn btn-primary" href="createPack.php">
                    <i class="bi bi-plus-circle"></i><i class="bi bi-files"></i></a></p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $(".deletePackBtn").click(function() {
                let packId = $(this).data("packId");
                $.ajax({
                    type: 'POST',
                    url: 'scripts/deletePack.php',
                    data: {
                        id: packId
                    },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    },
                    dataType: "json"
                });
            });
        });
    </script> -->

    <?php footer($gameName); ?>
</body>

</html>