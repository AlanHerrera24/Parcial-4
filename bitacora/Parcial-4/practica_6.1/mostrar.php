<?php
$archivo = "asistentes.txt";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Asistentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
            background-color: #2f3b2f; /* verde militar oscuro */
        }

        h1 {
            color: #d8e6d1; /* verde claro */
            border-bottom: 2px solid #7a1f1f; /* rojo mono-eye */
            padding-bottom: 10px;
        }

        ol {
            background-color: #556b55; /* verde zaku */
            border-radius: 8px;
            padding: 20px 40px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
        }

        ol li {
            padding: 8px 0;
            border-bottom: 1px solid #708070;
            font-size: 1.1em;
            color: #f0f5eb;
        }

        ol li:last-child {
            border-bottom: none;
        }

        .error {
            background-color: #5a1f1f;
            color: #ffb3b3;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #ff4d6d; /* ojo rojo */
        }

        .vacio {
            color: #c0c0c0;
            font-style: italic;
        }
    </style>
</head>
<body>

<h1>Lista de Asistentes</h1>

<?php
try {
    //Verifica si el archivo existe
    if (!file_exists($archivo)) {
        throw new Exception("El archivo '$archivo' no existe. Asegúrate de haber ejecutado el registro primero.");
    }

    //Abrir el archivo para lectura
    $fp = fopen($archivo, "r");

    //Si no se pudo abrir lanzar excepción
    if (!$fp) {
        throw new Exception("No se pudo abrir el archivo '$archivo' para lectura.");
    }

    //Leer todas las líneas y guardarlas en un arreglo (ignorando líneas vacías)
    $asistentes = [];
    while (!feof($fp)) {
        $linea = fgets($fp);
        $linea = trim($linea);
        if ($linea !== "") {  // ignorar líneas vacías
            $asistentes[] = htmlspecialchars($linea);
        }
    }

    //Cerrar el archivo
    fclose($fp);

    //Mostrar resultados
    if (count($asistentes) === 0) {
        echo "<p class='vacio'>El archivo está vacío. No hay asistentes registrados.</p>";
    } else {
        echo "<p>Total de asistentes: <strong>" . count($asistentes) . "</strong></p>";
        // desafio de la  <ol>
        echo "<ol>";
        foreach ($asistentes as $nombre) {
            echo "    <li>$nombre</li>\n";
        }
        echo "</ol>";
    }

} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

</body>
</html>