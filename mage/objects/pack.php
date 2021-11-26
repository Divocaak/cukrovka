<?php
require_once "../config.php";
require_once "card.php";

class Pack
{
    public $id;
    public $name;
    public $cards;
    public $cardsFromDb;

    function __construct($id, $name, $cards, $cardsFromDb)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cards = $cards;
        $this->cardsFromDb = $cardsFromDb;
    }

    function get_cards_from_db($link)
    {
        if ($this->cardsFromDb) {
            return true;
        } else {
            $cardsToRet = [];
            $sql = 'SELECT id, name, params FROM cards WHERE id IN (' . $this->cards . ');';
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $cardsToRet[] = new Card($row[0], $row[1], $row[2]);
                }
                $this->cards = $cardsToRet;
                $this->cardsFromDb = true;
                return true;
                mysqli_free_result($result);
            } else {
                return false;
            }
        }
    }
}
?>