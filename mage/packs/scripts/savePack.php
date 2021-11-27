<?php
require_once "../../utils/config.php";

$sql = 'SELECT name FROM packs WHERE name="' . $_POST["name"] . '" AND player_id=' . $_POST["player_id"] . ';';
if (mysqli_num_rows(mysqli_query($link, $sql)) > 0) {
    echo json_encode("Balíček se stejným jménem již existuje");
} else {
    $sql = 'INSERT INTO packs (name, cards, player_id) VALUES ("' . $_POST["name"] . '", "' . $_POST["cards"] . '", ' . $_POST["player_id"] . ');';
    if (!mysqli_query($link, $sql)) {
        echo json_encode("Error: " . $sql . " - " . mysqli_error($link));
    } else {
        echo json_encode("Balíček uložen");
    }
}
?>