<?php
class OmsyCache {
	private $OmsyMain;

	function __construct($OmsyMain) {
		$this->OmsyMain = $OmsyMain;

		define('__SessionCache', $this);
	}

	function delete() {}
	function insert($name, $content, $limit) {
		return __OmsySql->execute(
			'INSERT INTO omsy_cache(uniqid, name, content, date_, limit_) VALUES(?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))',
			[uniqid(), $name, $content, $limit]
		);
	}
	function get($string, $d = 'name') {
		$allowed = ['name', 'uniqid'];

		if (!in_array($d, $allowed)) return [];
		
		__OmsySql->execute(
        	'DELETE FROM omsy_cache WHERE limit_ <= NOW()'
    	);

		return __OmsySql->execute(
			"SELECT * FROM omsy_cache WHERE {$d} = ? AND limit_ > NOW()",
			[$string]
		)->fetchAll();
	}
}