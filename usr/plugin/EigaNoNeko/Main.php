<?php
class EigaNoNeko {
	public $cdnImageUrl = 'https://raw.githubusercontent.com/is-omini/eiganoneko-cdn-images/refs/heads/main/';
	function __construct($OmsyMain) {
		define("__EnnMain", $this);

		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Playlists");
		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Peoples");
		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Characters");
		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Catalogues");
		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Watch");
		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "CardItem");

		__OmsyMain->__include(__Root . "usr/plugin/EigaNoNeko/class", "Crawler");
	}

	public function naviguation() {
		return [
			"anime" => [
				"url" => "",
				"subcontent" => [
					"Per categories" => [
						"url" => "/archive?db=categorie"
					],
					"All" => [
						"url" => "/archive?db=catalogue"
					]
				]
			],
			// "Manga" => [
			// 	"url" => "",
			// 	"subcontent" => [
			// 		"Shopping" => [
			// 			"url" => "/archive?db=shopping"
			// 		]
			// 	]
			// ],
			"Industry" => [
				"subcontent" => [
					"Peoples" => [
						"url" => "/archive?db=people"
					],
					"Characters" => [
						"url" => "/archive?db=character"
					]
				]
			],
			"Community" => [
				"url" => "",
				"subcontent" => [
					"All liste" => [
						"url" => "/archive?db=playlist"
					],
					"Users" => [
						"url" => "/archive?db=user"
					]
				]
			],
		];
	}

	// DELETED
	public function homeBanner() {
		$b = [];
		$bann = ['made-in-abyss', 'classroom-of-the-elite', 'dan-da-dan','le-monde-merveilleusement-bizarre-de-gumball'];
		foreach ($bann as $key => $value) {
			$a = __EnnCatalogues->get($value, 'slug_');
			$a['description'] = __OmsyString->substr(__OmsyString->htmlDecode($a['description']), 255);
			$b[] = $a;
		}
		return $b;
	}

	public function getLangue($object, $name) {
		if(isset($object[$name]['french'])) return $object[$name]['french'];
		if(isset($object[$name]['english'])) return $object[$name]['english'];
	}
}