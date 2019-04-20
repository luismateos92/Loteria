<?php

namespace LuisMateos92\Loteria;

use LuisMateos92\Loteria\DownloadDatas\DownloadDatas;
use LuisMateos92\Loteria\InsertDatasToDatabase\InsertDatasToDatabase;
use LuisMateos92\Loteria\StatsEuromillon\StatsEuromillon;
use LuisMateos92\Loteria\StatsPrimitiva\StatsPrimitiva;
use Monolog\Logger;

class Execute
{
	private $logger;
	private $downloadDatas;
	private $insertDatas;
	private $statsEuromillon;
	private $statsPrimitiva;

	public function __construct(
		Logger $logger,
		DownloadDatas $downloadDatas,
		InsertDatasToDatabase $insertDatas,
		StatsEuromillon $statsEuromillon,
		StatsPrimitiva $statsPrimitiva
	) {
		$this->logger = $logger;
		$this->downloadDatas = $downloadDatas;
		$this->insertDatas = $insertDatas;
		$this->statsEuromillon = $statsEuromillon;
		$this->statsPrimitiva = $statsPrimitiva;
	}

	public function execute(string $game)
	{
		$this->logger->info('Start loterias');
		$datas = $this->downloadDatas->downloadDatas($game);
		$this->insertDatas->insertDatas($datas, $game);
	}

	public function generateStats(string $game)
	{
		$this->logger->info('Start generate stats');
		if ($game === 'euromillones') {
			$this->logger->info('Generate stats to euromillones');
			$this->statsEuromillon->generateStatsToEuromillon();
		} else {
			$this->logger->info('Generate stats to primitiva');
			$this->statsPrimitiva->generateStatsToPrimitiva();
		}
	}
}
