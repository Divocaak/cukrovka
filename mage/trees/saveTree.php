<?php
require_once "../utils/config.php";

$sql = "UPDATE trees SET points_spent=" . $_POST["spentPoints"] . " WHERE id=" . $_POST["id"] . ";";
if (!mysqli_query($link, $sql)) {
    echo json_encode("Error: " . $sql . " - " . mysqli_error($link));
} else {
    echo json_encode("Saved");
}
?>