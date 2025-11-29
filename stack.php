<?php
// stack.php
// Simple OOP stack + helper untuk integrasi ke session

class ActionStack {
    protected $stack = [];

    public function __construct($initial = []) {
        $this->stack = is_array($initial) ? $initial : [];
    }

    // push data ke stack
    public function push($data) {
        $this->stack[] = $data;
    }

    // pop data dari stack (LIFO)
    public function pop() {
        if (empty($this->stack)) return null;
        return array_pop($this->stack);
    }

    // lihat seluruh stack (array)
    public function all() {
        return $this->stack;
    }

    public function isEmpty() {
        return empty($this->stack);
    }

    // sinkronisasi session (memudahkan)
    public function saveToSession($key = 'delete_stack') {
        $_SESSION[$key] = $this->stack;
    }

    public static function fromSession($key = 'delete_stack') {
        $arr = isset($_SESSION[$key]) && is_array($_SESSION[$key]) ? $_SESSION[$key] : [];
        return new self($arr);
    }
}

// Child class untuk fitur undo (inheritance -> OOP2)
class UndoStack extends ActionStack {
    public function saveDeleteAction($item) {
        $this->push($item);
    }

    public function getLastAction() {
        return $this->pop();
    }
}
?>
