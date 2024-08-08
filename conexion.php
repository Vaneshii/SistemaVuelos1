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

            return $resultados;
        } catch (PDOException $e) {
            echo "<p>Error al realizar la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
            return [];
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla = filter_input(INPUT_POST, 'tabla', FILTER_SANITIZE_STRING);

    if ($tabla) {
        $con = new conexion();
        $datos = $con->listarDatos($tabla);

        // Comienza a generar el HTML
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Resultados de la Consulta</title>
        </head>
        <body>
            <h1>Resultados de la Tabla: " . htmlspecialchars($tabla) . "</h1>";

        if (!empty($datos)) {
            echo "<table border='1'>
                    <tr>";

            // Encabezados de la tabla
            foreach ($datos[0] as $columna => $valor) {
                echo "<th>" . htmlspecialchars($columna) . "</th>";
            }
            echo "</tr>";

            // Filas de la tabla
            foreach ($datos as $fila) {
                echo "<tr>";
                foreach ($fila as $valor) {
                    echo "<td>" . htmlspecialchars($valor) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No se encontraron registros en la tabla " . htmlspecialchars($tabla) . "</p>";
        }

        echo "</body>
        </html>";
    } else {
        echo "<p>Nombre de tabla no válido.</p>";
    }
}
?>
