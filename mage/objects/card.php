<?php
class Card{
    public $id;
    public $name;
    public $param;

    function __construct($id, $name, $param)
  {
    $this->id = $id;
    $this->name = $name;
    $this->param = $param;
  }

  function get_name(){
      return $this->name;
  }
  function get_param($translated, $cardGlossary){
      $key = $this->param;
      return ($translated ? $cardGlossary[$key] : $key);
  }

  function renderCard($cardGlossary, $btn, $pathToRoot){
    // TODO předělat na obrázek podle id
      echo '<div class="col-3">
      <div class="card">
      <img src="' . $pathToRoot . 'imgs/cardImgs/1.png" class="card-img-top" alt="Tady by měl být obrázek">
      <div class="card-body">
        <h5 class="card-title">' . $this->name . '</h5>
        <p class="card-text">' . $this->get_param(false, $cardGlossary) . '</p>
        <a href="..." class="btn btn-primary' . ($btn ? "" : ' disabled') . '"><i class="bi bi-send"></i></a>
      </div>
    </div>
    </div>';
  }

  function renderRow($cardGlossary){
    // TODO předělat na obrázek podle id
    echo '<tr>
          <th scope="row" style="width: 10%"><img src="../imgs/cardImgs/1.png" class="img-thumbnail" alt="Tady by měl být obrázek"></th>
          <td>' . $this->name . '</td>
          <td>' . $this->get_param(true, $cardGlossary) . '</td>
          <td>
            <div class="d-flex flex-row" data-card-id="' . $this->id . '">
              <a class="cardMinusBtn btn btn-outline-danger"><i class="bi bi-dash-circle"></i></a>
              <p class="px-3" id="cardCount' . $this->id . '" data-card-count="0"><b>0</b>/2</p>
              <a class="cardPlusBtn btn btn-outline-success"><i class="bi bi-plus-circle"></i></a>
            </div>
          </td>
        </tr>';
  }
}
