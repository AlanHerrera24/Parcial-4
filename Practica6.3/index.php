<?php

// Paso 1 define la clase
class Producto {
    private string $nombre;
    private string $categoria;
    private float  $precio;
    private int    $stock;
    //Paso 2
    // Constructor
    public function __construct(string $nombre, string $categoria, float $precio, int $stock) {
        $this->nombre    = $nombre;
        $this->categoria = $categoria;
        $this->precio    = $precio;
        $this->stock     = $stock;
    }

    // Getters
    public function getNombre(): string    { return $this->nombre; }
    public function getCategoria(): string { return $this->categoria; }
    public function getPrecio(): float     { return $this->precio; }
    public function getStock(): int        { return $this->stock; }

    // Paso 3: Devuelve toda la información del producto en una cadena
    public function getInfo(): string {
        return "Nombre: {$this->nombre} | Categoría: {$this->categoria} | "
             . "Precio: \${$this->precio} | Stock: {$this->stock}";
    }

    // Paso 4: Guarda la información en un archivo (una línea por producto)
    public function guardarEnArchivo(string $ruta): void {
        $linea = "{$this->nombre}|{$this->categoria}|{$this->precio}|{$this->stock}\n";
        file_put_contents($ruta, $linea, FILE_APPEND);
    }
}

// Paso 5: Arreglo indexado con 5 objetos Producto
$productos = [
    new Producto("Laptop Lenovo",    "Electrónica",  12999.99, 10),
    new Producto("Teclado Mecánico", "Accesorios",    899.50,  25),
    new Producto("Monitor 24\"",     "Electrónica",  3499.00,   8),
    new Producto("Silla Ergonómica", "Mobiliario",   2150.00,   5),
    new Producto("Webcam HD",        "Accesorios",    650.00,  15),
];

//esto limpia el archivo antes de entregarlo
file_put_contents("productos.txt", "");

// Paso 6: Recorre el arreglo, muestra info y guarda en archivo
foreach ($productos as $producto) {
    // a) Muestra en pantalla
    echo $producto->getInfo() . "<br>\n";

    // b) Guarda en productos.txt
    $producto->guardarEnArchivo("productos.txt");
}

echo "<br>\nProductos guardados en <strong>productos.txt</strong>\n";
?>