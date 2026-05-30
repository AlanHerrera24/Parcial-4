<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #333; }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        button:hover { background-color: #45a049; }
        .btn-limpiar { background-color: #e74c3c; }
        .btn-limpiar:hover { background-color: #c0392b; }
        .mensaje-ok  { color: green; font-weight: bold; }
        .mensaje-err { color: red;   font-weight: bold; }
        .lista-asistentes {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
        }
        .lista-asistentes ol { margin: 0; padding-left: 20px; }
        .lista-asistentes li { padding: 4px 0; }
    </style>
</head>
<body>

<h1> Registro de Asistentes</h1>
<p>Ingresa el nombre de un asistente y haz clic en <strong>Agregar</strong>.</p>

<?php

$nombreArchivo = "asistentes.txt";
$mensaje       = "";
$tipoMensaje   = "";

// AGREGAR un nombre 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agregar"])) {

    $nuevoNombre = trim($_POST["nombre"] ?? "");

    if ($nuevoNombre === "") {
        $mensaje     = "El nombre no puede estar vacío.";
        $tipoMensaje = "mensaje-err";
    } else {
        try {
            // Modo "a" = append (agrega al final sin borrar lo que ya hay)
            $RArchivo = fopen($nombreArchivo, "a");

            if (!$RArchivo) {
                throw new Exception("No se pudo abrir el archivo.");
            }

            fwrite($RArchivo, $nuevoNombre . PHP_EOL);
            fclose($RArchivo);

            $mensaje     = "'$nuevoNombre' registrado correctamente.";
            $tipoMensaje = "mensaje-ok";

        } catch (Exception $e) {
            $mensaje     = "Error: " . $e->getMessage();
            $tipoMensaje = "mensaje-err";
        }
    }
}

// LIMPIAR el archivo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["limpiar"])) {
    try {
        // Modo "w" sobrescribe y deja el archivo vacío
        $RArchivo = fopen($nombreArchivo, "w");
        if (!$RArchivo) {
            throw new Exception("No se pudo abrir el archivo para limpiar.");
        }
        fclose($RArchivo);
        $mensaje     = "Lista de asistentes borrada.";
        $tipoMensaje = "mensaje-ok";
    } catch (Exception $e) {
        $mensaje     = "Error: " . $e->getMessage();
        $tipoMensaje = "mensaje-err";
    }
}

// Mostrar mensaje de resultado
if ($mensaje !== "") {
    echo "<p class='$tipoMensaje'>$mensaje</p>";
}
?>

<!-- Formulario para ingresar un nombre -->
<form method="POST" action="">
    <label for="nombre">Nombre completo:</label>
    <input type="text" id="nombre" name="nombre"
           placeholder="Ej. Andrea Ramírez" autofocus>
    <button type="submit" name="agregar">Agregar asistente</button>
    <button type="submit" name="limpiar" class="btn-limpiar">Limpiar lista</button>
</form>

<?php
if (file_exists($nombreArchivo)) {
    $lineas = file($nombreArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (count($lineas) > 0) {
        echo "<div class='lista-asistentes'>";
        echo "<h2>Asistentes registrados (" . count($lineas) . ")</h2>";
        echo "<ol>";
        foreach ($lineas as $linea) {
            echo "<li>" . htmlspecialchars($linea) . "</li>";
        }
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<p><em>Aún no hay asistentes registrados.</em></p>";
    }
}
?>

</body>
</html>