<?php
require_once "tree.php";

class Player
{
  public $id;
  public $username;

  function __construct($id, $username)
  {
    $this->id = $id;
    $this->username = $username;
  }

  function get_tree($link, $type_id){
    $tree = null;
    $sql = 'SELECT t.id, t.points_spent, ty.name FROM trees t
      INNER JOIN types ty ON t.type_id=ty.id
      WHERE t.type_id=' . $type_id . ' AND t.player_id=' . $this->id . ';';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $tree = new Tree($row[0], $row[1], $type_id, $row[2], $this->id);
      }
      mysqli_free_result($result);
      return $tree;
    } else {
      return "ERROR";
    }
  }

  function get_wins()
  {
    // TODO předělat :D
    return "6";
  }
  function get_loses()
  {
    // TODO předělat :D
    return "4";
  }
  function get_win_rate()
  {
    // TODO předělat :D
    return "60 %";
  }
}
