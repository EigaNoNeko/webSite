<?php
class OmsyPermission {
	private $userGroup;

	private $prm_def_code = [
		"OPEN" => 1,

		"EDIT" => 2,

		"DELETE" => 4,

		"PASSWORD_RESET" => 7,

		"RELOAD" => 15,

		"RELOAD_" => 31,
	];
	public function __get($name) { return $returnGet = $this->prm_def_code[$name] ?? null; }
	
	function __construct($OmsyMain) {
		define('__OmsyPermission', $this);

		$OmsyMain->addModule("OmsyAccount");
		$this->userGroup = $OmsyMain->OmsyAccount->role ?? 0;
	}

	public function can($prmCode, $prmRule) {
		$prm = str_split($prmRule, 2);
		$groupMap = [
			0 => (int) $prm[0] ?? 0, // Inviter
			
			2 => (int) $prm[1] ?? 0, // Membre

			4 => (int) $prm[2] ?? 0, // Partenaire

			8 => (int) $prm[3] ?? 0, // Staff

			16 => (int) ($prm[4] ?? 0), // Root
		];
		
		$userPerm = isset($groupMap[$this->userGroup]) ? (int)$groupMap[$this->userGroup] : 0;

		return ($userPerm & $prmCode) !== 0;
	}

	public function loadFile($string) {
		if (!file_exists($string)) return null;
		return json_decode(file_get_contents($string), true);
	}
}