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
    $this->params = json_decode($params, true);
    $this->tier_id = $tier_id;
    $this->tier_name = $tier_name;
    $this->type_name = $type_name;
  }

  function get_param($elementGlossary)
  {
    $ret = "";
    foreach($this->params as $key => $value){
      $ret .= $elementGlossary[$key] . " (" . $value . ") ";
    }
    return $ret;
  }

  function renderRow($elementGlossary, $unlocked, $rowIndex)
  {
    $beforeColor = ("table-" . ($unlocked ? "success" : "danger"));
    echo '<tr class="' . $beforeColor . '" data-before-color="' . $beforeColor . '" id="element' . $rowIndex . '">
        <th scope="row" style="width: 10%"><img src="../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" alt="Tady by měl být obrázek"></th>
        <td>' . $this->name . '</td>
        <td>' . $this->get_param($elementGlossary) . '</td>
        <td><b>' . $this->tier_id . '</b>: ' . $this->tier_name . '</td>
      </tr>';
  }

  function renderHandRow($elementGlossary, $key)
  {
    echo '<tr data-element-id="' . $this->id . '" data-hand-key="' . $key . '">
        <th scope="row" style="width: 10%"><img src="../../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" style="width:40%" alt="Tady by měl být obrázek"></th>
        <td>' . $key . ": " . $this->name . '</td>
        <td>' . $this->type_name . '</td>
        <td>' . $this->tier_id . ', ' . $this->tier_name . '</td>
        <td>' . $this->get_param($elementGlossary) . '</td>
        <td><div class="col-6 d-flex align-items-center justify-content-center">
          <a class="btn btn-info mx-2 mergeBtn">Merge</a>
          <a class="btn btn-success mx-2 useBtn">Use</a>
          </div>
        </td>
      </tr>';
  }

  function renderCombinationRow($elementGlossary, $key, $htmlId)
  {
    return '<tr id="' . $htmlId . '" data-element-id="' . $this->id . '" data-hand-key="' . $key . '">
      <th scope="row" style="width: 10%"><img src="../../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" style="width:40%" alt="Tady by měl být obrázek"></th>
      <td>' . $this->name . '</td>
      <td>' . $this->type_name . '</td>
      <td>' . $this->tier_id . ', ' . $this->tier_name . '</td>
      <td>' . $this->get_param($elementGlossary) . '</td>
      </tr>';
  }

  function renderAttackRow($elementGlossary){
    return '<tr>
        <th scope="row" style="width: 10%"><img src="../../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" style="width:40%" alt="Tady by měl být obrázek"></th>
        <td>' . $this->name . '</td>
        <td>' . $this->type_name . '</td>
        <td>' . $this->tier_id . ', ' . $this->tier_name . '</td>
        <td>' . $this->get_param($elementGlossary) . '</td>
      </tr>';
  }
}
