<?php

namespace LuisMateos92\Loteria\InsertDatasToDatabase;

use LuisMateos92\Loteria\Config;
use Monolog\Logger;

class InsertDatasToDatabase
{
	private $logger;
	private $config;
	private $objetoMysqli;

	public function __construct(
		Logger $logger,
		Config $config
	) {
		$this->logger = $logger;
		$this->config = $config;
	}

	public function insertDatas(string $datas, string $game)
	{
		$this->logger->info("Insert datas of {$game} to database");
		$database = $this->config->get('database');

		if ($this->connectToDatabase($database)) {
			if ($game === 'euromillones') {
				$this->insertDatasEuromillones($datas);
			} else {
				$this->insertDatasPrimitiva($datas);
			}
		}

		$this->logger->info('End to insert datas in database');
	}

	/**
	 * Connect with database.
	 *
	 * @param array $databaseDatas
	 *
	 * @return bool $connection
	 */
	private function connectToDatabase(array $databaseDatas)
	{
		// Create connection
		$this->objetoMysqli = new \mysqli(
			$databaseDatas['db_host'],
			$databaseDatas['db_username'],
			$databaseDatas['db_password'],
			$databaseDatas['db_name']
		);
		// Check connection
		if ($this->objetoMysqli->connect_errno) {
			$this->logger->error('Error de conexión: ' .
				$this->objetoMysqli->mysqli_connect_errno() .
				', ' . $this->objetoMysqli->mysqli_connect_error());

			return false;
		} else {
			return true;
		}
	}

	/**
	 * Insert datas to Euromillones.
	 *
	 * @param string $datas
	 */
	private function insertDatasEuromillones(string $datas)
	{
		$rows = explode("\n", $datas);

		$header = 0;

		foreach ($rows as $row) {
			if ($header < 1) {
				++$header;
			} else {
				$cols = explode(',', $row);
				$combinacion = $cols[1] . $cols[2] . $cols[3] . $cols[4] . $cols[5];
				$estrellas = $cols[7] . $cols[8];
				$sql = 'INSERT INTO euromillones (fecha, combinacion, estrellas, primer_numero, segundo_numero,' .
					'tercer_numero, cuarto_numero, quinto_numero, primera_estrella, segunda_estrella)' .
					" VALUES ('{$cols[0]}','{$combinacion}','{$estrellas}','{$cols[1]}','{$cols[2]}','{$cols[3]}'," .
					"'{$cols[4]}','{$cols[5]}','{$cols[7]}','{$cols[8]}')";
				$result = $this->objetoMysqli->query("SELECT fecha FROM euromillones WHERE fecha = '{$cols[0]}'");

				if ($result->num_rows > 0) {
					$this->logger->info('La fecha ya existe.');
					/* cerrar el resultset */
					$result->close();
				} else {
					if ($this->objetoMysqli->query($sql) === true) {
						$this->logger->info('Nuevo dato guardado.');
					} else {
						$this->logger->error('Error: ' . $sql . ' ' . $this->objetoMysqli->error);
					}
				}
			}
		}
	}

	/**
	 * Insert datas to Primitiva.
	 *
	 * @param $string datas
	 */
	private function insertDatasPrimitiva(string $datas)
	{
		$rows = explode("\n", $datas);

		$header = 0;

		foreach ($rows as $row) {
			if ($header < 1 || preg_match('/FECHA/i', $row)) {
				++$header;
			} else {
				$cols = explode(',', $row);
				if (!empty($cols[1])) {
					$reintegro = empty($cols[8]) ? 0 : $cols[8];
					$combinacion = $cols[1] . $cols[2] . $cols[3] . $cols[4] . $cols[5] . $cols[6];
					if (!empty($cols[9]) && strlen($cols[9] > 5)) {
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

					$sql = 'INSERT INTO primitiva (fecha, combinacion, complementario, reintegro, ' .
						'primer_numero, segundo_numero, ' .
						'tercer_numero, cuarto_numero, quinto_numero, sexto_numero, joker,' .
						' primer_numero_joker, segundo_numero_joker, tercer_numero_joker, ' .
						'cuarto_numero_joker, quinto_numero_joker,' .
						'sexto_numero_joker, septimo_numero_joker)' .
						" VALUES ('{$cols[0]}','{$combinacion}','{$cols[7]}','{$reintegro}'," .
						"'{$cols[1]}','{$cols[2]}','{$cols[3]}'," .
						"'{$cols[4]}','{$cols[5]}','{$cols[6]}','{$joker}'" .
						" ,'{$joker1}','{$joker2}','{$joker3}','{$joker4}','{$joker5}'," .
						"'{$joker6}','{$joker7}')";
					$result = $this->objetoMysqli->query("SELECT fecha FROM primitiva WHERE fecha = '{$cols[0]}'");

					if ($result->num_rows > 0) {
						$this->logger->info('La fecha ya existe.');

						/* cerrar el resultset */
						$result->close();
					} else {
						if ($this->objetoMysqli->query($sql) === true) {
							$this->logger->info('Nuevo dato guardado.');
						} else {
							$this->logger->error('Error: ' . $sql . ' ' . $this->objetoMysqli->error);
						}
					}
				}
			}
		}
	}
}
