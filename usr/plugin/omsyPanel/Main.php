<?php
class omsyPanel {
	public $r = '';

	public $getPage;

	private $CMS;

	function __construct($CMS) {
		$this->CMS = $CMS;
		$this->getPage = $_GET['page'] ?? '';

		define('__OmsyPanel', $this);

	}

	public function loadAdmin() {
		$prm = null;

		$plugin = $_GET['plugin'] ?? 'omsyPanel';
		$page = $_GET['page'] ?? 'index';
		$page = explode('/', $page);
		foreach ($page as $key => $value) if(empty($value)) unset($page[$key]);
		$page = implode('/', $page);

		define('__GETTER_PAGE__', $page);
		define('__ACTIF_PLUGIN__', $plugin);

		$path = "usr/plugin/$plugin/panel-ui/$page.php";
		$pathConf = "usr/plugin/$plugin/panel-ui/prm.json";

		if(!file_exists(__Root . $path)) {
			return [
				"err" => "path",
				"prm" => "",
				"path" => ""
			];
		}

		if(!file_exists(__Root . $pathConf)) {
			return [
				"err" => "path_conf",
				"prm" => null,
				"path" => null
			];
		}

		$conf = __OmsyJSON->decode(file_get_contents(__Root . $pathConf));
		if(!isset($conf)) {
			return [
				"err" => "json_decode",
				"prm" => null,
				"path" => null
			];
		}
		if(!isset($conf->$page->prm)) {
			return [
				"err" => "conf_page_prm",
				"prm" => null,
				"path" => null
			];
		}
		if(!__OmsyPermission->can(__OmsyPermission->OPEN, $conf->$page->prm)) {
			return [
				"err" => "prm",
				"prm" => null,
				"path" => null
			];
		}
		define('__GETTER_PRM__', $conf->$page->prm);

		return [
			"prm" => $conf->$page->prm,
			"path" => $path
		];
	}

	public function htmlHeader() { if(!isset($_GET['notui'])) include __Root . "panel/use/ui/header.php"; }
	public function htmlFooter() { if(!isset($_GET['notui'])) include __Root . "panel/use/ui/footer.php"; }
	public function HMain($data) { include __Root . "panel/use/kit/hmain.php"; }
	public function SQLX_pagetab($data) {
		$items_max = intval($_GET['q_limit'] ?? 1);
		include __Root . "panel/use/kit/sqlx_pagetab.php";
	}
	public function SQLX_table($table = []) {
		$options = [];
		if(isset($table['omsyPanel.table.options'])) {
			$options = $table['omsyPanel.table.options'];
			unset($table['omsyPanel.table.options']);
		}
		$items_max = intval($_GET['q_limit'] ?? 1);
		include __Root . "panel/use/kit/sqlx_table.php";
	}



	

	public function getMenu2() {
		$menus = [];
		foreach (explode(',', __OmsyMain->__Template->plugins) as $value) {
			$value = trim($value);
			$getPerms = __OmsyJSON->decode(@file_get_contents(__Root . "usr/plugin/{$value}/panel-ui/prm.json") ?? '[]');
			$getInfo = __OmsyJSON->decode(@file_get_contents(__Root . "usr/plugin/{$value}/template.json") ?? '[]');

			if(!isset($getPerms) || !isset($getInfo)) continue;

			$focus = false;

			$sub = [];
			$getFolderPlugin = scandir(__Root . "usr/plugin/{$value}/panel-ui/");
			foreach ($getPerms as $k => $v):
				//if(!is_file(__Root . "usr/plugin/{$value}/panel-ui/$k.php")) continue;
				if(!isset($v->prm)) continue;
				if(isset($v->hidden)) continue;
				if(!__OmsyPermission->can(__OmsyPermission->OPEN, $v->prm)) continue;

				$sfocus = false;
				if(isset($_GET['page']) && $_GET['page'] == $k) {
					$focus = true;
					$sfocus = true;
				}
				$sub['null'][] = [
					"id" => $k,
					"focus" => $sfocus,
					"name" => $v->name ?? $k,
					"url" => "/panel/getter?plugin=$value&page=$k",
				];
			endforeach;

			if(count($sub) <= 0) continue;

			$menus['null'][] = [
				"id" => $value,
				"url" => "/panel/getter?plugin=$value",
				"focus" => $focus,
				"icon" => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M224.62-160q-27.62 0-46.12-18.5Q160-197 160-224.62V-360h40v135.38q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69H360v40H224.62Zm510.76 0H600v-40h135.38q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-360h40v135.38q0 27.62-18.5 46.12Q763-160 735.38-160ZM160-735.38q0-27.62 18.5-46.12Q197-800 224.62-800H360v40H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93V-600h-40v-135.38Zm640 0V-600h-40v-135.38q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H600v-40h135.38q27.62 0 46.12 18.5Q800-763 800-735.38ZM481.54-271.54q13.31 0 22.42-9.11 9.12-9.12 9.12-22.43 0-13.3-9.12-22.42-9.11-9.12-22.42-9.12-13.31 0-22.42 9.12-9.12 9.12-9.12 22.42 0 13.31 9.12 22.43 9.11 9.11 22.42 9.11Zm-20.62-132.23h38.39q1.54-25.54 9.92-42 8.39-16.46 32.31-40.38 30.38-30.39 41.88-51.12 11.5-20.73 11.5-46.11 0-46.31-31.3-75.7-31.31-29.38-80.54-29.38-39.23 0-69.46 19.84-30.24 19.85-47 57.08l36.76 15.46q10.85-25.23 31.5-40.19 20.66-14.96 46.66-14.96 32.84 0 53.81 18.96 20.96 18.96 20.96 49.58 0 18.46-9.12 34.81-9.11 16.34-31.34 36.11-29.93 28.23-42.43 52.15-12.5 23.93-12.5 55.85Z"></path></svg>',
				"name" => $getInfo->name,
				"sub" => $sub
			];
		}

		return $menus;
	}
}