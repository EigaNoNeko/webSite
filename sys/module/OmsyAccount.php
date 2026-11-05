<?php
class OmsyAccount {
	private $CMS;
	private $userID;
	private $getUsername = null;

	function __construct($CMS){
		$this->CMS = $CMS;
		$this->userID = null;

		$headers = getallheaders();
		if(isset($headers['Authorization'])) {
			if (preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches)) {
				$_COOKIE['omsy_token'] = $matches[1];
			}
		}

		if(isset($_COOKIE['omsy_token'])) {
			$e = __OmsySql->execute(
				'SELECT * FROM omsy_account_login WHERE token = ?',
				[$_COOKIE['omsy_token']]
			)->fetchAll()[0]??null;
			if(isset($e['user_id'])) {
				$this->userID = $e['user_id'];
			}
		}

		define('__OmsySession', $this->get($this->userID));
		define('__OmsyAccounts', $this);
	}

	public function get($id, $type = "uniqid") {
		//return [];
		switch ($type) {
			case 'id':
			case 'ID':
				$type = "ID";
				break;

			case 'username':
				$type = "username";
				break;
			
			default:
				$type = "uniqid";
				break;
		}

		$isUser = __OmsySql->execute("SELECT * FROM omsy_account WHERE $type = ?", [$id])->fetchAll();
		if(!isset($isUser[0])) return null;

		$user = array_merge($isUser[0], $this->getAccountObject($isUser[0]['uniqid']));

		if(isset($user['user_avatar'])) $user['user_avatar'] = "https://floagg.com".$user['user_avatar'];

		return $user;
	}

	public function datas($uid) {
		$datas =[];

		$logins = __OmsySql->execute('SELECT * FROM omsy_account_login WHERE user_id = ?' , [$uid])->fetchAll();
		$objects = [];
		foreach (__OmsySql->execute('SELECT * FROM omsy_object WHERE object_content = ?' , [$uid])->fetchAll() as $key => $value) {
			$objects[$value['object_type']][] = $value['object_name'];
		}


		//$datas = array_merge($datas, $logins);
		$datas = array_merge($datas, $objects);

		return $datas;
	}

	public function research($query, $limit = 10) {
        $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
        if ($page < 1) {
            $page = 1;
        }

		$datas = [];
		$querySearch = '%'.htmlentities($query).'%';


		// PAgination
		$totalPeoples = count(__OmsySql->execute(
			'SELECT * FROM omsy_account WHERE username LIKE ?', 
			[$query]
		)->fetchAll());

		$totalPages = ceil($totalPeoples / $limit);
		$offset = ($page - 1) * $limit;

		$buffCatalogId = [];
		$ennCatalogObj = __OmsySql->execute(
			'SELECT * FROM omsy_account WHERE username LIKE ? LIMIT '.$offset.', '.$limit,
			[$query]
		)->fetchAll();

		foreach ($ennCatalogObj as $key => $value) {
			$value = array_merge($value, $this->getAccountObject($value['uniqid']));
			$value['img'] = $value['user_avatar'] ?? '/use/img/avatar/haha.png';

			$value['name'] = $value['username'];
			$value['about'] = "???";
			$value['url_single'] = "account?id=".$value['uniqid'];

			$datas[] = $value;
		}

		return [
            "meta" => [
                "data_type" => "user",
            ],
            "pagination" => [
                "current_page" => $page,
                "max_page" => $totalPages,
                "items" => [
                    "count" => count($datas),
                    "total" => $totalPeoples,
                    "per_page" => $limit,
                ],
            ],
            "data" => $datas,
        ];
	}
	public function getAll_() {
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		if($page < 1) $page = 1;

		$totalPeoples = count(__OmsySql->execute(
			'SELECT ID FROM omsy_account'
		)->fetchAll());

		$totalPages = ceil($totalPeoples / $limit);
		$offset = ($page - 1) * $limit;

		$data = __OmsySql->execute(
			'SELECT * FROM omsy_account ORDER BY ID ASC LIMIT '.$offset.', '.$limit,
		)->fetchAll();

		foreach ($data as $key => $value) {
			$data[$key]['image'] = json_decode(html_entity_decode(htmlspecialchars_decode($value['image'])), true);
			$data[$key]['single_url'] = "/single_character?ID={$value['ID']}";
		}

		return [
			"pagination" => [
				"current_page" => $page,
				"items" => [
					"count" => count($data),
					"total" => $totalPeoples,
					"per_page" => $limit,
				]
			],
			"data" => $data
		];
	}












	function getAccount($uniqid) {
		$getUsername = __OmsySql->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$uniqid])->fetchAll();
		if(!isset($getUsername[0])) return null;

		$getUsername = array_merge($getUsername[0], $this->getAccountObject($getUsername[0]['uniqid']));

		return $getUsername;
	}

	function getAccountObject($uniqid) {
		$getUsername = [];
		$getUserObj = __OmsySql->execute('SELECT * FROM omsy_account_obj WHERE user_id = ? ORDER BY ID ASC', [$uniqid])->fetchAll();
		foreach($getUserObj as $value) {
			$getUsername[$value['name']] = $value['content'];
		}

		$getUsername['last_login'] = __OmsySql->execute('SELECT * FROM omsy_account_login WHERE user_id = ? ORDER BY ID DESC', [$uniqid])->fetchAll()[0]['reg_date'] ?? null;

		return $getUsername;
	}

	function __get($var) {
		if(!isset($this->getUsername) || !is_array($this->getUsername)) {
			__OmsySql->execute(
				'INSERT INTO omsy_account_login (token, user_id, reg_date) VALUES(?,?,Now())',
				[$_COOKIE['omsy_token'] ?? '', $this->userID]
			);

			$this->getUsername = $this->getAccount($this->userID);
		}
		if(isset($this->getUsername[$var])) return $this->getUsername[$var];
		if(isset($this->getUsername['objects'][$var])) return $this->getUsername['objects'][$var];
		return null;
	}

	function __isset($var) {
		if(!isset($this->getUsername) || !is_array($this->getUsername)) {
			__OmsySql->execute(
				'INSERT INTO omsy_account_login (token, user_id, reg_date) VALUES(?,?,Now())',
				[$_COOKIE['omsy_token'] ?? '', $this->userID]
			);

			$this->getUsername = $this->getAccount($this->userID);
		}
		if(isset($this->getUsername[$var])) return $this->getUsername[$var];
		if(isset($this->getUsername['objects'][$var])) return $this->getUsername['objects'][$var];
		return null;
	}

	public function insertObj($name, $content, $user=null) {
		if(!isset($user)) $user = $this->uniqid ?? null;
		if(!isset($user)) return;

		__OmsySql->execute('
			INSERT INTO omsy_account_obj (user_id, name, content)
			VALUES(?,?,?)',
			[$user, $name, $content]
		);
		return true;
	}

	public function getObj($name) {
		$user = $this->uniqid ?? null;
		if(!isset($user)) return;

		$get = __OmsySql->execute('
			SELECT * FROM omsy_account_obj WHERE user_id = ? AND name = ? ORDER BY ID ASC',
			[$user, $name]
		)->fetchAll();
		return $get;
	}

	public function updateObj($ID, $name, $content) {
		if(!isset($user)) $user = $this->uniqid ?? null;
		if(!isset($user)) return;

		__OmsySql->execute('
			UPDATE omsy_account_obj
			SET name = ?, content = ?
			WHERE ID = ?',
			[$name, $content, $ID]
		);
		return true;
	}

	function updatePassword_($old, $new, $conf) {
		if(!$this->uniqid) return 'Non connecter';
		if($new !== $conf) return 'Les mot de passe ne corresponde pas';

		$reg = $this->get($this->uniqid);
		if(!$this->passwordVerfiy($old, $reg['password'])) return 'L\'ancien mot de passe n\'ais pas bon';

		$newPassword = $this->passwordHasher($new);

		__OmsySql->execute(
			'UPDATE omsy_account SET password = ? WHERE uniqid = ?',
			[$newPassword, $this->uniqid]
		);

		if(isset($reg['email'])) {
			__OmsyMailer->send(
				$reg['email'],
				'Votre mot de passe as etait modifier sur Floagg',
				[
					'username' => $reg['username'],
					'mail' => $reg['email'],
					'message' => '
					<p>Floagg.com vous informe que votre de mot de passe as etait modifier.</p>'
				]
			);
		}

		return null;
	}

	function updatePassword($password, $user = null) {
		if(!isset($user)) $user = $this->uniqid;
		if(isset($user) && !__OmsyPermission->can(__OmsyPermission->EDIT, '00000015')) return false;

		$newPassword = $this->passwordHasher($password);

		__OmsySql->execute(
			'UPDATE omsy_account SET password = ? WHERE uniqid = ?',
			[$newPassword, $user]
		);
	}

	function getInfo($user) {
		$getUsername = __OmsySql->execute('SELECT * FROM omsy_account WHERE uniqid = ?', [$user])->fetchAll();
		if(!isset($getUsername[0])) return false;
		$getUsername = $getUsername[0];

		$getUsername['objects'] = [];
		$getUserObj = __OmsySql->execute('SELECT * FROM omsy_account_obj WHERE user_id = ?', [$getUsername['uniqid']])->fetchAll();
		foreach($getUserObj as $value) {
			$getUsername['objects'][$value['name']] = $value['content'];
		}

		return $getUsername;
	}

	function passwordHasher($password) { return password_hash($password, PASSWORD_DEFAULT); }
	function passwordVerfiy($password, $hash) { return password_verify($password, $hash); }

	function getAll() {
		return __OmsySql->execute('SELECT * FROM omsy_account')->fetchAll();
	}

	function create($data) {
		$reg = [
			'uniqid' => $data['uniqid'] ?? uniqid(),
			'username' => $data['username'] ?? '',
			'mail' => $data['mail'] ?? null,
			'password' => $data['password'] ?? '',
			'role' => $data['role'] ?? 2
		];

		$reg['password'] = $this->passwordHasher($reg['password']);

		if(!$this->usernameVirify($reg['username'])) return ['err' => 'Username non valide veuillez utiliser des Nombre ou letre, . , - et _ .'];

		$getUsername = __OmsySql->execute('SELECT * FROM omsy_account WHERE username = ?', [$reg['username']])->fetchAll();

		if(isset($getUsername[0])) return ['err' => 'Nom d\'utilisateur déjà utiliser.'];

		// if (!filter_var($reg['mail'], FILTER_VALIDATE_EMAIL)) return ['err' => 'Email invalide.'];

		__OmsySql->execute(
			"INSERT INTO omsy_account (uniqid, username, email, password, role, register_date) VALUES(?, ?, ?, ?, ?, Now())",
			[$reg['uniqid'], $reg['username'], $reg['mail'], $reg['password'], intval($reg['role'])]
		);

		if(isset($reg['email'])) {
			__OmsyMailer->send(
				$reg['email'],
				'Bienvenue sur Floagg',
				[
					'username' => $reg['username'],
					'mail' => $reg['email'],
					'message' => '
					<p>Floagg.com vous souhaite la bienvenue.</p>'
				]
			);
		}

		$this->setToken($reg['uniqid']);

		__OmsyMain->addLog("Account added to 'omsy_account'", "Accounts");

		return ['account' => $reg, 'success' => true, 'err' => null];
	}

	function connect($data) {
		$reg = [
			'username' => $data['username'] ?? '',
			'password' => $data['password'] ?? ''
		];

		$reqUser = __OmsySql->execute(
			'SELECT * FROM omsy_account WHERE username = ?',
			[$reg['username']]
		)->fetchAll();

		if(!$reqUser) return ['err' => 'L\'utilisateur n\'existe pas.'];

		$reqUser = $reqUser[0];

		// if($this->passwordVerfiy($reg['password'], $reqUser['password'])) {
			if(isset($reqUser['email'])) {
				$otpCode = __OmsySecurity->optGenerate($reqUser['uniqid']);

				__OmsyMailer->send(
					$reqUser['email'],
					'Code de validation de votre compte Floagg',
					[
						'username' => $reqUser['username'],
						'mail' => $reqUser['email'],
						'message' => '
						<p>Pour la sécurité sécurité de votre compte, voicie votre code validation à usage unique valable pendant 15minutes pour vous connecter à votre compte.</p>
						<div style="margin:28px 0;text-align:center;">
							<span style="margin: 0 auto; display:inline-block; padding: 16px; background: #E6EAFF; font-size: 24px; font-weight:bold; letter-spacing:8px;">' . $otpCode['code'] . '</span>
						</div>'
					]
				);

				return ['otp' => true, 'mailing' => $reqUser['email'], 'otpId' => $otpCode['uniqid'], 'otpUser' => $reqUser['uniqid'], 'success' => true, 'err' => null];
			}

			$this->setToken($reqUser['uniqid']);

			__OmsyMain->addLog("Account sing in to 'omsy_account'", "Accounts");

			return ['account' => $reqUser, 'success' => true, 'err' => null];
		// }
		return ['err' => 'Mot de passe incorrect'];
	}

	public function setToken($user) {
		$reqUser = __OmsyAccounts->get($user);
		$userToken = __OmsyMain->GFunction->RandomString(64);
		if(isset($_COOKIE['omsy_token'])) $userToken = htmlentities($_COOKIE['omsy_token']);
		__OmsySql->execute(
			'INSERT INTO omsy_account_login (token, user_id, reg_date) VALUES(?,?,Now())',
			[$userToken, $reqUser['uniqid']]
		);
		$_SESSION = $reqUser;
		$_SESSION['Role'] = intval($reqUser['role']);
		$_COOKIE['omsy_token'] = $userToken;
		setcookie("omsy_token", $userToken, time() + 31536000);
	}

	function usernameVirify($chaine) {
	    return (bool) preg_match('/^[A-Za-z0-9._-]+$/', $chaine);
	}
}