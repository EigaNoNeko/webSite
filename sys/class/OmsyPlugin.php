<?php
// DECO ???!!!
class OmsyPlugin {
	private $Class;
	private $OmsyMain;
	private $loading = false;

	function __construct($OmsyMain) { define("__OmsyPlugin", $this); $this->OmsyMain = $OmsyMain; }
	public function __get($name) { return isset($this->Class[$name])?$this->Class[$name]:NULL;	}

	public function load() {
		if($this->loading) return;
		// Tranormation de la liste des plugins devant etre activer en tableau
		$plugins = explode(',', __OmsyMain->__Template->plugins);
		foreach ($plugins as $key => $value) {
			if(empty($value)) continue; // Es que c'est vide ?

			$value = trim($value); // Suprime la mise en forme..
			if (file_exists(__Root . __OmsyMain->getPluginPath() . $value . "/Main.php")) {
			    if (class_exists($value, false)) continue;
				$i = __OmsySecurity->__include(__Root . __OmsyMain->getPluginPath() . $value . "/Main.php");
				if(!$i) {
					__OmsyMain->addLog(
						"Impossible de charger le plugin : " . __Root . __OmsyMain->getPluginPath() . $value . "/Main.php",
						"OmsyPlugin"
					);
					continue;
				}
				$value = str_replace('-', '', $value); // Suprime les tiré au espace..
				if(!class_exists($value)) {
					__OmsyMain->addLog(
						"Impossible de charger le plugin : " . __Root . __OmsyMain->getPluginPath() . $value . "/Main.php car la class est introuvable",
						"OmsyPlugin"
					);
					continue;
				}
				
				$this->Class[$value] = new $value($this->OmsyMain);
			}
			else __OmsyMain->addLog("Impossible de charger le plugin : $value", "OmsyPlugin");
		}
		$this->loading = true;
	}
}