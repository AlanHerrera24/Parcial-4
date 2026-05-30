<?php

$usuario = [
    "Nombre" => "Ana",
    "Edad" => 21,
    "Email" => "ana@email.com",
    "Carrera" => "Ingeniería Informática"
];

echo "Nombre: " . $usuario["Nombre"] . "<br>";
echo "Edad: " . $usuario["Edad"] . "<br>";
echo "Email: " . $usuario["Email"] . "<br>";
echo "Carrera: " . $usuario["Carrera"] . "<br><br>";


$usuario["Edad"] = 22;
$usuario["activo"] = true;
echo "Array completo:<br>";
echo "<pre>"; 
print_r($usuario);
echo "</pre>";
?>