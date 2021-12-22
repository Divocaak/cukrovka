<?php
require_once "tree.php";
require_once "casualBattle.php";

class Player
{
  public $id;
  public $username;
  public $level;
  public $level_name;
  public $wins;
  public $loses;

  function __construct($id, $username, $level, $level_name)
  {
    $this->id = $id;
    $this->username = $username;
    $this->level = $level;
    $this->level_name = $level_name;
  }

  function renderNameWithLevel(){
    return "<b>" . $this->username . " (" . $this->level . ": <i>" . $this->level_name . "</i>)</b>";
  }

  // WAITING otestovat jestli někde potřebuju player_id
  function get_trees_all($link/* , $player_id = null */)
  {
    $trees = [];
    $sql = 'SELECT t.id, t.points_spent, t.type_id, ty.name FROM trees t
      INNER JOIN types ty ON t.type_id=ty.id
      WHERE t.player_id=' . /* ($player_id != null ? $player_id : $this->id) */ $this->id . ';';
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

  function get_remaining_points($link, $maxPoints)
  {
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

  function get_wins($link)
  {
    $wins = 0;
    $sql = 'SELECT COUNT(winner_id) FROM casual_games WHERE end IS NOT NULL AND winner_id=' . $this->id . ';';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $wins = $row[0];
      }
      mysqli_free_result($result);
      $this->wins = $wins;
      return $wins;
    } else {
      return "ERROR";
    }
  }

  function get_loses($link)
  {
    $loses = 0;
    $sql = 'SELECT COUNT(id) FROM casual_games WHERE end IS NOT NULL AND winner_id !=' . $this->id . '  AND (attacker_id=' . $this->id . ' OR defender_id=' . $this->id . ');';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $loses = $row[0];
      }
      mysqli_free_result($result);
      $this->loses = $loses;
      return $loses;
    } else {
      return "ERROR";
    }
  }

  function get_win_rate()
  {
    if ($this->wins + $this->loses > 0) {
      return (($this->loses / $this->wins) * 100) . "%";
    } else {
      return "not enough battles";
    }
  }

  function get_casual_battle($link, $asLog, $id = null)
  {
    $incomingAttacks = [];
    $sql = "SELECT id, start, end, winner_id, attacker_id, attack, defender_id, defense, attacker_seen FROM casual_games
      WHERE ";
    $logConditions = "attacker_id=" . $this->id . " AND attacker_seen IS NULL";
    $incomingConditions = "defender_id=" . $this->id . " AND defense IS NULL";
    $idd = "id=" . $id;
    $sql .= ($id != null ? $idd : (($asLog ? $logConditions : $incomingConditions) . " ORDER BY start;"));
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        $incomingAttacks[] = new CasualBattle($row[0], $row[1], $row[2], get_player_by_id($row[3], $link), get_player_by_id($row[4], $link), $row[5], get_player_by_id($row[6], $link), $row[7], $row[8]);
      }
      mysqli_free_result($result);
      return $incomingAttacks;
    } else {
      return "ERROR";
    }
  }
}

function get_player_by_id($id, $link)
{
    $sql = "SELECT p.id, p.username, p.level, l.name FROM players p INNER JOIN levels l ON l.id=p.level WHERE p.id=" . $id . ";";
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        return new Player($row[0], $row[1], $row[2], $row[3]);
      }
      mysqli_free_result($result);
    } else {
      return null;
    }
}
