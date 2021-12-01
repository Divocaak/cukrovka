<?php
require_once "element.php";
class Tree
{
    public $id;
    public $points_spent;
    public $type_id;
    public $type_name;

    function __construct($id, $points_spent, $type_id, $type_name)
    {
        $this->id = $id;
        $this->points_spent = $points_spent;
        $this->type_id = $type_id;
        $this->type_name = $type_name;
    }

    function get_elements_all($link)
    {
        $elements = null;
        $sql = 'SELECT e.id, e.params, n.name, e.tier_id, ti.name, ty.name 
            FROM ELEMENTS e INNER JOIN names n ON n.id=e.name_id 
            INNER JOIN tiers ti ON ti.id=e.tier_id 
            INNER JOIN types ty ON ty.id=e.type_id 
            WHERE e.type_id=' . $this->type_id . ';';
        if ($result = mysqli_query($link, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                $elements[] = new Element($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
            }
            mysqli_free_result($result);
            return $elements;
        } else {
            return "ERROR";
        }
    }
}
