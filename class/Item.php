<?php
/* Kelas Item punya method pengecekan expire yang terpisah dari database.
Jadi perhitungan expire dilakukan OOP. */
date_default_timezone_set('Asia/Jakarta');

class Item {
    public $name;
    public $qty;
    public $exp; // format YYYY-MM-DD

    public function __construct($name, $qty, $exp) {
        $this->name = $name;
        $this->qty  = $qty;
        $this->exp  = $exp;
    }

    
    public function isExpired() {
        if (empty($this->exp)) return false;

        $today = new DateTime('today');
        try {
            $exp = new DateTime($this->exp);
        } catch (Exception $e) {
            // Kalau format tanggal invalid, anggap belum expired (atau bisa diganti sesuai kebutuhan)
            return false;
        }

        return $exp < $today;
    }
    /* “Fungsinya menghitung sisa hari sampai expired
     dan ngembaliin angka negatif kalau sudah lewat */
       public function daysLeft() {
        if (empty($this->exp)) return null;

        $today = new DateTime('today');
        try {
            $exp = new DateTime($this->exp);
        } catch (Exception $e) {
            return null;
        }

        // diff->days selalu non-negatif, jadi tentukan tanda berdasarkan perbandingan
        $diffDays = $today->diff($exp)->days;

        if ($exp < $today) {
            return -$diffDays; 
        } elseif ($exp == $today) {
            return 0;
        } else {
            return $diffDays; 
        }
    }
}
?>
