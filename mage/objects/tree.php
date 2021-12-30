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
        $sql = 'SELECT e.id, e.name, e.params, e.tier_id, ti.name, ty.name 
            FROM ELEMENTS e INNER JOIN tiers ti ON ti.id=e.tier_id 
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

    function render_elements_simplified($link)
    {
        // TODO show detail on click
        $elements = $this->get_elements_all($link);
        $output = '<tr>
        <th scope="row" style="width: 10%">' . $this->type_name . '</th>';
        for ($i = 1; $i < count($elements) + 1; $i++) {
            $output .= '<td class="table-' . ($i <= $this->points_spent ? "success" : "danger") . '"><div class="row">
            <div class="col-12"><img src="../../imgs/cardImgs/' . $elements[$i - 1]->id . '.png" class="img-thumbnail" style="width:10%" alt="Tady by měl být obrázek"></div>
            <div class="col-12"><a href="#" class="btn btn-outline-secondary">Detail</a></div>
            </div>
            </td>';
        }

        echo $output . '</tr>';
    }
}
