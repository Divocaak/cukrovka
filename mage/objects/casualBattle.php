<?php
class CasualBattle
{
  public $id;
  public $start;
  public $end;
  public $winner;
  public $attacker;
  public $attack;
  public $defender;
  public $defense;

  function __construct(
    $id,
    $start,
    $end,
    $winner,
    $attacker,
    $attack,
    $defender,
    $defense
  ) {
    $this->id = $id;
    $this->start = $start;
    $this->end = $end;
    $this->winner = $winner;
    $this->attacker = $attacker;
    $this->attack = $attack;
    $this->defender = $defender;
    $this->defense = $defense;
  }

  function renderAttackRow()
  {
    echo '<tr>
    <td>' . $this->start . '</td>
    <td>' . $this->attacker->renderNameWithLevel() . '</td>
    <td>
    <form action="battles/casual/casual.php" method="post">
      <button type="submit" class="btn btn-outline-danger mx-2" name="battleId" value="' . $this->id . '">Defend</button>
    </td>
    </tr>';
  }
  
  function renderBattleLog()
  {
    echo '<tr data-battle-id="' . $this->id . '">
    <td>' . $this->start . '</td>
    <td>' . $this->defender->renderNameWithLevel() . '</td>
    <td>' . ($this->defense == "" ? "No response yet." : '<a class="btn btn-outline-info mx-2 showResults" data-battle-id="' . $this->id . '">Show results</a>') . '</td>
  </tr>';
  }
}
