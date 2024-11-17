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

    public function obtenerReservacionesMesas() {
        $stmt = $this->pdo->query("SELECT * FROM reservaciones_mesas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarios() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios where tipo = 'cliente'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMesas() {
        $stmt = $this->pdo->query("SELECT * FROM mesas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarReservacion($cliente_id, $fechaHora, $personas, $estado, $comentarios, $mesa_id) {
        $stmt = $this->pdo->prepare("INSERT INTO reservaciones (cliente_id, fecha, personas, estado, comentarios) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$cliente_id, $fechaHora, $personas, $estado, $comentarios])) {
            $reservacion_id = $this->pdo->lastInsertId();
            $stmtMesaCheck = $this->pdo->prepare("SELECT COUNT(*) FROM mesas WHERE id = ?");
            $stmtMesaCheck->execute([$mesa_id]);
            if ($stmtMesaCheck->fetchColumn() > 0) {
                $stmtMesa = $this->pdo->prepare("INSERT INTO reservaciones_mesas (reservacion_id, mesa_id) VALUES (?, ?)");
                if ($stmtMesa->execute([$reservacion_id, $mesa_id])) {
                    $_SESSION['mensaje'] = "Reservación registrada exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al registrar la mesa de la reservación.";
                }
            } else {
                $_SESSION['error'] = "La mesa seleccionada no existe. ".$mesa_id;
            }
        } else {
            $_SESSION['error'] = "Error al registrar la reservación.";
        }
    }
}
?>
