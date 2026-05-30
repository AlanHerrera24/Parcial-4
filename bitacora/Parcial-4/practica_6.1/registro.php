<?php
// Arreglo indexado con al menos 5 nombres completos
$nombres = [
    "María López",
    "Juan Pérez",
    "Carlos García",
    "Ana Torres",
    "Luis Sánchez"
];

// Nombre del archivo donde se guardarán los nombres
$nombreArchivo = "asistentes.txt";

try {
    // Si ya existe, su contenido se borra y se escribe de nuevo
    $RArchivo = fopen($nombreArchivo, "w");

    // Si fopen devuelve false, el archivo no se pudo abrir
    if (!$RArchivo) {
        throw new Exception("No se pudo abrir el archivo '$nombreArchivo'.");
    }

    // Recorremos el arreglo con foreach y escribimos cada nombre en una línea
    foreach ($nombres as $nombre) {
        // PHP_EOL inserta el salto de línea correcto según el sistema operativo
        fwrite($RArchivo, $nombre . PHP_EOL);
    }

    // Cerramos el archivo para liberar el recurso
    fclose($RArchivo);

    echo "Archivo '$nombreArchivo' creado y nombres escritos correctamente." . PHP_EOL;
    echo "Se registraron " . count($nombres) . " asistentes." . PHP_EOL;

} catch (Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage() . PHP_EOL;
}
?>