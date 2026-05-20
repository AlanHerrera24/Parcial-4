<?php
//declaracion de los colores
$colores = ["Morado", "Azul", "Verde", "Dorado", "Plata"];
//mostrar los colores
echo "Colores: ";
foreach ($colores as $index => $color) {
    echo $color;
    if ($index < count($colores) - 1) {
        echo ", ";
    }
}
echo "<br>";

echo "Primer color: " . $colores[0] . "<br>";
echo "Último color: " . end($colores) . "<br>"; 
echo "Cantidad total de colores: " . count($colores) . "<br>";
?>