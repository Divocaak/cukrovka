<?php
session_start();
require_once "objects/player.php";
require_once "config.php";

$player = unserialize($_SESSION["player"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Domovská stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body class="m-5">
    <div class="row">
        <div class="col-6">
            <a class="btn btn-outline-danger" href="sign/logout.php">Odhlásit se</a>
        </div>
        <div class="col-6">
            <p>Přihlášen jako <b><?php echo $player->get_username(); ?></b></p>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-4">
            <h3>Zápasy</h3>
            <a class="btn btn-danger" href="">Začít zápas</a>
            <h4 class="mt-3">Historie</h4>
            <p>Výhry: <b><?php echo $player->get_wins(); ?></b>, Prohry: <b><?php echo $player->get_loses(); ?></b>, Win-rate: <b><?php echo $player->get_win_rate(); ?></b></p>
        </div>
        <div class="col-4">
            <h3>Balíčky</h3>
            <p>Vytvořeno <b><?php echo $player->get_packs_count($link, false); ?></b> balíčků.</p>
            <a class="btn btn-primary" href="packs/createPack.php">Vytvořit nový balíček</a>
            <a class="btn btn-outline-primary" href="packs/showPacks.php">Zobrazit balíčky</a>
        </div>
        <div class="col-4">
            <h3>Sbírka karet</h3>
            <p>Odemčeno <b><?php echo count($player->get_unlocked_cards_ids($link)); ?></b> z XXX karet.</p>
            <a class="btn btn-outline-warning" href="collection.php">Zobrazit karty</a>
        </div>
    </div>
</body>

</html>