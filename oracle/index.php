<?php
require_once "qs.php";
session_start();

if(isset($_POST['submit'])){
    if(isset($_POST["radios"])){
        echo ($_POST["radios"] == $_SESSION["correct"][0] ?
            '<i class="bi bi-check-circle-fill text-success"></i><p>Správně! (' . $_SESSION["answer"] . ')</p>' :
            '<i class="bi bi-x-circle-fill text-danger"></i><p>Správná odpověď: ' . $_SESSION["answer"] . '</p>');
    } else if(isset($_POST["1"]) || isset($_POST["2"]) || isset($_POST["3"]) || isset($_POST["4"])){
        $isRight = true;
        foreach($_SESSION["correct"] as $answer){
            if(!isset($_POST[strval($answer)])){
                $isRight = false;
                break;
            }
        }

        echo ($isRight ?
            '<i class="bi bi-check-circle-fill text-success"></i><p>Správně! (' . $_SESSION["answer"] . ')</p>' :
            '<i class="bi bi-x-circle-fill text-danger"></i><p>Správná odpověď: ' . $_SESSION["answer"] . '</p>');
    }
   session_unset();
}

$questions = explode("§&newLine;", $qs);
$realQs = [];
for($i = 0; $i < count($questions); $i++){
    $curr = explode("&newLine;", $questions[$i]);
    $question = $curr[0];
    unset($curr[0]);

    $curr[count($curr)] = "rEmOvE_mE";
    $curr = array_diff($curr, ["", null, "rEmOvE_mE"]);

    $realQs[] = ["question" => $question, "answers" => $curr];
}


function newQuestion($questions){
    $qId = rand(0, count($questions) - 1);
    $q = $questions[$qId];
    
    $_SESSION["correct"] = [];
    for($i = 1; $i < count($q["answers"]) + 1; $i++){
        if(substr($q["answers"][$i], 1, 1) == "*"){
            $_SESSION["correct"][] = $i;
            $_SESSION["answer"] .= (returnText($q["answers"][$i]) . " ");
        }
    }
    
    $toRet = '<form action="index.php" method="post"><h1 class="text-center">' . strval($qId + 1) . '. ' . $q["question"] . '</h1>';
    
    for($i = 1; $i < count($q["answers"]) + 1; $i++){
        $toRet .= (count($_SESSION["correct"]) > 1 ? '<div class="form-check">
        <input class="form-check-input" type="checkbox" name="' . $i . '" value="' . $i . '" id="defaultCheck1">
        <label class="form-check-label" for="defaultCheck1">' . returnText($q["answers"][$i]) . '</label></div>' : '<div class="form-check">
      <input class="form-check-input" type="radio" name="radios" id="exampleRadios1" value="' . $i . '">
      <label class="form-check-label" for="exampleRadios1">' . returnText($q["answers"][$i]) . '</label></div>');
    }

    $toRet .= '<button type="submit" class="btn btn-primary" name="submit">Zkontrolovat</button></form>';

  echo $toRet;
}

function returnText($text){
    return substr($text, 1, 1) == "*" ? substr($text, 2, strlen($text)): $text; 
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="utf-8">
  <meta name="viewport"
     content="width=device-width, initial-scale=1, user-scalable=yes">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>
<body>
<?php
    newQuestion($realQs);
?>
<p class="text-center">
<br>
&nbsp;&nbsp;&nbsp;___,_<br>
@/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;oo_<br>
(&nbsp;&nbsp;)___(,_@,<br>
&nbsp;&nbsp;}/&nbsp;&nbsp;&nbsp;`}\<br>
&nbsp;&nbsp;""&nbsp;&nbsp;&nbsp;&nbsp;""<br>
Divočák
</p>
</body>
</html>