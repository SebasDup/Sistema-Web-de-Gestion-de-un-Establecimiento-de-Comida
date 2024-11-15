<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class MesaModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerMesas() {
        $stmt = $this->pdo->query("SELECT * FROM mesas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMesasEmpelado($zonaAsignada) {
        $stmt = $this->pdo->prepare("SELECT * FROM mesas WHERE zona_id = ?");
        $stmt->execute([$zonaAsignada]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerZonas() {
        $stmt = $this->pdo->query("SELECT * FROM zonas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarMesa($numero, $capacidad, $estado, $zona_id) {
        $stmt = $this->pdo->prepare("INSERT INTO mesas (numero, capacidad, estado, zona_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$numero, $capacidad, $estado, $zona_id])) {
            $_SESSION['mensaje'] = "Mesa número: ".$numero.", Agregada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar la mesa número: ".$numero;
        }
    }

    public function agregarZona($zona) {
        $stmt = $this->pdo->prepare("INSERT INTO zonas (nombre) VALUES (?)");
        if ($stmt->execute([$zona])) {
            $_SESSION['mensaje'] = "Zona ".$zona.", Agregada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar la Zona : ".$zona;
        }
    }

    public function actualizarMesa($id, $numero, $capacidad, $zona_id){
        $stmt = $this->pdo->prepare("UPDATE mesas SET numero = ?, capacidad = ?, zona_id = ? WHERE id = ?");
        if ($stmt->execute([$numero, $capacidad, $zona_id, $id])) {
            $_SESSION['mensaje'] = "Mesa número: ".$numero.", Actualizada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la mesa número: ".$numero;
        }
    }

    public function eliminarMesa($id, $numero) {
        $stmt = $this->pdo->prepare("DELETE FROM mesas WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Mesa número: ".$numero.", Eliminada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la mesa número: ".$numero;
        }
    }

    public function mesaExiste($numero) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mesas WHERE numero = ?");
        $stmt->execute([$numero]);
        return $stmt->fetchColumn() > 0;
    }

    //Funcion para comprobar si una mesa esta disponible o no
    public function mesaDisponible($id, $numero) {
        $stmt = $this->pdo->prepare("SELECT id FROM mesas WHERE estado = 'disponible' AND id = ? AND numero = ?");
        $stmt->execute([$id, $numero]);
        return $stmt->fetchColumn() > 0;
    }

    public function zonaExiste($zona) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM zonas WHERE nombre = ?");
        $stmt->execute([$zona]);
        return $stmt->fetchColumn() > 0;
    }

    // Método para verificar si la mesa es la misma
    public function mesaEsPropia($id,$numero) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mesas WHERE numero = ? AND id = ?");
        $stmt->execute([$numero, $id]);
        return $stmt->fetchColumn() > 0;
    }

    public function actualizarEstado($estado, $id, $numero) {
        $stmt = $this->pdo->prepare("UPDATE mesas SET estado = ? WHERE id = ?");
        if ($stmt->execute([$estado, $id])) {
            $_SESSION['mensaje'] = "Mesa ".$numero.", Actualizada exitosamente a ".$estado;
        } else {
            $_SESSION['error'] = "Error al actualizar la mesa: ".$numero;
        }
    }

    public function eliminarZona($id, $nombre){
        if (isset($_GET['id'])) {
            $stmt = $this->pdo->prepare("DELETE FROM zonas WHERE id = ?");
            if ($stmt->execute([$_GET['id']])) {
                $_SESSION['mensaje'] = "Zona ". $nombre ." eliminada exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar la Zona ".$nombre;
            }
        } else {
            $_SESSION['error'] = "Error al eliminar la Zona ";
        }
    }

    public function zonaLibre($id){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM mesas WHERE zona_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function actualizarZona($id, $nombre){
        $stmt = $this->pdo->prepare("UPDATE zonas SET nombre = ? WHERE id = ?");
        if ($stmt->execute([$nombre, $id])) {
            $_SESSION['mensaje'] = "Zona ". $nombre .", Actualizada exitosamente";
        } else {
            $_SESSION['error'] = "Error al actualizar la Zona ".$nombre;
        }
    }

    public function zonaEsPropia($id, $nombre){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM zonas WHERE nombre = ? AND id = ?");
        $stmt->execute([$nombre, $id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>