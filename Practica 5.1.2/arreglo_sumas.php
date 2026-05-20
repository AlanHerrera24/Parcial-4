<?php
$misNumeros =[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
for ($i=0; $i < 15; $i++) {
 $misNumeros[$i] = rand(1, 100);
}
print_r($misNumeros);

//ciclo foreach
foreach ($misNumeros as $numero) {
 echo $numero . " ";
}

//ciclo for
for ($i = 0; $i < count($misNumeros); $i++) {
 echo $misNumeros[$i] . " ";
}




//la suma de todos
function suma_total(array $numeros): int {
$suma = 0;
foreach ($numeros as $numero) {
$suma += $numero;
}
return $suma;
}
//suma de pares
function suma_pares(array $numeros): int {
$suma = 0;
for ($i = 0; $i < count($numeros); $i += 2) {
$suma += $numeros[$i];
}
return $suma;
}
//suma de los impares
function suma_impares(array $numeros): int {
$suma = 0;
for ($i = 1; $i < count($numeros); $i += 2) {
$suma += $numeros[$i];
}
return $suma;
}