<?php
class PromocionModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerPromociones() {
        $stmt = $this->pdo->query("SELECT * FROM promociones");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarPromocion($titulo, $descripcion, $descuento, $fecha_inicio, $fecha_fin) {
        $stmt = $this->pdo->prepare("INSERT INTO promociones (titulo, descripcion, descuento, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$titulo, $descripcion, $descuento, $fecha_inicio, $fecha_fin])) {
            $_SESSION['mensaje'] = "Promoción: ".$titulo.", Agregada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar la promoción: ".$titulo;
        }
    }

    public function actualizarPromocion($id, $titulo, $descripcion, $descuento, $fecha_inicio, $fecha_fin) {
        $stmt = $this->pdo->prepare("UPDATE promociones SET titulo = ?, descripcion = ?, descuento = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?");
        if ($stmt->execute([$titulo, $descripcion, $descuento, $fecha_inicio, $fecha_fin, $id])) {
            $_SESSION['mensaje'] = "Promoción: ".$titulo.", Actualizada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la promoción: ".$titulo;
        }
    }

    public function eliminarPromocion($id) {
        $stmt = $this->pdo->prepare("DELETE FROM promociones WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Promoción eliminada exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar la promoción";
        }
    }

    public function TituloRepetido($titulo){
        $stmt = $this->pdo->prepare("SELECT * FROM promociones WHERE titulo = ?");
        $stmt->execute([$titulo]);
        $promocion = $stmt->fetch(PDO::FETCH_ASSOC);
        if($promocion){
            return true;
        }else{
            return false;
        }
    }

    public function TituloEsPropio($titulo, $id){
        $stmt = $this->pdo->prepare("SELECT * FROM promociones WHERE titulo = ? AND id = ?");
        $stmt->execute([$titulo, $id]);
        $promocion = $stmt->fetch(PDO::FETCH_ASSOC);
        if($promocion){
            return true;
        }else{
            return false;
        }
    }
}
?>
