<?php
class ReservacionModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerReservaciones() {
        $stmt = $this->pdo->query("SELECT * FROM reservaciones");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
