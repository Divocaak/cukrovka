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
                <p>Logged as <b><?php echo $player->username; ?></b></p>
            </div>
        </div>
        <h1 class="m-5"><?php echo $gameName;?></h1>
        <div class="row mt-5">
            <div class="col-4">
                <h2>Battles</h2>
                <a class="btn btn-danger" href="">Begin new</a>
                <h3 class="mt-3">Active battles</h3>
                <h3 class="mt-3">History</h3>
                <p>Wins: <b><?php echo $player->get_wins(); ?></b>, Loses: <b><?php echo $player->get_loses(); ?></b>, Win-rate: <b><?php echo $player->get_win_rate(); ?></b></p>
            </div>
            <div class="col-4">
                <h2>Elements</h2>
                <a class="btn btn-danger" href="trees/showTree.php?type=0">Fire</a>
                <a class="btn btn-primary" href="trees/showTree.php?type=1">Water</a>
                <a class="btn btn-success" href="trees/showTree.php?type=2">Earth</a>
                <a class="btn btn-info" href="trees/showTree.php?type=3">Air</a>
            </div>
            <div class="col-4">
                <h2>třeba soc. centrum/shop/mage/clan/idk</h2>
            </div>
        </div>
    </div>

    <?php footer($gameName); ?>
</body>

</html>