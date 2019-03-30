<?php

// Conexión con la BD
define("DB_HOST","host" ); 
define("DB_USER", "username"); 
define("DB_PASS", "password"); 
define("DB_DATABASE", "database"); 

// Create connection
$objetoMysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
// Check connection
if ($objetoMysqli -> connect_errno){
    die("Error de conexión: " . $objetoMysqli->mysqli_connect_errno() . ", " . $objetoMysqli->mysqli_connect_error()); 
}
else{
    echo "La conexión tuvo éxito\n";
}

$euromillones = 'http://www.lotoideas.com/euromillones-resultados-historicos-de-todos-los-sorteos/';
$primitiva = 'http://www.lotoideas.com/primitiva-resultados-historicos-de-todos-los-sorteos/';
// $file = 'https://docs.google.com/spreadsheet/pub?key=0AhqMeY8ZOrNKdEFUQ3VaTHVpU29UZ3l4emFQaVZub3c&output=csv';

$htmlEuromillones = file_get_contents($euromillones);
$htmlPrimitiva = file_get_contents($primitiva);

// Euromillones
if(preg_match('/(https:\/\/docs.google.com\/spreadsheet\/pub\?key\=)+(.+?)(output.+csv)/', $htmlEuromillones, $matches))
{
	$datas = file_get_contents(html_entity_decode($matches[0]));
}

$rows = explode("\n", $datas);

$header = 0;

foreach ($rows as $row) {
	if ($header<1) {
		$header++;
	} else {
		$cols = explode(',', $row);
		$combinacion = $cols[1] . $cols[2] . $cols[3] . $cols[4] . $cols[5];
		$estrellas = $cols[7] . $cols[8];
		$sql = "INSERT INTO euromillones (fecha, combinacion, estrellas, primer_numero, segundo_numero, tercer_numero, cuarto_numero, quinto_numero, primera_estrella, segunda_estrella)" .
				" VALUES ('{$cols[0]}','{$combinacion}','{$estrellas}','{$cols[1]}','{$cols[2]}','{$cols[3]}','{$cols[4]}','{$cols[5]}','{$cols[7]}','{$cols[8]}')";
		$result = $objetoMysqli->query("SELECT fecha FROM euromillones WHERE fecha = '{$cols[0]}'");

		if($result->num_rows > 0)
		{
		    echo "La fecha ya existe.\n";
    		/* cerrar el resultset */
    		$result->close();
		} else {
			if ($objetoMysqli->query($sql) === TRUE) {
 		   		echo "Nuevo dato guardado\n";
			} else {
    			echo "Error: " . $sql . " " . $objetoMysqli->error . "\n";
			}
		}
	}
}

$count = $objetoMysqli->query("SELECT count(*) as num FROM euromillones");

if (mysqli_num_rows($count) > 0) {
	while($row = mysqli_fetch_assoc($count)) {
		$numCombinaciones = $row["num"];
	}
} else {
	echo "0 results\n";
}

$out = "El numero total de combinaciones es de {$numCombinaciones}\n";

$primerNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, primer_numero FROM euromillones GROUP BY primer_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($primerNumero) > 0) {
	while($row = mysqli_fetch_assoc($primerNumero)) {
		$out .= "El número {$row["primer_numero"]} aparece en primer lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$segundoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, segundo_numero FROM euromillones GROUP BY segundo_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($segundoNumero) > 0) {
	while($row = mysqli_fetch_assoc($segundoNumero)) {
		$out .= "El número {$row["segundo_numero"]} aparece en segundo lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$terceroNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, tercer_numero FROM euromillones GROUP BY tercer_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($terceroNumero) > 0) {
	while($row = mysqli_fetch_assoc($terceroNumero)) {
		$out .= "El número {$row["tercer_numero"]} aparece en tercer lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$cuartoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, cuarto_numero FROM euromillones GROUP BY cuarto_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($cuartoNumero) > 0) {
	while($row = mysqli_fetch_assoc($cuartoNumero)) {
		$out .= "El número {$row["cuarto_numero"]} aparece en cuarto lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$quintoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, quinto_numero FROM euromillones GROUP BY quinto_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($quintoNumero) > 0) {
	while($row = mysqli_fetch_assoc($quintoNumero)) {
		$out .= "El número {$row["quinto_numero"]} aparece en quinto lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$primeraEstrella = $objetoMysqli->query("SELECT count(*) as numero_veces, primera_estrella FROM euromillones GROUP BY primera_estrella ORDER BY numero_veces DESC");

if (mysqli_num_rows($primeraEstrella) > 0) {
	while($row = mysqli_fetch_assoc($primeraEstrella)) {
		$out .= "El número {$row["primera_estrella"]} aparece en primera estrella {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$segundaEstrella = $objetoMysqli->query("SELECT count(*) as numero_veces, segunda_estrella FROM euromillones GROUP BY segunda_estrella ORDER BY numero_veces DESC");

if (mysqli_num_rows($segundaEstrella) > 0) {
	while($row = mysqli_fetch_assoc($segundaEstrella)) {
		$out .= "El número {$row["segunda_estrella"]} aparece en segunda estrella {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$combinacionesGanadoras = $objetoMysqli->query("SELECT combinacion, fecha FROM euromillones ORDER BY fecha DESC");

if (mysqli_num_rows($combinacionesGanadoras) > 0) {
	while($row = mysqli_fetch_assoc($combinacionesGanadoras)) {
		$out .= "La combinacion {$row["combinacion"]} fue el resultado del {$row["fecha"]}\n";
		if ($row["combinacion"] === '0115263850'){
			$out .= "Coincidencia\n";
		}
	}
} else {
	echo "0 results\n";
}

file_put_contents('/tmp/estadisticas.txt', $out);

$datos = $objetoMysqli->query("SELECT * FROM euromillones");

$combinaciones[] = [];
$primero[] = [];
$segundo[] = [];
$tercer[] = [];
$cuarto[] = [];
$quinto[] = [];
$estrella1[] = [];
$estrella2[] = [];

if (mysqli_num_rows($datos) > 0) {
	while($row = mysqli_fetch_assoc($datos)) {
		$combinaciones[$row["fecha"]] = $row["combinacion"];
		$primero[$row["fecha"]] = $row["primer_numero"];
		$segundo[$row["fecha"]] = $row["segundo_numero"];
		$tercer[$row["fecha"]] = $row["tercer_numero"];
		$cuarto[$row["fecha"]] = $row["cuarto_numero"];
		$quinto[$row["fecha"]] = $row["quinto_numero"];
		$estrella1[$row["fecha"]] = $row["primera_estrella"];
		$estrella2[$row["fecha"]] = $row["segunda_estrella"];
	}
} else {
	echo "0 results\n";
}

// 0115263850

// Primitiva
if(preg_match('/(https:\/\/docs\.google\.com\/spreadsheet\/pub\?key\=)+(.+?)(\&amp\;single\=true\&amp\;gid\=0\&amp\;output\=csv)/', $htmlPrimitiva, $matches))
{
	$url1 = $matches[0];
	$url2 = str_replace ('gid=0' , 'gid=1', $matches[0]);
	$datas = file_get_contents(html_entity_decode($url1));
}

$rows = explode("\n", $datas);

$header = 0;

foreach ($rows as $row) {
	if ($header<1) {
		$header++;
	} else {
		$cols = explode(',', $row);
		if(!empty($cols[1]))
		{
			$reintegro = empty($cols[8]) ? 0 : $cols[8];
			if (!empty($cols[9]) && strlen($cols[9]>5))
			{
				$joker = $cols[9];
				$joker1 = $cols[9][0];
				$joker2 = $cols[9][1];
				$joker3 = $cols[9][2];
				$joker4 = $cols[9][3];
				$joker5 = $cols[9][4];
				$joker6 = $cols[9][5];
				$joker7 = $cols[9][6];
			} else {
				$joker = 0;
				$joker1 = 0;
				$joker2 = 0;
				$joker3 = 0;
				$joker4 = 0;
				$joker5 = 0;
				$joker6 = 0;
				$joker7 = 0;
			}

			$sql = "INSERT INTO primitiva (fecha, combinacion, complementario, reintegro, primer_numero, segundo_numero, tercer_numero, cuarto_numero, quinto_numero, sexto_numero, joker," .
					" primer_numero_joker, segundo_numero_joker, tercer_numero_joker, cuarto_numero_joker, quinto_numero_joker, sexto_numero_joker, septimo_numero_joker)" .
					" VALUES ('{$cols[0]}','{$combinacion}','{$cols[7]}','{$reintegro}','{$cols[1]}','{$cols[2]}','{$cols[3]}','{$cols[4]}','{$cols[5]}','{$cols[6]}','{$joker}'" .
					" ,'{$joker1}','{$joker2}','{$joker3}','{$joker4}','{$joker5}','{$joker6}','{$joker7}')";
			$result = $objetoMysqli->query("SELECT fecha FROM primitiva WHERE fecha = '{$cols[0]}'");

			if($result->num_rows > 0)
			{
			    echo "La fecha ya existe.\n";

	    		/* cerrar el resultset */
	    		$result->close();
			} else {
				if ($objetoMysqli->query($sql) === TRUE) {
	 		   		echo "Nuevo dato guardado\n";
				} else {
	    			echo "Error: " . $sql . " " . $objetoMysqli->error . "\n";
				}
			}
		}
	}
}

$datas = file_get_contents(html_entity_decode($url2));

$rows = explode("\n", $datas);

$header = 0;

foreach ($rows as $row) {
	if ($header<1) {
		$header++;
	} else {
		$cols = explode(',', $row);
		if(!empty($cols[1]))
		{
			$reintegro = empty($cols[8]) ? 0 : $cols[8];
			if (!empty($cols[9]) && strlen($cols[9]>5))
			{
				$joker = $cols[9];
				$joker1 = $cols[9][0];
				$joker2 = $cols[9][1];
				$joker3 = $cols[9][2];
				$joker4 = $cols[9][3];
				$joker5 = $cols[9][4];
				$joker6 = $cols[9][5];
				$joker7 = $cols[9][6];
			} else {
				$joker = 0;
				$joker1 = 0;
				$joker2 = 0;
				$joker3 = 0;
				$joker4 = 0;
				$joker5 = 0;
				$joker6 = 0;
				$joker7 = 0;
			}

			$sql = "INSERT INTO primitiva (fecha, combinacion, complementario, reintegro, primer_numero, segundo_numero, tercer_numero, cuarto_numero, quinto_numero, sexto_numero, joker," .
					" primer_numero_joker, segundo_numero_joker, tercer_numero_joker, cuarto_numero_joker, quinto_numero_joker, sexto_numero_joker, septimo_numero_joker)" .
					" VALUES ('{$cols[0]}','{$combinacion}','{$cols[7]}','{$reintegro}','{$cols[1]}','{$cols[2]}','{$cols[3]}','{$cols[4]}','{$cols[5]}','{$cols[6]}','{$joker}'" .
					" ,'{$joker1}','{$joker2}','{$joker3}','{$joker4}','{$joker5}','{$joker6}','{$joker7}')";
			$result = $objetoMysqli->query("SELECT fecha FROM primitiva WHERE fecha = '{$cols[0]}'");

			if($result->num_rows > 0)
			{
			    echo "La fecha ya existe.\n";

	    		/* cerrar el resultset */
	    		$result->close();
			} else {
				if ($objetoMysqli->query($sql) === TRUE) {
	 		   		echo "Nuevo dato guardado\n";
				} else {
	    			echo "Error: " . $sql . " " . $objetoMysqli->error . "\n";
				}
			}
		}
	}
}

$count = $objetoMysqli->query("SELECT count(*) as num FROM primitiva");

if (mysqli_num_rows($count) > 0) {
	while($row = mysqli_fetch_assoc($count)) {
		$numCombinaciones = $row["num"];
	}
} else {
	echo "0 results\n";
}

$out = "El numero total de combinaciones es de {$numCombinaciones}\n";

$primerNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, primer_numero FROM primitiva GROUP BY primer_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($primerNumero) > 0) {
	while($row = mysqli_fetch_assoc($primerNumero)) {
		$out .= "El número {$row["primer_numero"]} aparece en primer lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$segundoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, segundo_numero FROM primitiva GROUP BY segundo_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($segundoNumero) > 0) {
	while($row = mysqli_fetch_assoc($segundoNumero)) {
		$out .= "El número {$row["segundo_numero"]} aparece en segundo lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$terceroNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, tercer_numero FROM primitiva GROUP BY tercer_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($terceroNumero) > 0) {
	while($row = mysqli_fetch_assoc($terceroNumero)) {
		$out .= "El número {$row["tercer_numero"]} aparece en tercer lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$cuartoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, cuarto_numero FROM primitiva GROUP BY cuarto_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($cuartoNumero) > 0) {
	while($row = mysqli_fetch_assoc($cuartoNumero)) {
		$out .= "El número {$row["cuarto_numero"]} aparece en cuarto lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$quintoNumero = $objetoMysqli->query("SELECT count(*) as numero_veces, quinto_numero FROM primitiva GROUP BY quinto_numero ORDER BY numero_veces DESC");

if (mysqli_num_rows($quintoNumero) > 0) {
	while($row = mysqli_fetch_assoc($quintoNumero)) {
		$out .= "El número {$row["quinto_numero"]} aparece en quinto lugar {$row["numero_veces"]} veces\n";
	}
} else {
	echo "0 results\n";
}

$out .= "\n";

$combinacionesGanadoras = $objetoMysqli->query("SELECT combinacion, fecha FROM primitiva ORDER BY fecha DESC");

if (mysqli_num_rows($combinacionesGanadoras) > 0) {
	while($row = mysqli_fetch_assoc($combinacionesGanadoras)) {
		$out .= "La combinacion {$row["combinacion"]} fue el resultado del {$row["fecha"]}\n";
		if ($row["combinacion"] === '0115263850'){
			$out .= "Coincidencia\n";
		}
	}
} else {
	echo "0 results\n";
}

file_put_contents('/tmp/estadisticasPrimitiva.txt', $out);

$datos = $objetoMysqli->query("SELECT * FROM primitiva");

$combinaciones[] = [];
$primero[] = [];
$segundo[] = [];
$tercer[] = [];
$cuarto[] = [];
$quinto[] = [];
$estrella1[] = [];
$estrella2[] = [];

if (mysqli_num_rows($datos) > 0) {
	while($row = mysqli_fetch_assoc($datos)) {
		$combinaciones[$row["fecha"]] = $row["combinacion"];
		$primero[$row["fecha"]] = $row["primer_numero"];
		$segundo[$row["fecha"]] = $row["segundo_numero"];
		$tercer[$row["fecha"]] = $row["tercer_numero"];
		$cuarto[$row["fecha"]] = $row["cuarto_numero"];
		$quinto[$row["fecha"]] = $row["quinto_numero"];
	}
} else {
	echo "0 results\n";
}
$objetoMysqli->close();

?>
