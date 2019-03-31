<?php

namespace LuisMateos92\Loteria;

use LuisMateos92\Loteria\DownloadDatas\DownloadDatas;
use LuisMateos92\Loteria\InsertDatasToDatabase\InsertDatasToDatabase;
use Monolog\Logger;

class Execute
{
	private $logger;
	private $downloadDatas;
	private $insertDatas;

	public function __construct(
		Logger $logger,
		DownloadDatas $downloadDatas,
		InsertDatasToDatabase $insertDatas
	) {
		$this->logger = $logger;
		$this->downloadDatas = $downloadDatas;
		$this->insertDatas = $insertDatas;
	}

	public function execute(string $game)
	{
		$this->logger->info('Start loterias');
		$datas = $this->downloadDatas->downloadDatas($game);
		$this->insertDatas->insertDatas($datas, $game);
	}
}
