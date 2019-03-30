<?php
$url = 'https://www.loteriasyapuestas.es/f/loterias/documentos/Euromillones/Historicocombinaciones/HistoricoEuromillones.xls';

$fileContents = file_get_contents($url);

var_dump($fileContents);
?>