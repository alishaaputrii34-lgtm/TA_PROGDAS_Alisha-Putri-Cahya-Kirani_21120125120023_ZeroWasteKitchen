<?php
class Item {
    public $name;
    public $qty;
    public $exp;

    public function __construct($name, $qty, $exp) {
        $this->name = $name;
        $this->qty = $qty;
        $this->exp = $exp;
    }

    public function isExpired() {
        if (empty($this->exp)) return false;
        return strtotime($this->exp) < time();
    }

    public function daysLeft() {
        if (empty($this->exp)) return null;
        return floor((strtotime($this->exp) - time()) / 86400);
    }
}
?>
