<?php

namespace LuisMateos92\Loteria\StatsEuromillon;

use LuisMateos92\Loteria\Config;
use Monolog\Logger;

class StatsEuromillon
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

	public function generateStatsToEuromillon()
	{
		$this->logger->info('Generate stats to Euromillones');
		$database = $this->config->get('database');

		if ($this->connectToDatabase($database)) {
			$this->generateStatsToEuromillones();
		}

		$this->logger->info('End to generate stats to Euromillones');
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
	 * Generate stats to Euromillones.
	 */
	private function generateStatsToEuromillones()
	{
		$result = $this->objetoMysqli->query('SELECT * FROM euromillones');
		$rows[] = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}

		$statsNum[0] = 0;
		$statsStars[0] = 0;
		for ($i = 1; $i < 51; ++$i) {
			$cantNum = 0;
			$cantStars = 0;
			foreach ($rows as $value) {
				if (count($value) > 10) {
					if ((int) $value['primer_numero'] === $i ||
						((int) $value['segundo_numero'] === $i) ||
						((int) $value['tercer_numero'] === $i) ||
						((int) $value['cuarto_numero'] === $i) ||
						((int) $value['quinto_numero'] === $i)) {
						++$cantNum;
					}
					if (($i <= 12) && (((int) $value['primera_estrella'] === $i) ||
						((int) $value['segunda_estrella'] === $i))) {
						++$cantStars;
					}
				}
			}
			$statsNum[$i] = $cantNum;
			if ($i <= 12) {
				$statsStars[$i] = $cantStars;
			}
		}

		$this->insertStatsEuromillones($statsNum, 'numeros');
		$this->insertStatsEuromillones($statsStars, 'estrellas');
	}

	/**
	 * Insert stats to Euromillones.
	 *
	 * @param array  $stats
	 * @param string $type
	 */
	private function insertStatsEuromillones(array $stats, string $type)
	{
		foreach ($stats as $key => $stat) {
			if ($key >= 1) {
				if ($type === 'numeros') {
					$sqlInsert = 'INSERT INTO euromillones_numeros_stats (numero, cantidad)' .
						" VALUES ('{$key}','{$stat}')";

					$sqlUpdate = "UPDATE euromillones_numeros_stats SET cantidad = '{$stat}' WHERE numero = '{$key}'";

					$result = $this->objetoMysqli->query('SELECT numero FROM euromillones_numeros_stats' .
						" WHERE numero = '{$key}'");
				} else {
					$sqlInsert = 'INSERT INTO euromillones_estrellas_stats (estrella, cantidad_estrella)' .
						" VALUES ('{$key}','{$stat}')";

					$sqlUpdate = "UPDATE euromillones_estrellas_stats SET cantidad_estrella = '{$stat}'" .
					 " WHERE estrella = '{$key}'";

					$result = $this->objetoMysqli->query('SELECT cantidad_estrella FROM' .
						" euromillones_estrellas_stats WHERE estrella = '{$key}'");
				}

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
