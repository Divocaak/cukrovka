<?php
session_start();
require_once "objects/player.php";
require_once "objects/card.php";
require_once "utils/config.php";
require_once "utils/elements.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Karty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="m-5">
        <a class="btn btn-outline-secondary" href="home.php"><i class="bi bi-arrow-left"></i></a>
        <div class="row mt-5">
            <h1>Sbírka karet</h1>
            <?php
            foreach (unserialize($_SESSION["player"])->get_unlocked_cards($link) as $card) {
                $card->renderCard($cardGlossary, false, "", "");
            }
            ?>
        </div>
    </div>
    <?php footer($gameName); ?>
</body>

</html>