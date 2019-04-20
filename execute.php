<?php

require_once __DIR__ . '/vendor/autoload.php';

use LuisMateos92\Loteria\Config;
use LuisMateos92\Loteria\Execute;
use LuisMateos92\Loteria\DownloadDatas\DownloadDatas;
use LuisMateos92\Loteria\InsertDatasToDatabase\InsertDatasToDatabase;
use LuisMateos92\Loteria\StatsEuromillon\StatsEuromillon;
use LuisMateos92\Loteria\StatsPrimitiva\StatsPrimitiva;
// use League\Flysystem\Filesystem;
// use League\Flysystem\Adapter\Local;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// use Carbon\Carbon;

$logger = new Logger('test');
$logger->pushHandler(
	new StreamHandler(__DIR__ . '/log/loterias.log', Logger::DEBUG)
);
$config = new Config();
$guzzle = new Client();
// $carbon = new Carbon();
// $filesystem = new Filesystem(new Local(__DIR__ . '/output/');

$execute = new Execute(
	$logger,
	new DownloadDatas(
		$logger,
		$config,
		$guzzle
	),
	new InsertDatasToDatabase(
		$logger,
		$config
	),
	new StatsEuromillon(
		$logger,
		$config
	),
	new StatsPrimitiva(
		$logger,
		$config
	)
);

$option = -1;
echo "======[GENERATE LOTERIAS DATAS]======\n";
echo "0.- Download and Insert Euromillones\n";
echo "1.- Download and Insert Primitiva\n";
echo "2.- Download and Insert All\n";
echo "3.- Generate Stats to Euromillones\n";
echo "4.- Generate Stats to Primitiva\n";
echo "5.- Generate All Stats\n";
while ($option < 0 || $option > 5) {
	$option = readline('Select an option: ');
	switch ($option) {
		case 0:
			$execute->execute('euromillones');
			break;
		case 1:
			$execute->execute('primitiva');
			break;
		case 2:
			$execute->execute('euromillones');
			$execute->execute('primitiva');
			break;
		case 3:
			$execute->generateStats('euromillones');
			break;
		case 4:
			$execute->generateStats('primitiva');
			break;
		case 5:
			$execute->generateStats('euromillones');
			$execute->generateStats('primitiva');
			break;
	}
}
