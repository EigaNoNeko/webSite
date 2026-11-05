<?php
$buffSerie = [];
$buff = [];
$datas = __OmsySql->execute('SELECT * FROM as_crawler WHERE taille > 0')->fetchAll();
foreach ($datas as $data) {
	$buffSerie[] = $data['catalogue'];
	$buff[$data['catalogue']] = [];
	$data['data'] = json_decode(html_entity_decode($data['data']), true);
	foreach ($data['data']['data'] as $value) {
		$e = explode('/', $value['url']);
		$segment = $e[0];
		$type = null;
		if (str_contains($segment, 'saison'))	  $type = 'saison';
		elseif (str_contains($segment, 'kai'))	 $type = 'kai';
		elseif (str_contains($segment, 'hs'))	  $type = 'hs';
		elseif (str_contains($segment, 'film'))	$type = 'film';
		elseif (str_contains($segment, 'oav'))	 $type = 'oav';
		if ($type === null) continue; // ignorer les URLs non reconnues
		$buff[$data['catalogue']][] = [
			'src'  => $segment,
			'type' => $type
		];
	}
}
?>
<?= omsp_header() ?>
<section class="kit-head">
	<div class="kit-grid-contenaire">
		<div class="information">
			<h2>Syncronniser les informations</h2>
		</div>
	</div>
	<div class="kit-grid-contenaire">
		<ul>
			<li id="opt">
				<section class="kit-select">
					<div class="kit-select-name">
						<label>Episodes</label>
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-387.69 327.69-540h304.62L480-387.69Z"/></svg>
					</div>
					<div class="kit-select-menu">
						<div class="kit-select-option" onclick="updateOpt(this)">
							<input hidden type="checkbox" name="opt[]" value="images">
							<label>Images</label>
						</div>
						<div class="kit-select-option" onclick="updateOpt(this)">
							<input hidden type="checkbox" name="opt[]" value="episodes" checked>
							<label>Episodes</label>
						</div>
						<div class="kit-select-option" onclick="updateOpt(this)">
							<input hidden type="checkbox" name="opt[]" value="staff">
							<label>Staff</label>
						</div>
						<div class="kit-select-option" onclick="updateOpt(this)">
							<input hidden type="checkbox" name="opt[]" value="characters">
							<label>Characters / Voice actors</label>
						</div>
						<div class="kit-select-option" onclick="updateOpt(this)">
							<input hidden type="checkbox" name="opt[]" value="informations">
							<label>Informations</label>
						</div>
					</div>
				</section>
			</li>
			<li>
				<div class="kit-select input-mode">
					<div class="kit-select-name">
						<input type="text" value="<?= $_GET['serie'] ?? '' ?>" id="channel-search-a" placeholder="Syncronniser...">
					</div>
					<div class="kit-select-menu" id="search-context-menu-a"></div>
				</div>
			</li>
			<li>
				<a onclick="start(selectedCatalogue || null)">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f"><path d="M400-336.92 623.08-480 400-623.08v286.16ZM480.13-120q-74.67 0-140.41-28.34-65.73-28.34-114.36-76.92-48.63-48.58-76.99-114.26Q120-405.19 120-479.87q0-74.67 28.34-140.41 28.34-65.73 76.92-114.36 48.58-48.63 114.26-76.99Q405.19-840 479.87-840q74.67 0 140.41 28.34 65.73 28.34 114.36 76.92 48.63 48.58 76.99 114.26Q840-554.81 840-480.13q0 74.67-28.34 140.41-28.34 65.73-76.92 114.36-48.58 48.63-114.26 76.99Q554.81-120 480.13-120Zm-.13-40q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
					</span>
					<!-- <span class="text">Démarré</span> -->
				</a>
			</li>
			<li>
				<a onclick="refreshCrawler()">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M292.31-147.69 120-320l172.31-172.31L320.85-464l-124 124H800v40H196.85l124 124-28.54 28.31Zm375.38-320L639.15-496l124-124H160v-40h603.15l-124-124 28.54-28.31L840-640 667.69-467.69Z"/></svg>
					</span>
					<!-- <span class="text">Synchronniser</span> -->
				</a>
			</li>
		</ul>
	</div>
</section>
<section class="kit-contenaire" style="padding: calc(var(--size-base) * 5) calc(var(--size-base) * 5);">
	<div id="progress" style="padding-top: 0;"></div>
</section>
<script>
let selectedCatalogue = null;

