<?php
$dashboardApp = file_get_contents(__root__.'/panel/interface/config/apps.json');
$dashboardApp = json_decode($dashboardApp, true);

$cacheSize = CMS->DataBase->execute('SELECT * FROM cachor_page')->fetchAll();
$accounts = CMS->DataBase->execute('SELECT ID, username, register_date FROM omsy_account ORDER BY ID DESC')->fetchAll();
$accountData = CMS->DataBase->execute('SELECT ID FROM omsy_account_obj')->fetchAll();
$accountDataLogin = CMS->DataBase->execute('SELECT * FROM omsy_account_login ORDER BY ID DESC')->fetchAll();

?>
<header class="omsy-header">
	<div class="menus">
		<ul>
			<li>
				<a href="#">
					<img src="http://localhost/share/omsy-product/white_logo_lite.png" height="16px">
				</a>
				<div class="sub_menu">
					<ol>
						<li><a onclick="toggleWindowOmsyViews(true, '/panel/about-omsy')">À propos de Omsy php.b18082024.0</a></li>
						<li><span class="space_menu"></span></li>
						<li><a href="/">Mon site internet</a></li>
						<li><span class="space_menu"></span></li>
						<li><a href="#">Enregistré</a></li>
						<li><a href="#">Ennulé</a></li>
						<li><span class="space_menu"></span></li>
						<li><a onclick="toggleWindowOmsyViews(true, '/panel/app/index')">Test window</a></li>
						<li><a href="#">Fermer la session</a></li>
					</ol>
				</div>
			</li>
			<li>
				<a href="#"><b>OmsyPanel</b></a>
				<div class="sub_menu">
					<ol>
						<li><a onclick="toggleWindowOmsyViews(true, '/panel/about-omsypanel')">À propos de OmsyPanel</a></li>
						<li><a onclick="toggleWindowOmsyViews(true, '/panel/about-omsypanel-mte')">À propos de OmsyPanelMTE</a></li>
						<li><span class="space_menu"></span></li>
						<?php
							$getAppMenu = json_decode(@file_get_contents(__root__."/usr/plugins/OmsyPanel/panel-ui/navico.json") ?? '[]', true);
							foreach($getAppMenu ?? [] as $v):
							?>
						<li><a href="#"><?= $v['name'] ?></a></li>
						<?php endforeach; ?>
						<li><a onclick="toggleWindowOmsyViews(false)">Fermer OmsyPanel</a></li>
					</ol>
				</div>
			</li>
			<li>
				<a href="#">Open</a>
				<div class="sub_menu">
					<ol>
						<?php
							$getAppMenu = json_decode(@file_get_contents(__root__."/usr/plugins/OmsyPanel/panel-ui/navico.json") ?? '[]', true);
							foreach($getAppMenu ?? [] as $v):
							?>
						<li><a href="#"><?= $v['name'] ?></a></li>
						<?php endforeach; ?>
					</ol>
				</div>
			</li>
			<li>
				<a href="#">Task</a>
				<div class="sub_menu">
					<ol>
						<li><span class="text" id="lastRequestAction">no current last request</span></li>
						<li><span class="text" id="get-session">10 Sessions</span></li>
						<li><span class="text"><?= round(strlen(serialize($cacheSize)) / 1024, 2) ?>Ko Cache</span></li>
						<li><span class="space_menu"></span></li>
						<li><span class="text"><?= count($accounts) ?> Accounts</span></li>
						<li><span class="text"><?= count($accountData)+count($accountDataLogin) ?> Accounts Data</span></li>
						<li><span class="space_menu"></span></li>
						<li><a href="#">Refresh last request</a></li>
						<li><a href="#">Purge Sessions</a></li>
						<li><a href="#">Purge Cache</a></li>
					</ol>
				</div>
			</li>
			<li>
				<a href="#">Plugins/Extensions</a>
				<div class="sub_menu">
					<ol>
						<?php foreach($dashboardApp as $value): ?>
						<li>
							<a href="#">
								<span class="name"><?= $value['name'] ?></span>
								<span class="icon">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
								</span>
							</a>
							<div class="super_sub_menu">
								<ol>
									<?php
										$getAppMenu = json_decode(@file_get_contents(__root__."/usr/plugins/{$value['id']}/panel-ui/navico.json") ?? '[]', true);
										foreach($getAppMenu ?? [] as $v):
										?>
									<li><a href="#" onclick="toggleWindowOmsyViews(true, '<?php echo "plugin/{$value['id']}/{$v['url']}.php')" ?>"><?= $v['name'] ?></a></li>
									<?php endforeach; ?>
								</ol>
							</div>
						</li>
						<?php endforeach; ?>
					</ol>
				</div>
			</li>
		</ul>
	</div>
</header>
<section id="content-window-omsy"></section>