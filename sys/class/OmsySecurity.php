<?php
class OmsySecurity {
	private const TTL = 300 * 3; // 5min * 3
	private const MAX_ATTEMPTS = 5;

	private $StrPath;
	private $ArrayPath;
	private $Args = [];

	private $interface = [
		"publicInterface" => true,
		"adminInterface" => true,
		"applicationProgrammingInterface" => true
	];

	private $Mime = [
		"txt" => "text/plain",
		"htm" => "text/html",
		"html" => "text/html",
		"css" => "text/css",

		"js" => "application/javascript",
		"json" => "application/json",
		"xml" => "application/xml",

		"swf" => "application/x-shockwave-flash",
		"flv" => "video/x-flv",

		"php" => "application/x-httpd-php",

		"png" => "image/png",
		"jpe" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"jpg" => "image/jpeg",

		"gif" => "image/gif",
		"bmp" => "image/bmp",

		"webp" => "image/webp",

		"ico" => "image/vnd.microsoft.icon",

		"tiff" => "image/tiff",
		"tif" => "image/tiff",

		"svg" => "image/svg+xml",

		"zip" => "application/zip",
		"rar" => "application/x-rar",
		"rar" => "application/x-rar-compressed",

		"exe" => "application/x-msdownload",
		"msi" => "application/x-msdownload",

		"cab" => "application/vnd.ms-cab-compressed",
		"mp3" => "audio/mpeg",

		"qt" => "video/quicktime",
		"mov" => "video/quicktime",
		"mp4" => "video/mp4",
		"webm" => "video/webm",

		"pdf" => "application/pdf",

		"psd" => "image/vnd.adobe.photoshop",
		"ai" => "application/postscript",
		"eps" => "application/postscript",
		"ps" => "application/postscript",

		"doc" => "application/msword",
		"rtf" => "application/rtf",

		"xls" => "application/ms-excel",
		"ppt" => "application/ms-powerpoint",

		"odt" => "application/vnd.oasis.opendocument.text",
		"ods" => "application/vnd.oasis.opendocument.spreadsheet",
	];
	private $AcceptMime = [
		"image" => true,
		"audio" => true,
		"video" => true,
		"text" => true,

		"application/javascript" => true,
		"application/json" => true,
	];

	private $defaultAcessWebUi = [
		"/",
		"/index",
		"/index.php",
		"/index.html",
	];

	private $OmsyMain;

	function __construct($OmsyMain) {
		$this->OmsyMain = $OmsyMain;

		define('__OmsySecurity', $this);
		$OmsyMain->addClass("OmsyRequest");

		$_GET = new OmsyRequest($_GET);
		$_POST = new OmsyRequest($_POST);
		$_REQUEST = new OmsyRequest($_REQUEST);
		// $_SERVER = new OmsyRequest($_SERVER);
		
		// Mise en sercurité des information de l'URL
		// TMP: Ajout dans un tableau CMS->Args...
		$_SERVER['REQUEST_URI'] = explode('?', substr($_SERVER['REQUEST_URI'], 0));
		$_SERVER['REQUEST_URI'] = str_replace([".php", ".html"], "", $_SERVER['REQUEST_URI'][0]);

		if (in_array($_SERVER['REQUEST_URI'], $this->defaultAcessWebUi)) $_SERVER['REQUEST_URI'] = "/index";

		if (preg_match('/(sys)/', $_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI']  = "/404"; // TMP
		if (preg_match('/(share\/_\/)/', $_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI']  = "/404"; // TMP
		if (in_array($_SERVER['REQUEST_URI'], ["/panel", "/panel/"])) $_SERVER['REQUEST_URI']  = "/panel/index";
		if (in_array($_SERVER['REQUEST_URI'], ["/api", "/api/"])) $_SERVER['REQUEST_URI']  = "/api/index";

		$this->StrPath = substr($_SERVER["REQUEST_URI"], 1);
		$this->ArrayPath = explode("/", $this->StrPath);

		foreach ($this->OmsyMain->__Rewrite as $value) {
			$regex = str_replace("/", "\\/", $value->path);
			preg_match("/^$regex$/", $this->StrPath, $matches);

			if (isset($matches[0]) !== false) {
				unset($matches[0]);
				$value->rewrite = substr($value->rewrite, 1);
				$this->StrPath = $value->rewrite;
				$this->Args = $matches;
				break;
			}
		}
	}

	public function optGenerate($userId) {
		$code = str_pad(
			(string) random_int(0, 999999),
			6,
			'0',
			STR_PAD_LEFT
		);

		$key = "otp:{$userId}";
		$id = uniqid();
		__OmsyRedis->setex($key, self::TTL, $code);
		__OmsyRedis->setex("otp:{$userId}:attempts", self::TTL, 0);
		__OmsyRedis->setex("otp:{$userId}:uniqid", self::TTL, $id);

		return [
			'code' => $code,
			'uniqid' => $id,
		];
	}

	public function otpVerify($code, $userId) {
		$key = "otp:{$userId}";
        $attemptsKey = "otp:{$userId}:attempts";
        $uniqid = "otp:{$userId}:uniqid";

		$attempts = (int) __OmsyRedis->get($attemptsKey);
		if ($attempts >= self::MAX_ATTEMPTS) return false;

		$storedCode = __OmsyRedis->get($key);
		if ($storedCode === false) return null;

		__OmsyRedis->incr($attemptsKey);

		if (hash_equals($storedCode, $code)) {
			__OmsyRedis->del($key);
			__OmsyRedis->del($attemptsKey);
			__OmsyRedis->del($uniqid);
			return true;
		}
		return false;
	}

	public function __include($string) {
		if (!file_exists($string)) return false;

		$inclue = false;
		foreach ((array) $this->OmsyMain->__Security as $key => $value) {
			if ($value === "false"){
				$file = file_get_contents($string);
				if (strpos($file, "$key(") === false) $inclue = true;
				else {
					__OmsyMain->addLog(htmlspecialchars("It appears that the $key function is disabled. Please edit the .config/Core.xml file by replacing '<$key>false</$key>' with '<$key>true</$key>'."), "Security");
					exit(htmlspecialchars("It appears that the $key function is disabled. Please edit the .config/Core.xml file by replacing '<$key>false</$key>' with '<$key>true</$key>'."));
				}
			} else {
				$inclue = true;
			}

			if (!$inclue) return false;

			include_once($string);
			return true;
		}

		return false;
	}

	public function include($string) {
		if(!$this->__include($string)) exit("404");
	}

	public function getStrPath() { return $this->StrPath; }
	public function getArrayPath() { return $this->ArrayPath; }
	public function getArgs() { return $this->Args; }

	public function acceptedMime($pathFull) {
		if (!file_exists($pathFull)) return false;

		foreach ($this->Mime as $key => $value) {
			if (!str_contains($pathFull, "$key")) continue;
			if (!isset($this->AcceptMime)) return true;

			$valueS = explode("/", $value);
			if (isset($this->AcceptMime[$valueS[0]])) return true;
			if (isset($this->AcceptMime[$valueS[1]])) return true;
			if (isset($this->AcceptMime[$value])) return true;
		}

		return false;
	}

	public function readFile($Path) {
		if (file_exists($Path)) {
			$extention = pathinfo($Path, PATHINFO_EXTENSION);
			header("Content-Type: " . $this->Mime[$extention]);
			header("Content-Length: " . filesize($Path));
			header('Cache-Control: max-age=31536000, public');
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
			header_remove('Pragma'); // Supprime l'en-tête conflictuel
			readfile($Path);
			return true;
		}
		return false;
	}
}