<?php
class Crawler {
	public $malApi = 'https://api.jikan.moe/v4/anime';
	function __construct() { define('__EnnCrawler', $this); }

	public function request($url) {
		$req = __OmsyUrl->request($this->malApi . $url);
		$req = json_decode(htmlspecialchars_decode($req), true);

		return $req;
	}

	public function download($url, $down) {
		$file = file_get_contents($url);
		if ($file === false) return false;
		$result = @file_put_contents($down, $file);
		if ($result === false) return false;
		return true;
	}

	public function getMalId($catalogue) {
		$e = __OmsySql->execute(
			'SELECT * FROM as_crawler WHERE source = ? LIMIT 1',
			[$catalogue]
		)->fetchAll()[0] ?? NULL;
		if (!isset($e)) return null;

		$e['data'] = json_decode(htmlspecialchars_decode($e['data']), true);

		if(!isset($e['data']['mal_id'])) {
			$da = $this->request(
				'?q='
				. urlencode(str_replace('-', ' ', $catalogue))
				. '&limit=1'
			);
			if (!isset($da['data'][0])) return null;
			$malId = $da['data'][0]['mal_id'];

			$e['data']['mal_id'] = $malId;

			__OmsySql->execute(
				'UPDATE as_crawler SET data = ? WHERE ID = ?',
				[json_encode($e['data']), $e['ID']]
			);

			sleep(1);

			return $malId;
		} else {
			return $e['data']['mal_id'];
		}
	}
}