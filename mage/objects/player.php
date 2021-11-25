<?php
class Player
{
  public $id;
  public $username;
  public $packs;

  function __construct($id, $username)
  {
    $this->id = $id;
    $this->username = $username;
  }

  function get_username()
  {
    return $this->username;
  }

  // TODO dostat z toho Card(), ne jenom IDčka
  function get_unlocked_cards_ids($link)
  {
    $sql = 'SELECT unlocked_cards FROM players WHERE id=' . $this->id . ';';
    if ($result = mysqli_query($link, $sql)) {
      while ($row = mysqli_fetch_row($result)) {
        return explode(",", $row[0]);
      }
      mysqli_free_result($result);
      mysqli_close($link);
    } else {
      return "ERROR";
    }
  }

  function get_packs_count($link, $refresh)
  {
    if($refresh || !isset($this->packs)){
      $sql = 'SELECT COUNT(player_id) FROM packs WHERE player_id=' . $this->id . ';';
      if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
          $this->packs = $row[0];
          return $row[0];
        }
        mysqli_free_result($result);
        mysqli_close($link);
      } else {
        return "ERROR";
      }
    }
    return $this->packs;
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
