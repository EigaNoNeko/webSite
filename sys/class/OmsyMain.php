<?php
class OmsyMain {
	public $coreXml = null;
	
	private $__Omsy;
	private $__Database;
	private $__Template;
	private $__Security;
	private $__Server;
	private $__Applications;
	private $__Rewrite;

	private $useTemplate;

	private $__varShare = [];

	public function __get($name) {
		return $this->__varShare[$name] ?? null;
	}
	function __construct($confFileName, $objFileConfig) {
		define('__OmsyMain', $this);
		
		$this->coreXml = $objFileConfig;
		$this->__varShare["__Omsy"] = $this->__Omsy();
		$this->__varShare["__Database"] = $this->__Database();
		$this->__varShare["__Template"] = $this->__Template();
		$this->__varShare["__Security"] = $this->__Security();
		$this->__varShare["__Server"] = $this->__Server();
		$this->__varShare["__Applications"] = $this->__Applications();
		$this->__varShare["__Rewrite"] = $this->__Rewrite();

		foreach ($this->__Omsy->includes->item as $value) {
			if($value->type == "class") {
				if(!$this->addClass((string) $value->src)) continue;
				$buffPrimClass[] = $value->src;
			} elseif($value->type == "module") {
				if(!$this->addModule((string) $value->src)) continue;
			} elseif($value->type == "function") {
				if(!$this->addFunction((string) $value->src)) continue;
			}
		}

		$this->OmsyInterface->loadInterface();
	}

	private function __Omsy() {
		$this->__Omsy = $this->coreXml->omsy ?? null;
		if(!isset($this->__Omsy)) $this->omsyDie('Erreur __Omsy.');
		return $this->__Omsy;
	}
	private function __Database() {
		$this->__Database = $this->coreXml->database ?? null;
		if(!isset($this->__Database)) $this->omsyDie('Erreur __Database.');
		return $this->__Database;
	}
	private function __Template() {
		$this->__Template = $this->coreXml->template ?? null;
		if(!isset($this->__Template)) $this->omsyDie('Erreur __Template.');

		// $this->title = $this->__Template->title;
		$this->useTemplate = $this->__Template->folder;
		// $this->usePlugins = explode(',', str_replace(" ", "", $this->__Template->plugins));

		return $this->__Template;
	}
	private function __Security() {
		$this->__Security = $this->coreXml->security ?? null;
		if(!isset($this->__Security)) $this->omsyDie('Erreur __Security.');
		return $this->__Security;
	}
	private function __Server() {
		$this->__Server = $this->coreXml->server ?? null;
		if(!isset($this->__Server)) $this->omsyDie('Erreur __Server.');
		return $this->__Server;
	}
	private function __Applications() {
		$this->__Applications = $this->coreXml->applications ?? null;
		if(!isset($this->__Applications)) $this->omsyDie('Erreur __Applications.');
		return $this->__Applications;
	}
	private function __Rewrite() {
		$fileAdd = __Root . "share/_omsy/rewrite.json";
		if (!file_exists($fileAdd)) file_put_contents($fileAdd, json_encode([], JSON_PRETTY_PRINT));
		$this->__Rewrite = json_decode(file_get_contents($fileAdd));
		return $this->__Rewrite;
	}


	private function acceptedMime($string) {

	}



	public function __include($path, $file) {
		$fullPath = "$path/$file.php";
	
		if (!file_exists($fullPath)) return false;
	
		// Vérifie si la classe existe déjà (fichier déjà chargé)
		if (class_exists($file, false)) {
			$this->__varShare[$file] = new $file($this);
			return true;
		}
	
		include_once($fullPath);
	
		if (!class_exists($file, false)) return false;
	
		$this->__varShare[$file] = new $file($this);
		return true;
	}

	public function addClass($value) { return $this->__include(__Root . "/sys/class/", $value); }
	public function addFunction($value) { return $this->__include(__Root . "/sys/function/", $value); }
	public function addModule($value) { return $this->__include(__Root . "/sys/module/", $value); }

	public function getTemplatePath() { return "usr/template/{$this->useTemplate}/"; }
	public function getPluginPath() { return "usr/plugin/"; }

	public function addLog($msg, $className = 'GoodMorningOmsy', $errType = null) {
		$time = date('H:i:s');
		$date = date('d-m-Y');
		$route = __Root . "share/_omsy/logs/lastet-$date.log";
		if(!file_exists(__Root . "share/_omsy/logs/")) mkdir(__Root . "share/_omsy/logs/");
		$consol = "$time | [$className] | $msg \n";
		if(isset($errType)) $consol = "$time | $errType | [$className] | $msg \n";
		file_put_contents($route, $consol, FILE_APPEND | LOCK_EX);
	}
	public function omsyDie($str, $className) {
		$this->addLog($str, $className, 'OMSY_PANIC');
		// echo $str;
		exit();
	}

	public function setTemplate($config) {
		$install = $config->install;

		$plugins = ['omsyPanel'];

		foreach ($install->plugin as $value) {
			$plugins[] = $value->id;
		}

		$this->coreXml->database->server = $install->database->server;
		$this->coreXml->database->username = $install->database->username;
		$this->coreXml->database->password = $install->database->password;
		$this->coreXml->database->host = $install->database->host;
		$this->coreXml->database->dbname = $install->database->dbname;


		$this->coreXml->template->folder = $install->metadate->package;
		$this->coreXml->template->title = $install->metadate->title;
		$this->coreXml->template->plugins = implode(',', $plugins);

		$this->coreXml->asXml(__Root . 'Core.xml');
		file_put_contents(__Root . 'share/_omsy/rewrite.json', json_encode($install->rewrite));
		file_put_contents(__Root . 'share/_omsy/manifest.json', json_encode($install->manifest));
		file_put_contents(__Root . 'share/_omsy/robots.txt', implode("\n", $install->robots));
	}

	public function api($method, $err, $data = null) {
		$h = [
			'method' => $method,
			'err' => $err
		];
		if($data && is_array($data)) {
			$h['length'] = count($data);
			$h['data'] = $data;
		}
		//$n = array_merge($h, $data);
		echo json_encode($h);
	}
}