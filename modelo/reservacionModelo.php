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
                $_SESSION['error'] = "La mesa seleccionada no existe.";
            }
        } else {
            $_SESSION['error'] = "Error al registrar la reservación.";
        }
    }

    public function editarReservacion($reservacion_id, $personas, $fecha, $mesa_id) {
        $stmt = $this->pdo->prepare("UPDATE reservaciones SET personas = ?, fecha = ? WHERE id = ?");
        if ($stmt->execute([$personas, $fecha, $reservacion_id])) {
            $stmtMesaCheck = $this->pdo->prepare("SELECT COUNT(*) FROM mesas WHERE id = ?");
            $stmtMesaCheck->execute([$mesa_id]);
            if ($stmtMesaCheck->fetchColumn() > 0) {
                $stmtMesa = $this->pdo->prepare("UPDATE reservaciones_mesas SET mesa_id = ? WHERE reservacion_id = ?");
                if ($stmtMesa->execute([$mesa_id, $reservacion_id])) {
                    $_SESSION['mensaje'] = "Reservación editada exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al editar la mesa de la reservación.";
                }
            } else {
                $_SESSION['error'] = "La mesa seleccionada no existe.";
            }
        } else {
            $_SESSION['error'] = "Error al editar la reservación.";
        }
    }

    public function eliminarReservacion($reservacion_id){
        $stmt = $this->pdo->prepare("DELETE FROM reservaciones WHERE id = ?");
        if ($stmt->execute([$reservacion_id])) {
            $stmtMesa = $this->pdo->prepare("DELETE FROM reservaciones_mesas WHERE reservacion_id = ?");
            if ($stmtMesa->execute([$reservacion_id])) {
                $_SESSION['mensaje'] = "Reservación eliminada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al eliminar la mesa de la reservación.";
            }
        } else {
            $_SESSION['error'] = "Error al eliminar la reservación.";
        }
    }

    public function obtenerCapacidadMesa($id_mesa){
        $stmt = $this->pdo->prepare("SELECT capacidad FROM mesas WHERE id = ?");
        $stmt->execute([$id_mesa]);
        return $stmt->fetchColumn();
    }

    public function obtenerNombreMesa($id_mesa){
        $stmt = $this->pdo->prepare("SELECT numero FROM mesas WHERE id = ?");
        $stmt->execute([$id_mesa]);
        return $stmt->fetchColumn();
    }

    public function verificarReservacion($fecha, $mesa_id){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservaciones WHERE fecha = ? AND id IN (SELECT reservacion_id FROM reservaciones_mesas WHERE mesa_id = ?)");
        $stmt->execute([$fecha, $mesa_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function EsReservacionPropia($reservacion_id){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservaciones WHERE id = ?");
        $stmt->execute([$reservacion_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function obtenerHorario($diaSemana){
        $stmt = $this->pdo->prepare("SELECT * FROM horarios WHERE dia_semana = ?");
        $stmt->execute([$diaSemana]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
