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
                        echo $columna . ": " . $valor . " | ";
                    }
                    echo "<br>";
                }
            } else {
                echo "No se encontraron registros en la tabla " . $tabla;
            }
        } catch (PDOException $e) {
            echo "Error al realizar la consulta: " . $e->getMessage();
        }
    }
}

$con = new conexion();
if ($con->getConexion() != null) {
    echo "Conexión exitosa<br>";
    $con->listarDatos("usuarios");
} else {
    echo "Error al conectarse a la base de datos";
}
?>
