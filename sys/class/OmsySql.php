<?php
class OmsySql {
	private $OmsyMain;

	private $sqlHost;
	private $sqlUsername;
	private $sqlPassword;
	private $sqlDBName;

	private $pdo, $success, $result;

	function __construct($OmsyMain) {
		define('__OmsySql', $this);

		$this->OmsyMain = $OmsyMain;
		$this->OmsyMain->addModule('SqlPdoHtmlspecialchars');

		//$this->OmsyMain->__Database;
		$this->sqlHost = $this->OmsyMain->__Database->host;
		$this->sqlUsername = $this->OmsyMain->__Database->username;
		$this->sqlPassword = $this->OmsyMain->__Database->password;
		$this->sqlDBName = $this->OmsyMain->__Database->dbname;

		if(isset(__OmsyMain->coreXml->redis)) {
			$redis = new Redis();
			$redis->connect(__OmsyMain->coreXml->redis->host, 6379);
			$redis->auth(__OmsyMain->coreXml->redis->password);

			define('__OmsyRedis', $redis);
		}

		$this->connect();
	}
	
	public function dbSize() {
		$sql = "
		SELECT SUM(data_length + index_length) AS 'bits'
		FROM information_schema.tables
		WHERE table_schema = '$this->sqlDBName'
		GROUP BY table_schema;
		";

		$size = $this->execute($sql)->fetchAll();

		return intval($size[0]['bits']);
	}

	private function connect() {
		$this->pdo = new PDO(
			"mysql:host=".$this->sqlHost.";dbname=".$this->sqlDBName, 
			$this->sqlUsername, 
			$this->sqlPassword,
			[
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
			]
		);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$this->pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['SqlPdoHtmlspecialchars']);
	}

	public function execute($Request, $Params = null, $Protect = true) {
		try {
			$stmt = $this->pdo->prepare($Request);
			if ($Params === null) $this->success = $stmt->execute();
			else $this->success = $stmt->execute($Params);
			$this->result = $stmt;
		}
		catch (Exception $e) {
		    $this->success = false;
		    __OmsyMain->addLog(
                "ERROR: " . $e->getMessage() . " | Request: '" . $Request . "'",
                'OmsySql'
            );
		}

		return $this;
	}

	public function fetchAll($index = null) {
		if ($this->result === null) return NULL;
		if ($index !== null) return $this->result->fetchAll()[$index];
		return $this->result->fetchAll();
	}


	public function pagination($table, $object) {
		$require = "SELECT * FROM $tab";

		if(!empty($_GET['q_search'])) {
			$q_search = htmlentities($_GET['q_search']);
			$newSql .= " WHERE ".htmlentities($_GET['q_search_name'])." LIKE '%".htmlentities($q_search)."%'";
		}

		if(isset($object['ORDER'])) $require .= "ORDER BY ".$object['ORDER'];

		$max = 8;
		if(isset($_GET['q_limit']) && intval($_GET['q_limit']) > 0) $max = intval($_GET['q_limit']);

		$listChannels = $this->execute(
			$request
		)->fetchAll();
	}


	public function sqlPagex($tab, $sqlEnded = '', $max = 8) {
		$listChannels = $this->execute("SELECT * FROM $tab $sqlEnded")->fetchAll();

		if(isset($_GET['q_limit']) && intval($_GET['q_limit']) > 0) {
			$max = intval($_GET['q_limit']);
		}

		$countChannelVideo = count($listChannels);
		$pagesTotales = ceil($countChannelVideo/$max);
		if(isset($_GET['pp']) AND !empty($_GET['pp']) AND $_GET['pp'] > 0 AND $_GET['pp'] <= $pagesTotales) {
			$_GET['pp'] = intval($_GET['pp']);
			$pageCourante = $_GET['pp'];
		} else {
			$pageCourante = 1;
		}
		$depart = ($pageCourante-1)*$max;
		
		$newSql = "SELECT * FROM $tab";
		if(!empty($_GET['q_search'])) {
			$q_search = htmlentities($_GET['q_search']);
			$newSql .= " WHERE ".htmlentities($_GET['q_search_name'])." LIKE '%".htmlentities($q_search)."%'";
		}
		$newSql .= " $sqlEnded LIMIT $depart, $max";
		///die($newSql);
		$listChannels = $this->execute($newSql)->fetchAll();
		

		return [
			'items' => $listChannels,
			'page' => $pageCourante,
			'max' => $max,
			'totalePage' => $pagesTotales,
			'totaleItems' => $countChannelVideo,
		];
	}
}