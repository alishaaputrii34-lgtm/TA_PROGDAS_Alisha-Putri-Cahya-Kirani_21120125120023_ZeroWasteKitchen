    <?php
require_once "Item.php";

class FoodItem extends Item {
    public $category;
    public $unit;

    public function __construct($name, $qty, $exp, $category, $unit) {
        parent::__construct($name, $qty, $exp);
        $this->category = $category;
        $this->unit = $unit;
    }
}
?>
