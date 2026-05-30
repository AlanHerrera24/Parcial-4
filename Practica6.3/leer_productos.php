<?php

// Paso 1: Clase Producto 
class Producto {
    private string $nombre;
    private string $categoria;
    private float  $precio;
    private int    $stock;

    public function __construct(string $nombre, string $categoria, float $precio, int $stock) {
        $this->nombre    = $nombre;
        $this->categoria = $categoria;
        $this->precio    = $precio;
        $this->stock     = $stock;
    }

    public function getNombre(): string    { return $this->nombre; }
    public function getCategoria(): string { return $this->categoria; }
    public function getPrecio(): float     { return $this->precio; }
    public function getStock(): int        { return $this->stock; }

    public function getInfo(): string {
        return "Nombre: {$this->nombre} | Categoría: {$this->categoria} | "
             . "Precio: \${$this->precio} | Stock: {$this->stock}";
    }
}

// Paso 2: Función que lee el archivo y reconstruye los objetos
function leerProductosDesdeArchivo(string $ruta): array {
    $productos = [];

    // Paso 3: Verifica que el archivo exista antes de abrirlo
    if (!file_exists($ruta)) {
        echo "El archivo '$ruta' no existe. Ejecuta index.php primero.<br>\n";
        return $productos;
    }

    // Lee todas las líneas ignorando saltos de línea vacíos
    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $numero => $linea) {
        // Paso 4: Divide la línea usando "|" como delimitador
        $partes = explode("|", $linea);

        // Validación: la línea debe tener exactamente 4 campos
        if (count($partes) !== 4) {
            echo "Linea " . ($numero + 1) . " con formato incorrecto, se omite: $linea<br>\n";
            continue;
        }

        [$nombre, $categoria, $precio, $stock] = $partes;

        // Valida que precio y stock sean numéricos antes de crear el objeto
        if (!is_numeric($precio) || !is_numeric($stock)) {
            echo "Datos invalidos en linea " . ($numero + 1) . ", se omite.<br>\n";
            continue;
        }

        // Paso 5: Crea el objeto y lo agrega al arreglo
        $productos[] = new Producto(
            trim($nombre),
            trim($categoria),
            (float) $precio,
            (int)   $stock
        );
    }

    return $productos;
}

// Paso 6: Obtiene los productos desde el archivo
$productosLeidos = leerProductosDesdeArchivo("productos.txt");

// Paso 7: Muestra la información de cada producto reconstruido
echo "<strong>Productos leídos desde archivo:</strong><br>\n";
foreach ($productosLeidos as $producto) {
    echo $producto->getInfo() . "<br>\n";
}

echo "<br>\n Total: " . count($productosLeidos) . " producto(s) cargados.\n";
?>