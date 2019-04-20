<?php

namespace LuisMateos92\Loteria\StatsPrimitiva;

use LuisMateos92\Loteria\Config;
use Monolog\Logger;

class StatsPrimitiva
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

	public function generateStatsToPrimitiva()
	{
		$this->logger->info('Generate stats to Primitiva');
		$database = $this->config->get('database');

		if ($this->connectToDatabase($database)) {
			$this->generateStatsToPrimitivaData();
		}

		$this->logger->info('End to generate stats to Primitiva');
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
	 * Generate stats to Primitiva.
	 */
	private function generateStatsToPrimitivaData()
	{
		$result = $this->objetoMysqli->query('SELECT * FROM primitiva');
		$rows[] = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}

		$statsNum[0] = 0;
		$statsReintegro[0] = 0;
		$statsComplementario[0] = 0;
		$statsJoker[0] = 0;
		for ($i = 0; $i < 50; ++$i) {
			$cantNum = 0;
			$cantReintegro = 0;
			$cantComplementario = 0;
			$cantJoker = 0;
			foreach ($rows as $value) {
				if (count($value) > 10) {
					if ((int) $value['primer_numero'] === $i ||
						((int) $value['segundo_numero'] === $i) ||
						((int) $value['tercer_numero'] === $i) ||
						((int) $value['cuarto_numero'] === $i) ||
						((int) $value['quinto_numero'] === $i) ||
						((int) $value['sexto_numero'] === $i)) {
						++$cantNum;
					}
					if ((int) $value['complementario'] === $i) {
						++$cantComplementario;
					}
					if (($i <= 9) && (((int) $value['reintegro'] === $i))) {
						++$cantReintegro;
					}
					if (($i <= 9) && (int) $value['primer_numero_joker'] === $i ||
						((int) $value['segundo_numero_joker'] === $i) ||
						((int) $value['tercer_numero_joker'] === $i) ||
						((int) $value['cuarto_numero_joker'] === $i) ||
						((int) $value['quinto_numero_joker'] === $i) ||
						((int) $value['sexto_numero_joker'] === $i) ||
						((int) $value['septimo_numero_joker'] === $i)) {
						++$cantJoker;
					}
				}
			}
			$statsNum[$i] = $cantNum;
			$statsComplementario[$i] = $cantComplementario;
			if ($i <= 9) {
				$statsReintegro[$i] = $cantReintegro;
				$statsJoker[$i] = $cantJoker;
			}
		}

		$this->insertStatsPrimitiva($statsNum, 'numeros');
		$this->insertStatsPrimitiva($statsComplementario, 'complementario');
		$this->insertStatsPrimitiva($statsReintegro, 'reintegro');
		$this->insertStatsPrimitiva($statsJoker, 'joker');
	}

	/**
	 * Insert stats to Primitiva.
	 *
	 * @param array  $stats
	 * @param string $type
	 */
	private function insertStatsPrimitiva(array $stats, string $type)
	{
		$check = false;
		foreach ($stats as $key => $stat) {
			if ($type === 'numeros' && $key >= 1) {
				$sqlInsert = 'INSERT INTO primitiva_numeros_stats (numero, cantidad)' .
					" VALUES ('{$key}','{$stat}')";

				$sqlUpdate = "UPDATE primitiva_numeros_stats SET cantidad = '{$stat}' WHERE numero = '{$key}'";

				$result = $this->objetoMysqli->query('SELECT numero FROM primitiva_numeros_stats' .
					" WHERE numero = '{$key}'");
				$check = true;
			} elseif ($type === 'complementario' && $key >= 1) {
				$sqlInsert = 'INSERT INTO primitiva_complementario_stats (numero, cantidad)' .
					" VALUES ('{$key}','{$stat}')";

				$sqlUpdate = "UPDATE primitiva_complementario_stats SET cantidad = '{$stat}'" .
				" WHERE numero = '{$key}'";

				$result = $this->objetoMysqli->query('SELECT numero FROM primitiva_complementario_stats' .
					"WHERE numero = '{$key}'");
				$check = true;
			} elseif ($type === 'reintegro') {
				$sqlInsert = 'INSERT INTO primitiva_reintegro_stats (numero, cantidad)' .
					" VALUES ('{$key}','{$stat}')";

				$sqlUpdate = "UPDATE primitiva_reintegro_stats SET cantidad = '{$stat}' WHERE numero = '{$key}'";

				$result = $this->objetoMysqli->query('SELECT numero FROM primitiva_reintegro_stats' .
					" WHERE numero = '{$key}'");
				$check = true;
			} elseif ($type === 'joker') {
				$sqlInsert = 'INSERT INTO primitiva_joker_stats (numero, cantidad)' .
					" VALUES ('{$key}','{$stat}')";

				$sqlUpdate = "UPDATE primitiva_joker_stats SET cantidad = '{$stat}' WHERE numero = '{$key}'";

				$result = $this->objetoMysqli->query('SELECT cantidad FROM primitiva_joker_stats' .
					" WHERE numero = '{$key}'");
				$check = true;
			}

			if ($check) {
				if ($result->num_rows > 0) {
					if ($this->objetoMysqli->query($sqlUpdate) === true) {
						$this->logger->info('Dato actualizado.');
					} else {
						$this->logger->error('Error: ' . $sqlUpdate . ' ' . $this->objetoMysqli->error);
					}
				} else {
					if ($this->objetoMysqli->query($sqlInsert) === true) {
						$this->logger->info('Nuevo dato estadistico guardado.');
					} else {
						$this->logger->error('Error: ' . $sqlInsert . ' ' . $this->objetoMysqli->error);
					}
				}
				$result->close();
			}
		}
	}
}
