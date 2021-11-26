<?php
session_start();
require_once "../objects/card.php";
require_once "../objects/pack.php";
require_once "../objects/player.php";
require_once "../config.php";

$packs = [];
$sql = 'SELECT id, name, cards FROM packs WHERE player_id=' . unserialize($_SESSION["player"])->id . ';';
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $packs[] = new Pack($row[0], $row[1], $row[2], false);
    }
    mysqli_free_result($result);
} else {
    echo "ERROR";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balíčky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
</head>

<body class="m-5">
    <a class="btn btn-outline-secondary" href="../home.php"><i class="bi bi-arrow-left"></i></a>
    <div class="mt-5">
        <h1>Moje balíčky</h1>
        <div class="row">
            <?php
            foreach ($packs as $pack) {
                echo '<div class="col-12 mt-5"><div class="row">';
                // TODO implementovat delete 
                echo '<div class="col-1"><a class="btn btn-outline-danger" href=""><i class="bi bi-trash"></i></a></div>';
                echo '<div class="col-11"><h2>' . $pack->name . ": " . '</h2></div>';
                echo '<div class="col-12"><div class="row">';
                if ($pack->get_cards_from_db($link)) {
                    foreach($pack->cards as $card){
                        $card->renderCard($cardGlossary, false, "../");
                    }
                } else {
                    echo "<p class='text-muted'>U balíčku '" . $pack->name . "' se něco nepovedlo.</p>";
                }

                echo '</div></div></div></div>';
            }
            ?>
        </div>
</body>

</html>