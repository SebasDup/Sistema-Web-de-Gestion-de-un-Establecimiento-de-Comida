<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class MenuModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerMenu() {
        $stmt = $this->pdo->query("SELECT * FROM menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo usuario
    public function agregarMenu($nombre, $descripcion, $precio, $categoria) {
        $stmt = $this->pdo->prepare("INSERT INTO menu (nombre, descripcion, precio, categoria) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $descripcion, $precio, $categoria])) {
            $_SESSION['mensaje'] = "Platillo: ".$nombre.", Agregado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar el platillo: ".$nombre;
        }
    }

    // Método para actualizar un usuario existente
    public function actualizarMenu($id, $nombre, $descripcion, $precio, $categoria) {
        $nombreAntiguo = $nombre;
        $stmt = $this->pdo->prepare("UPDATE menu SET nombre = ?, descripcion = ?, precio = ?, categoria = ? WHERE id = ?");
        if ($stmt->execute([$nombre, $descripcion, $precio, $categoria, $id])) {
            $_SESSION['mensaje'] = "Platillo: ". $nombreAntiguo .", Actualizado exitosamente a ".$nombre.", descripcion: ".$descripcion.", precio: ".$precio ." categoria: ".$categoria;
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al actualizar el platillo Id: ".$id ." nombre: ".$nombre;
        }
    }

    // Método para eliminar un usuario
    public function eliminarMenu($id, $nombre) {
        $stmt = $this->pdo->prepare("DELETE FROM menu WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Platillo Id: " . $id ." ". $nombre ." , Eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al eliminar el platillo Id: ". $id ." nombre: ". $nombre;
        }
    }

    // Método para verificar si un platillo es nombre propio
    public function platilloEsPropio($id,$nombre) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM menu WHERE nombre = ? AND id = ?");
        $stmt->execute([$nombre, $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Método para verificar si un platillo ya existe
    public function platilloExiste($nombre) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM menu WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetchColumn() > 0;
    }
}
