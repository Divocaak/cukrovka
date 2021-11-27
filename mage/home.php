<?php
session_start();
require_once "objects/player.php";
require_once "utils/config.php";
require_once "utils/elements.php";

$player = unserialize($_SESSION["player"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Domovská stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="m-5">
        <div class="row">
            <div class="col-6">
                <a class="btn btn-outline-danger" href="sign/logout.php"><i class="bi bi-box-arrow-left"></i></a>
            </div>
            <div class="col-6">
                <p>Přihlášen jako <b><?php echo $player->get_username(); ?></b></p>
            </div>
        </div>
        <h1 class="m-5"><?php echo $gameName;?></h1>
        <div class="row mt-5">
            <div class="col-4">
                <h2>Zápasy</h2>
                <a class="btn btn-danger" href="">Začít zápas</a>
                <h3 class="mt-3">Rozehrané</h3>
                <h3 class="mt-3">Historie</h3>
                <p>Výhry: <b><?php echo $player->get_wins(); ?></b>, Prohry: <b><?php echo $player->get_loses(); ?></b>, Win-rate: <b><?php echo $player->get_win_rate(); ?></b></p>
            </div>
            <div class="col-4">
                <h2>Balíčky</h2>
                <p>Vytvořeno <b><?php echo count($player->get_packs($link)); ?></b> balíčků.</p>
                <a class="btn btn-primary" href="packs/createPack.php"><i class="bi bi-plus-circle"></i><i class="bi bi-files"></i></a>
                <a class="btn btn-outline-primary" href="packs/showPacks.php"><i class="bi bi-eye"></i><i class="bi bi-files"></i></a>
            </div>
            <div class="col-4">
                <h2>Sbírka karet</h2>
                <p>Odemčeno <b><?php echo count($player->get_unlocked_cards($link)); ?></b> z XXX karet.</p>
                <a class="btn btn-outline-warning" href="collection.php"><i class="bi bi-eye"></i><i class="bi bi-file"></i></a>
            </div>
        </div>
    </div>

    <?php footer($gameName); ?>
</body>

</html>