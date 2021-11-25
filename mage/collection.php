<?php
session_start();
require_once "objects/player.php";
require_once "objects/card.php";
require_once "config.php";
require_once "glossary.php";

$unlockedIds = "";
$sql = 'SELECT unlocked_cards FROM players WHERE id=' . unserialize($_SESSION["player"])->id . ';';
if ($result = mysqli_query($link, $sql)) {
    $unlockedIds = mysqli_fetch_row($result)[0];
} else {
    echo "ERROR";
}

if($unlockedIds != ""){
    $unlockedCards = [];
    $sql = 'SELECT id, name, params FROM cards WHERE id IN (' . $unlockedIds . ');';
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $unlockedCards[] = new Card($row[0], $row[1], $row[2]);
        }
        mysqli_free_result($result);
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Karty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

</head>

<body class="m-5">
<a class="btn btn-outline-secondary" href="home.php">Zpět</a>
<div class="row mt-5">
    <?php
        foreach($unlockedCards as $card){
            $card->render($cardGlossary, false);
        }
    ?>
</div>
</body>

</html>