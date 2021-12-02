<?php
class Element
{
  public $id;
  public $name;
  public $params;
  public $tier_id;
  public $tier_name;
  public $type_name;

  function __construct($id, $name, $params, $tier_id, $tier_name, $type_name)
  {
    $this->id = $id;
    $this->name = $name;
    $this->params = $params;
    $this->tier_id = $tier_id;
    $this->tier_name = $tier_name;
    $this->type_name = $type_name;
  }

  function get_param($translated, $elementGlossary)
  {
    $key = $this->params;
    return ($translated ? $elementGlossary[$key] : $key);
  }
  
  // TODO use later mby
  /* function renderCard($cardGlossary, $btn, $pathToRoot, $count)
  {
    echo '<div class="col-2">
    <div class="card">
    <img src="' . $pathToRoot . 'imgs/cardImgs/' . $this->id . '.png" class="card-img-top" alt="Tady by měl být obrázek">
    <div class="card-body">
      <h5 class="card-title">' . $this->name . '</h5>
      <p class="card-text">' . $this->get_param(false, $cardGlossary) . '</p>
      <a href="..." class="btn btn-primary' . ($btn ? "" : ' disabled') . '"><i class="bi bi-send"></i></a>
      ' . ($count != "" ? '<p class="mt-2 text-muted"> V balíčku <b class="text-dark">' . $count . '</b> kusů</p>' : "") . '
    </div>
  </div>
  </div>';
  } */

  function renderRow($elementGlossary, $unlocked, $rowIndex)
  {
    $beforeColor = ("table-" . ($unlocked ? "success" : "danger"));
    echo '<tr class="' . $beforeColor . '" data-before-color="' . $beforeColor . '" id="element' . $rowIndex . '">
        <th scope="row" style="width: 10%"><img src="../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" alt="Tady by měl být obrázek"></th>
        <td>' . $this->name . '</td>
        <td>' . $this->get_param(true, $elementGlossary) . '</td>
        <td><b>' . $this->tier_id . '</b>: ' . $this->tier_name . '</td>
      </tr>';
  }
}
