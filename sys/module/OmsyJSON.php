<?php
class OmsyJSON {
	public function __construct($CMS) { define('__OmsyJSON', $this); }

	public function encode($array) {
		$string = json_encode($array);
		return $string;
	}
	public function decode($string, $t = false) {
		$string = json_decode(__OmsyString->htmlDecode($string), $t);
		return $string;	
	}
}