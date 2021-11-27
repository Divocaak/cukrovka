<?php
require_once "../../utils/config.php";

$sql = "DELETE FROM packs WHERE id=" . $_POST["id"] . ";";
if (mysqli_query($link, $sql)) {
    echo json_encode("Balíček odstraněn");
} else {
    echo json_encode("Někde se stala chyba");
}
?>