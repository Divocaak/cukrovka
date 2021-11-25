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

  function render($cardGlossary, $btn){
      echo '<div class="col-3">
      <div class="card">
      <img src="..." class="card-img-top" alt="Obrázek pls">
      <div class="card-body">
        <h5 class="card-title">' . $this->name . '</h5>
        <p class="card-text">' . $this->get_param(true, $cardGlossary) . '</p>
        <a href="..." class="btn btn-primary' . ($btn ? "" : ' disabled') . '">Použít</a>
      </div>
    </div>
    </div>';
  }
}
?>