<?php
require_once "../../utils/config.php";
require_once "../../objects/element.php";
session_start();

$sql = "SELECT e.id, e.name, e.params, e.tier_id, ti.name, ty.name 
    FROM ELEMENTS e INNER JOIN tiers ti ON ti.id=e.tier_id 
    INNER JOIN types ty ON ty.id=e.type_id 
    WHERE e.id=(" . $_POST["combinationId"] . ");";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $_SESSION["handElements"][] = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }

    unset($_SESSION["handElements"][$_POST["key1"]]);
    unset($_SESSION["handElements"][$_POST["key2"]]);
}
?>