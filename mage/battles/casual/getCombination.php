<?php
require_once "../../utils/config.php";
require_once "../../objects/element.php";
session_start();

//$_POST["element1"] = $_GET["element1"];
//$_POST["element2"] = $_GET["element2"];

$sql = "SELECT result FROM combinations WHERE
    (element_st=" . $_POST["element1"] . " AND element_nd=" . $_POST["element2"] . ")
    OR (element_st=" . $_POST["element2"] . " AND element_nd=" . $_POST["element1"] . ");";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $resultId = $row[0];
    }
    //$_SESSION["combination"] = ["el1" => $_POST["element1"], "el2" => $_POST["element2"], "result" => $resultId];
    $out = $_SESSION["allElements"][$_POST["element1"]]->renderCombinationRow($elementGlossary);
    $out .= $_SESSION["allElements"][$_POST["element2"]]->renderCombinationRow($elementGlossary);
    echo json_encode($out);
} else {
    echo json_encode("!Error: " . $sql . " - " . mysqli_error($link));
}
?>