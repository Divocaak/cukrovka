<?php
session_start();
require_once "../objects/card.php";
require_once "../objects/player.php";
require_once "../config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balíčky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body class="m-5">
<a class="btn btn-outline-secondary" href="../home.php">Zpět</a>
<div class="row mt-5">
    <?php
        foreach(unserialize($_SESSION["player"])->get_unlocked_cards_ids($link) as $card){
            echo $card . "<br>";
        }
    ?>
</div>
</body>

</html>