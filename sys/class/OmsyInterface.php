<?php
class OmsyInterface {
	private $OmsyMain;
	private $Rewrites;

	private $isLoaded = false;

	private $htmlTitle;

	private $headerError = [
		404 => [
			"code" => '404', 
			"msg" => 'Not Found'
		],
		400 => [
			"code" => '400', 
			"msg" => 'Bad Request'
		],
		401 => [
			"code" => '401', 
			"msg" => 'Unauthorized'
		],
		403 => [
			"code" => '403', 
			"msg" => 'Forbidden'
		],
		500 => [
			"code" => '500', 
			"msg" => 'Internal Server Error'
		],
		502 => [
			"code" => '502', 
			"msg" => 'Bad Gateway'
		],
		503 => [
			"code" => '503', 
			"msg" => 'Service Unavailable'
		]
	];

	public function setTitle($string) { $this->htmlTitle = $string; }
	public function getTitle() { return $this->htmlTitle ?? __OmsyMain->__Template->title; }

	function __construct($OmsyMain) {
		$this->OmsyMain = $OmsyMain;

		define('__OmsyInterface', $this);
	
		$this->Rewrites = $OmsyMain->__Rewrite ?? (object) [];
	}
	public function loadInterface() {
		if ($this->isLoaded) return;
		if(__OmsySecurity->getArrayPath()[0] == "panel") return $this->adminInterface();
		if(__OmsySecurity->getArrayPath()[0] == "api") return $this->applicationProgrammingInterface();
		if(__OmsySecurity->getArrayPath()[0] == "webhook") return $this->applicationWebHook();
		else return $this->publicInterface();
	}

	public function publicInterface() {
		$this->isLoaded = true;

		if(__OmsySecurity->getStrPath() === 'manifest.json') {
			if(file_exists(__Root . 'share/_omsy/manifest.json')) return __OmsySecurity->readFile(__Root . 'share/_omsy/manifest.json');
		}
		if(__OmsySecurity->getStrPath() === 'robots.txt') {
			if(file_exists(__Root . 'share/_omsy/robots.txt')) return __OmsySecurity->readFile(__Root . 'share/_omsy/robots.txt');
		}

		if (!file_exists($this->OmsyMain->getTemplatePath())) return $this->err(403);
		if(__OmsySecurity->acceptedMime(__Root . __OmsySecurity->getStrPath())) return __OmsySecurity->readFile(__Root . __OmsySecurity->getStrPath());
		
		$permission = __OmsyPermission->loadFile(__Root . $this->OmsyMain->getTemplatePath() . "prm.json");
		if ($permission === null) return $this->err(403);

		
		if(__OmsySecurity->acceptedMime(__Root . $this->OmsyMain->getTemplatePath() . __OmsySecurity->getStrPath()))
			return __OmsySecurity->readFile(__Root . $this->OmsyMain->getTemplatePath() . __OmsySecurity->getStrPath());

		if(!isset($permission[__OmsySecurity->getStrPath()])) return $this->err(404);
		if(!__OmsyPermission->can( __OmsyPermission->OPEN, $permission[__OmsySecurity->getStrPath()])) return $this->err(403);
		
		__SessionOmsy->insertSession();
		__OmsyPlugin->load();
		return __OmsySecurity->include($this->OmsyMain->getTemplatePath() . __OmsySecurity->getStrPath() . ".php");
	}
	public function adminInterface() {
		if(!__OmsyPermission->can(__OmsyPermission->OPEN, '0000010101')) return null;

		if(__OmsySecurity->acceptedMime(__Root . __OmsySecurity->getStrPath())) return __OmsySecurity->readFile(__Root . __OmsySecurity->getStrPath());

		if (!file_exists(__Root . __OmsySecurity->getStrPath() . ".php")) return $this->err(404);

		__OmsyPlugin->load();
		return __OmsySecurity->include(__Root . __OmsySecurity->getStrPath() . ".php");
	}
	public function applicationProgrammingInterface() {
		$apiNotFound = json_encode([
			'error' => [
				'http' => 301,
				'message' => 'Access not unauthorized'
			]
		]);

		if (file_exists("./share/" . __OmsySecurity->getStrPath() . ".php")) return __OmsySecurity->include("./share/" . __OmsySecurity->getStrPath() . ".php");


		$ArrayPath = __OmsySecurity->getArrayPath();
		$permission = __OmsyPermission->loadFile(__Root . "usr/plugin/" .  $ArrayPath[1] . "/api/prm.json");

		if ($permission === null) {
			// TMP
			$bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/api/" . $ArrayPath[2] . ".php";
			if(isset($ArrayPath[3])) $bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/api/" . $ArrayPath[2] . "/" . $ArrayPath[3] . ".php";
			if (file_exists($bPath)) {
				__OmsyPlugin->load();
				return __OmsySecurity->include($bPath);
			} else {
				__OmsyPlugin->load();
				return $this->err(404);
			}
			// TMP
		}
		$fileAcceptMethod = ($permission[$ArrayPath[2]]?? null ?? $permission[$ArrayPath[2] . "/" . $ArrayPath[3]]?? null)['method'] ?? '';

		if(!in_array($_SERVER['REQUEST_METHOD'], explode(',', $fileAcceptMethod))) return $this->err(301, $apiNotFound, 'text/json');

		$bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/api/" . $ArrayPath[2] . ".php";
		if(isset($ArrayPath[3])) $bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/api/" . $ArrayPath[2] . "/" . $ArrayPath[3] . ".php";
		if (file_exists($bPath)) {
			__OmsyPlugin->load();
			return __OmsySecurity->include($bPath);
		} else {
			__OmsyPlugin->load();
			return $this->err(404);
		}
	}

	public function applicationWebHook() {
		if (file_exists("./share/" . __OmsySecurity->getStrPath() . ".php")) return __OmsySecurity->include("./share/" . __OmsySecurity->getStrPath() . ".php");
		$ArrayPath = __OmsySecurity->getArrayPath();
		$bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/webhook/" . $ArrayPath[2] . ".php";
		if(isset($ArrayPath[3])) $bPath = __Root . "usr/plugin/" .  $ArrayPath[1] . "/webhook/" . $ArrayPath[2] . "/" . $ArrayPath[3] . ".php";
		if (file_exists($bPath)) {
			__OmsyPlugin->load();
			return __OmsySecurity->include($bPath);
		} else {
			__OmsyPlugin->load();
			return $this->err(404);
		}
	}

	public function includeInterface() {}

	public function urlParser($data) {
		$bufferGET = [];
		foreach ($_GET->array as $key => $value) $bufferGET[$key] = $value;
		$newArray = array_merge($bufferGET, $data);
		$string = http_build_query($newArray);
		return $string;
	}

	public function err($code = 404, $content = null, $contentType = null) {
		http_response_code($code);
		if(isset($contentType)) header("Content-Type: $contentType;");
		
		if(file_exists(__Root . $this->OmsyMain->getTemplatePath() . "get_error.php")) {
			__OmsyPlugin->load();
			return __OmsySecurity->include(__Root . $this->OmsyMain->getTemplatePath() . "get_error.php");
		} elseif(isset($content)) {
			echo $content;
			return true;
		}
		return true;
	}
}