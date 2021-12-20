<?php
require_once "../../utils/config.php";
require_once "../../objects/element.php";
session_start();

$parts;
foreach ($_SESSION["handElements"] as $key => $element) {
    if ($key == $_POST["key1"]) {
        $parts .= $_SESSION["handElements"][$_POST["key1"]]->renderCombinationRow($elementGlossary, $_POST["key1"], "comb1");
    }
    if ($key == $_POST["key2"]) {
        $parts .= $_SESSION["handElements"][$_POST["key2"]]->renderCombinationRow($elementGlossary, $_POST["key2"], "comb2");
    }
}

$sql = "SELECT e.id, e.name, e.params, e.tier_id, ti.name, ty.name 
    FROM ELEMENTS e INNER JOIN tiers ti ON ti.id=e.tier_id 
    INNER JOIN types ty ON ty.id=e.type_id 
    WHERE e.id=(SELECT result FROM combinations WHERE
    (element_st=" . $_POST["id1"] . " AND element_nd=" . $_POST["id2"] . ")
    OR (element_st=" . $_POST["id2"] . " AND element_nd=" . $_POST["id1"] . "));";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $combination = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }

    $resHead = '<tr class="table-dark" id="mergePreview" data-result-id="' . ($combination->id != null ? $combination->id : 0) . '"><td colspan="5">Result:</td></tr>';
    if ($combination != null) {
        $comb .= $combination->renderCombinationRow($elementGlossary, "", "");
    } else {
        $comb .= '<tr class="table-danger"><td colspan="5">Cannot combine</td></tr>';
    }
} else {
    $out = 'ERROR';
}
echo json_encode($parts . $resHead . $comb . $out);
