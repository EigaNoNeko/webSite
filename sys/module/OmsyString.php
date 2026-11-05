<?php
class OmsyString {
	private $seedString = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	public function __construct($cmd) { if(!defined('__OmsyString')) define('__OmsyString', $this); }

	public function getSeed() { return $this->seedString; }
	public function setSeed($string) { $this->seedString = $string; }

	public function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = $this->utf8ize($value);
			}
		} elseif (is_string($mixed)) {
			return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
		}
		return $mixed;
	}

	public function htmlDecode($string) {
		if(!is_string($string)) return '';

		$string = html_entity_decode(htmlspecialchars_decode($string ?? ''));
		return html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    }

	public function substr($string, $int = 255, $end = "...") {
		if(strlen($string) >= $int) $string = substr($string, 0, $int).$end;
		return $string;
	}

	public function slugify($text, $divider = '-') {

		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, $divider);
		$text = preg_replace('~-+~', $divider, $text);
		$text = strtolower($text);

		if (empty($text)) return 'n-a';

		return $text;
    }

    public function rand($length = 8, $tmpSeed = null) {
    	$seed = $this->seedString;
    	if(isset($tmpSeed)) $seed = $tmpSeed;

    	return substr(str_shuffle(str_repeat($x=$seed, ceil($length/strlen($x)) )),1,$length);
    }
}