const serieData = <?= json_encode($buff) ?>;
const serie = <?= json_encode($buffSerie) ?>;
const langs = ['vf', 'vostfr'];
// const langs = ['vf', 'vf1', 'vf2', 'vostfr', 'vastfr'];
let opt = {
	'images': false,
	'episodes': false,
	'staff': false,
	'characters': false,
	'informations': false,
}
let optApi = {
	'images': 'put_crawler_images',
	'episodes': 'put_crawler_episodes',
	'staff': 'put_crawler_staff',
	'characters': 'put_crawler_characters',
	'informations': 'put_crawler_informations',
}
document.querySelectorAll('#opt input').forEach((e) => {
	opt[e.value] = e.checked

	if(e.checked) e.parentNode.classList.add('checked')
})
const updateOpt = (t) => {
	const e = t.querySelector('input');
	e.checked = !e.checked;
	t.classList.toggle('checked', e.checked);
	opt[e.value] = e.checked;
}

async function query(q, url, progress) {
	return Omsy.request('/api/EigaNoNeko/v3/' + url, {
		catalogue: q,
	})
	.then((res) => { progress.message(res) })
	.catch((err) => { progress.err(err) })
}

async function queryEpisodes(q, saison, lang, progress) {
	return Omsy.request('/api/EigaNoNeko/v3/put_crawler_episodes', {
		channel_id: q,
		saison: saison,
		lang: lang
	})
	.then((res) => { progress.message(res) })
	.catch((err) => { progress.err(err) })
}
let i = 1
async function processOpts(catalogue, progress) {
	let opt_ = {
		'images': 0,
		'episodes': 0,
		'staff': 0,
		'characters': 0,
		'informations': 0,
	}

	for (const [key, value] of Object.entries(opt)) {
		if (!value) continue;
		if (key == 'informations' && opt_[key] > 0) continue;
		
		if (key !== 'episodes') {
			await query(catalogue, optApi[key], progress)
			progress.update(i, catalogue + '.enn');
			console.log(i)
			i++

			await sleep(10000);
		}
		else {
			for (const ep of serieData[catalogue]) {
				await Promise.all(langs.map(lang => {
					queryEpisodes(catalogue, ep.src, lang, progress)
					progress.update(i, catalogue + '.enn');
					console.log(i)
					i++
				}));
				await sleep(10000);
			}
		}

		opt_[key]++;
	}
}

const start = async (startFrom = null) => {
    const keys = Object.keys(serieData);
    const startIndex = startFrom ? Math.max(keys.indexOf(startFrom), 0) : 0;

    // ✅ forCount calculé uniquement sur les catalogues traités
    let forCount = 0;
    for (let j = startIndex; j < keys.length; j++) {
        const catalogue = keys[j];
        for (const [key, value] of Object.entries(opt)) {
            if (!value) continue;
            if (key === 'episodes') {
                forCount += serieData[catalogue].length * langs.length;
            } else {
                forCount += 1;
            }
        }
    }

    let i = 1;

    const progress = new OmsyProgress(
        document.querySelector('#progress'),
        forCount + 1
    );

    for (let j = startIndex; j < keys.length; j++) {
        await processOpts(keys[j], progress);
        await sleep(10000);
    }
}

const updateFormA = async (catalogue) => {
	const activeCount = Object.values(opt).filter(Boolean).length;
	const forCount = activeCount * serieData[catalogue].length;

	const progress = new OmsyProgress(
		document.querySelector('#progress'),
		forCount+1
	);

	await processOpts(catalogue, progress);
	await sleep(10000);
}


function searching() {
	Omsy.request("/api/EigaNoNeko/v2/get_crawler_search", {
		query: document.querySelector('#channel-search-a').value
	}, true)
	.then((res) => {
		let list = document.querySelector('#search-context-menu-a')
		list.innerHTML = res.map(e =>
				`<a class="kit-select-option" href="javascript:void(0)" onclick="selectedCatalogue='${e.catalogue}'">${e.catalogue}</a>`
			).join('');
	})
}
document.querySelector('#channel-search-a').addEventListener('input', searching)


const refreshCrawler = async () => {
	const progressA = new OmsyProgress(
		document.querySelector('#progress'),
		48
	);
	for (let i = 0; i <= 48; i++) {
		try { await pageLink(i) }
		catch (err) { console.error(`Erreur à la page ${i}:`, err); }
		progressA.update(i, 'Page numéro '+i+'')
		await sleep(30000);
	}
	progressA.update(48, 'Recherche de catalogues terminer')
}

const pageLink = (page) => {
	return request('/api/EigaNoNeko/v3/crawler_refresh', {
		page: page
	})
	.then((res) => {
		console.log(res)
	})
}
</script>
<?= omsp_footer() ?>