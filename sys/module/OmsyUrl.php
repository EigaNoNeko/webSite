<?php
class OmsyUrl {
	private $CMS = null;

	function __construct($CMS) {
		$this->CMS = $CMS;
		define('__OmsyUrl', $this);
	}

	public function request($url) {
		$html = __SessionCache->get('omsyUrl.'.$url);
		if(!isset($html[0])) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
			$html = curl_exec($ch);
			curl_close($ch);

			__SessionCache->insert('omsyUrl.'.$url, $html, 1);
		} else $html = $html[0]['content'];
		return $html;
	}
}