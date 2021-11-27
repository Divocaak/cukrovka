<?php
require_once "card.php";

class Pack
{
    public $id;
    public $name;
    public $cards;

    function __construct($id, $name, $cards)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cards = $cards;
    }

    function get_cards($link)
    {
        if(is_string($this->cards)){
            $cardsCounted = [];
            foreach(explode(",", $this->cards) as $cardId){
                $cardsCounted[$cardId]++;
            }
            
            $cardsToRet = [];
            $sql = 'SELECT id, name, params FROM cards WHERE id IN (' . $this->cards . ');';
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $cardsToRet = array_merge($cardsToRet, array_fill(0, $cardsCounted[$row[0]], new Card($row[0], $row[1], $row[2])));
                }
                $this->cards = $cardsToRet;
                mysqli_free_result($result);
            } else {
                return "ERROR";
            }
        }

        return $this->cards;
    }
}
