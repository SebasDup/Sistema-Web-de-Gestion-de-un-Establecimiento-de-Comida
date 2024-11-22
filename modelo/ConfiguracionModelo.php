<?php
class ConfiguracionModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerHorario() {
        $stmt = $this->pdo->query("SELECT * FROM horarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarHorario($dia, $apertura, $cierre, $estado) {
        $stmt = $this->pdo->prepare("UPDATE horarios SET hora_apertura = ?, hora_cierre = ?, estado = ? WHERE dia_semana = ?");
        if ($stmt->execute([$apertura, $cierre, $estado, $dia])) {
            $_SESSION['mensaje'] = "Horario actualizado exitosamente.";
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al actualizar horario, comuníquese con el administrador";
        }
    }
}
?>
