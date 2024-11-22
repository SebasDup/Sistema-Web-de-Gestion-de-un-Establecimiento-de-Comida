<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ComandaModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerComandas() {
        $stmt = $this->pdo->query("SELECT * FROM comandas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMesas() {
        $stmt = $this->pdo->query("SELECT * FROM mesas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatillos() {
        $stmt = $this->pdo->query("SELECT * FROM menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerClientes() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPlatillosComanda() {
        $stmt = $this->pdo->query("SELECT * FROM items_comanda");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarComanda($cliente_id, $platillos_id, $totalComanda, $mesa_id, $comentarios, $estado, $cantidades, $precios, $id_Empleado) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO comandas (mesa_id, cliente_id, estado, total, comentarios, empleado_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$mesa_id, $cliente_id, $estado, $totalComanda, $comentarios, $id_Empleado]);
            $comanda_id = $this->pdo->lastInsertId();

            $platillos_array = explode(',', $platillos_id);
            $cantidades_array = explode(',', $cantidades);
            $precios_array = explode(',', $precios);

            $stmt = $this->pdo->prepare("INSERT INTO items_comanda (comanda_id, menu_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            for ($i = 0; $i < count($platillos_array); $i++) {
                $stmt->execute([$comanda_id, $platillos_array[$i], $cantidades_array[$i], $precios_array[$i]]);
            }

            $this->pdo->commit();
            $_SESSION['mensaje'] = "Comanda registrada exitosamente.";
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = "Error al registrar la comanda: " . $e->getMessage();
            return false;
        }
    }
    
    public function editarComanda($comanda_id, $platillos_id, $totalComanda, $mesa_id, $comentarios, $estado, $cantidades, $precios) {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("SELECT id FROM comandas WHERE id = ?");
            $stmt->execute([$comanda_id]);
            if (!$stmt->fetch()) {
                throw new PDOException("La comanda no existe");
            }

            $platillos_array = explode(',', $platillos_id);
            $cantidades_array = explode(',', $cantidades);
            $precios_array = explode(',', $precios);

            $stmt = $this->pdo->prepare("UPDATE comandas SET mesa_id = ?, total = ?, comentarios = ?, estado = ? WHERE id = ?");
            $stmt->execute([$mesa_id, $totalComanda, $comentarios, $estado, $comanda_id]);

            $stmt = $this->pdo->prepare("DELETE FROM items_comanda WHERE comanda_id = ?");
            $stmt->execute([$comanda_id]);

            $stmt = $this->pdo->prepare("INSERT INTO items_comanda (comanda_id, menu_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            for ($i = 0; $i < count($platillos_array); $i++) {
                $stmt->execute([$comanda_id, $platillos_array[$i], $cantidades_array[$i], $precios_array[$i]]);
            }

            $this->pdo->commit();
            $_SESSION['mensaje'] = "Comanda actualizada exitosamente.";
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = "Error al actualizar la comanda: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarComanda($id){
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("SELECT id FROM comandas WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                throw new PDOException("La comanda no existe");
            }
            $stmt = $this->pdo->prepare("DELETE FROM items_comanda WHERE comanda_id = ?");
            $stmt->execute([$id]);

            $stmt = $this->pdo->prepare("DELETE FROM comandas WHERE id = ?");
            $stmt->execute([$id]);

            $this->pdo->commit();
            $_SESSION['mensaje'] = "Comanda eliminada exitosamente.";
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $_SESSION['error'] = "Error al eliminar la comanda: " . $e->getMessage();
            return false;
        }
    }
}
