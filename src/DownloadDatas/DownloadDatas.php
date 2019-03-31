<?php

namespace LuisMateos92\Loteria\DownloadDatas;

use LuisMateos92\Loteria\Config;
use GuzzleHttp\Client;
use Monolog\Logger;

class DownloadDatas
{
	private $logger;
	private $config;
	private $guzzle;

	public function __construct(
		Logger $logger,
		Config $config,
		Client $guzzle
	) {
		$this->logger = $logger;
		$this->config = $config;
		$this->guzzle = $guzzle;
	}

	public function downloadDatas(string $game)
	{
		$this->logger->info("Download datas to: {$game}");
		$html = $this->getUrltoDownloadDatas($this->config->get($game));

		if ($game === 'euromillones') {
			if (preg_match(
				'/(https:\/\/docs.google.com\/spreadsheet\/pub\?key\=)+(.+?)(output.+csv)/',
				$html,
				$matches
			)) {
				$url = html_entity_decode($matches[0]);
				$datas = $this->getDatasToGame($url, $game);
			}
		} else {
			if (preg_match(
				"/(https:\/\/docs\.google\.com\/spreadsheet\/pub\?key\=)+" .
					"(.+?)(\&amp\;single\=true\&amp\;gid\=0\&amp\;output\=csv)/",
				$html,
				$matches
			)) {
				$url1 = html_entity_decode($matches[0]);
				$datas = $this->getDatasToGame($url1, $game);

				$url2 = html_entity_decode(str_replace('gid=0', 'gid=1', $matches[0]));
				$datas .= "\n" . $this->getDatasToGame($url2, $game);
			}
		}

		return $datas;
	}

	/**
	 * Get url to download datas of game.
	 *
	 * @param string $url
	 *
	 * @return string $html
	 */
	private function getUrltoDownloadDatas(string $url)
	{
		$response = $this->guzzle->request(
			'GET',
			$url,
			['exceptions' => false]
		);

		$response = $response->getBody()->getContents();

		return $response;
	}

	/**
	 * Download data to Euromillones.
	 *
	 * @param string $url
	 *
	 * @return string $response
	 */
	private function getDatasToGame(string $url)
	{
		$response = $this->guzzle->request(
			'GET',
			$url,
			['exceptions' => false]
		);

		$response = $response->getBody()->getContents();

		return $response;
	}
}
