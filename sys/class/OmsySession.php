<?php
class OmsySession {
	private $OmsyMain;

	function __construct($OmsyMain) {
		$this->OmsyMain = $OmsyMain;

		define('__SessionOmsy', $this);
	}
	public function __omsy() {}

	public function insertSession() {
		return;
		$existe = __OmsySql->execute("SELECT ID FROM omsy_session WHERE session_id = ?", [session_id()])->fetchAll();
		if(isset($existe[0])) __OmsySql->execute("UPDATE omsy_session SET last_activity = Now() AND page = ? WHERE session_id = ?", [$_SERVER['HTTP_USER_AGENT'], session_id()]);
		else {
			__OmsySql->execute(
				'INSERT INTO omsy_session (session_id, page, agent, last_activity) VALUES(?, ?, ?, NOW())',
				[session_id(), $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'], $_SERVER['HTTP_USER_AGENT']]
			);
		}
	}
	public function clearSession() { __OmsySql->execute("DELETE FROM omsy_session"); }
	public function getAllSession() {
		$buff = [];
		$sessions = __OmsySql->execute('SELECT * FROM omsy_session WHERE last_activity > NOW() - INTERVAL ? SECOND', [100])->fetchAll();
		$i = 0;
		foreach ($sessions as $key => $value) {
			if(
				stripos($value['agent'], 'bot') ||
				stripos($value['agent'], 'python') ||
				stripos($value['page'], 'index') ||
				($value['agent'] == 'aaPanel')
			) continue;
			if($i > 5) continue;
			if(!isset($buff[$value['session_id']])) $i++;
			$buff[$value['session_id']] = $value;
		}
		return $buff;
	}

	public function getBestPageBySession() {
		$sessUser = [];
		$buff = [];

		$sessions = __OmsySql->execute('SELECT * FROM omsy_session')->fetchAll();
		foreach ($sessions as $key => $value) {
            if(stripos($value['agent'], 'bot') || stripos($value['agent'], 'python')) continue;
            if(stripos($value['page'], 'robots.txt')) continue;
			if(isset($sessUser[$value['session_id']])) continue;

			if(!isset($buff[strtolower($value['page'])])) $buff[strtolower($value['page'])] = 0;
			$sessUser[$value['session_id']] = true;
			$buff[strtolower($value['page'])]++;
		}
		arsort($buff);
		return $buff;
	}

	public function afterCMSLoad() {}
}