<?php

require 'setting.php';

class conexion {
    private $conector = null;

    public function getConexion() {
        $this->conector = new PDO("sqlsrv:server=".SERVIDOR.";database=".DATABASE, USUARIO, PASSWORD);
        $this->conector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar PDO para manejar errores
        return $this->conector;
    }

    public function listarDatos($tabla) {
        try {
            $sql = "SELECT * FROM " . $tabla;
            $stmt = $this->getConexion()->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($resultados) > 0) {
                foreach ($resultados as $fila) {
                    foreach ($fila as $columna => $valor) {
                        echo htmlspecialchars($columna) . ": " . htmlspecialchars($valor) . " | ";
                    }
                    echo "<br>";
                }
            } else {
                echo "No se encontraron registros en la tabla " . htmlspecialchars($tabla);
            }
        } catch (PDOException $e) {
            echo "Error al realizar la consulta: " . htmlspecialchars($e->getMessage());
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla = filter_input(INPUT_POST, 'tabla', FILTER_SANITIZE_STRING);

    if ($tabla) {
        $con = new conexion();
        if ($con->getConexion() != null) {
            echo "<h1>Resultados de la Tabla: " . htmlspecialchars($tabla) . "</h1>";
            $con->listarDatos($tabla);
        } else {
            echo "Error al conectarse a la base de datos";
        }
    } else {
        echo "Nombre de tabla no válido.";
    }
}
?>
