<?php
class Element
{
  public $id;
  public $params;
  public $name;
  public $tier_id;
  public $tier_name;
  public $type_name;

  function __construct($id, $params, $name, $tier_id, $tier_name, $type_name)
  {
    $this->id = $id;
    $this->params = $params;
    $this->name = $name;
    $this->tier_id = $tier_id;
    $this->tier_name = $tier_name;
    $this->type_name = $type_name;
  }

  // TODO use later
  /* function get_param($translated, $cardGlossary)
  {
    $key = $this->param;
    return ($translated ? $cardGlossary[$key] : $key);
  }

  function renderCard($cardGlossary, $btn, $pathToRoot, $count)
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
  }

  function renderRow($cardGlossary, $maxCardsPerPack)
  {
    echo '<tr>
        <th scope="row" style="width: 10%"><img src="../imgs/cardImgs/' . $this->id . '.png" class="img-thumbnail" alt="Tady by měl být obrázek"></th>
        <td>' . $this->name . '</td>
        <td>' . $this->get_param(true, $cardGlossary) . '</td>
        <td>
          <div class="d-flex flex-row" data-card-id="' . $this->id . '">
            <a class="cardMinusBtn btn btn-outline-danger"><i class="bi bi-dash-circle"></i></a>
            <p class="px-3" id="cardCount' . $this->id . '" data-card-count="0"><b>0</b>/' . $maxCardsPerPack . '</p>
            <a class="cardPlusBtn btn btn-outline-success"><i class="bi bi-plus-circle"></i></a>
          </div>
        </td>
      </tr>';
  } */
}
