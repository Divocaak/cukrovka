<?php
require_once "tree.php";

class Player
{
  public $id;
  public $username;
  public $level;
  public $level_name;

  function __construct($id, $username, $level, $level_name)
  {
    $this->id = $id;
    $this->username = $username;
    $this->level = $level;
    $this->level_name = $level_name;
  }

  function get_trees_all($link){
    $trees = [];
    $sql = 'SELECT t.id, t.points_spent, t.type_id, ty.name FROM trees t
      INNER JOIN types ty ON t.type_id=ty.id
      WHERE t.player_id=' . $this->id . ';';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $trees[] = new Tree($row[0], $row[1], $row[2], ucfirst($row[3]));
      }
      mysqli_free_result($result);
      return $trees;
    } else {
      return "ERROR";
    }
  }
  
  function get_remaining_points($link, $maxPoints){
    $remPoints = 0;
    $sql = 'SELECT SUM(points_spent) FROM trees WHERE player_id=' . $this->id . ';';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $remPoints = $maxPoints - $row[0];
      }
      mysqli_free_result($result);
      return $remPoints;
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
