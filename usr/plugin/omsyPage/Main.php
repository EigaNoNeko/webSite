<?php
class omsyPage {
	public function __construct() {
		define('__OmsyPage', $this);
		
	}
	public function setPage($path) {
		$page = __OmsySql->execute('SELECT * FROM omsypage_page WHERE page_slug=?', [$path])->fetchAll();
		if(is_array($page) && count($page) > 0) {
			$this->pageSql = $page[0];

			return true;
		}
		return false;
	}
	public function getContent() {
		return CMS->GFunction->htmlDecode($this->pageSql['page_content']);
	}

	public function getPageMenu() {
		$data = [];
		$notParent = __OmsySql->execute('SELECT * FROM omsypage_page WHERE page_parent=0')->fetchAll();

		foreach ($notParent as $key => $value) {
			if(!isset($notParent[$key]['kids'])) $notParent[$key]['kids'] = [];
			$parent = __OmsySql->execute('SELECT * FROM omsypage_page WHERE page_parent=?', [$value['ID']])->fetchAll();
			if(isset($parent[0])) $notParent[$key]['kids'] = $parent;
		}

		return $notParent;
	}
	public function updatevar($i, $t, $n, $c) {
		$notParent = __OmsySql->execute('UPDATE omsypage_var SET type=?, name=?, content=? WHERE ID = ?', [$t,$n,$c,$i]);
	}

	public function addvar($t, $n, $c) {
		$notParent = __OmsySql->execute('INSERT INTO omsypage_var(type, name, content) VALUES(?,?,?)', [$t,$n,$c]);
	}

	public function getVariableByType($val) {
		return $parent = __OmsySql->execute('SELECT * FROM omsypage_var WHERE type=? ORDER BY ID ASC', [$val])->fetchAll();
	}

	public function getVariableTypeName($val, $name) {
		return $parent = __OmsySql->execute('SELECT * FROM omsypage_var WHERE type=? AND name=? ORDER BY ID ASC', [$val,$name])->fetchAll();
	}

	public function sepImgAndUrl($co) {
		$pattern = '(?:https?:\/\/[^\s\[\]]+|(?:\.{0,2}\/)?[A-Za-z0-9_\-\/\.]+)';
		//$pattern = '([.]]+)';
		if(preg_match("/($pattern)\[($pattern)\]/", $co, $output_array)) {
			$img = $output_array[1];
			$url = $output_array[2];

			return [$img, $url];
		}

		return ['null', 'null'];
	}
}