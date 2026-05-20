<?php
$nivelesPH = [];
for ($i = 0; $i < 13; $i++) {
 $nivelesPH[] = round(mt_rand(40, 75) / 10, 1); // genera entre 4.0 y 7.5
}
$suma = array_sum($nivelesPH);
$promedioOriginal = $suma / count($nivelesPH);
$mayorDistancia = 0;
$indiceMasDistante = 0;
foreach ($nivelesPH as $indice => $valor) {
 $distancia = abs($valor - $promedioOriginal);
 if ($distancia > $mayorDistancia) {
 $mayorDistancia = $distancia;
 $indiceMasDistante = $indice;
 }
}
$valorDistante = $nivelesPH[$indiceMasDistante];
$nivelesPH[$indiceMasDistante] = -1;
$sumaNueva = 0;
$contador = 0;
foreach ($nivelesPH as $valor) {
 if ($valor != -1) {
 $sumaNueva += $valor;
 $contador++;
 }
}
$promedioNuevo = $sumaNueva / $contador;
echo "Niveles de pH originales: " . implode(", ", $nivelesPH) . "\n";
echo "Promedio original: $promedioOriginal\n";
echo "Valor más distante: $valorDistante\n";
echo "Niveles de pH con outlier eliminado: " . implode(", ", $nivelesPH) .
"\n";
echo "Nuevo promedio: $promedioNuevo\n";

?>
