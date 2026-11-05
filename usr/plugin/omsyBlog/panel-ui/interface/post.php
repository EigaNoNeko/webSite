<?= omsp_header() ?>
<style>
</style>
<form method="post" enctype="multipart/form-data" data-submit="Publier">
<input name="submit_form" value="true" hidden>
<section class="kit-head">
	<div class="kit-grid-contenaire">
		<div class="back-button">
			<a href="/panel/getter?plugin=omsyBlog&page=posts">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m294.92-450 227.85 227.85L480-180 180-480l300-300 42.77 42.15L294.92-510H780v60H294.92Z"/></svg>
			</a>
		</div>
		<div class="information">
			<h2>Créer un article</h2>
		</div>
	</div>
	<div class="kit-grid-contenaire">
		<ul>
			<li>
				<a href="/">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M583.65-396.53q42.5-42.68 42.5-103.65 0-60.97-42.68-103.47t-103.65-42.5q-60.97 0-103.47 42.68t-42.5 103.65q0 60.97 42.68 103.47t103.65 42.5q60.97 0 103.47-42.68ZM403.5-423.5Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM243.23-310.73Q136.31-381.46 83.08-500q53.23-118.54 160.04-189.27T479.89-760q129.96 0 236.88 70.73Q823.69-618.54 876.92-500q-53.23 118.54-160.04 189.27T480.11-240q-129.96 0-236.88-70.73ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg>
					</span>
					<span class="name">Aperçu</span>
				</a>
			</li>
			<li>
				<a href="/">
					<span class="icon">
						<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M800-663.08v438.46q0 27.62-18.5 46.12Q763-160 735.38-160H224.62q-27.62 0-46.12-18.5Q160-197 160-224.62v-510.76q0-27.62 18.5-46.12Q197-800 224.62-800h438.46L800-663.08ZM760-646 646-760H224.62q-10.77 0-17.7 6.92-6.92 6.93-6.92 17.7v510.76q0 10.77 6.92 17.7 6.93 6.92 17.7 6.92h510.76q10.77 0 17.7-6.92 6.92-6.93 6.92-17.7V-646ZM536.54-321.92Q560-345.38 560-378.46T536.54-435q-23.46-23.46-56.54-23.46T423.46-435Q400-411.54 400-378.46t23.46 56.54q23.46 23.46 56.54 23.46t56.54-23.46ZM270.77-569.23h296.92v-120H270.77v120ZM200-646v446-560 114Z"/></svg>
					</span>
					<span class="name">Enregistrer le bouillon</span>
				</a>
			</li>
		</ul>
	</div>
</section>
<section class="omsyblog-contenaire post">
	<div class="omsyblog-grid">
		<section class="omsyblog-grid-contenaire">
			<div class="kit-input">
				<label>Titre de l'article</label>
				<input type="text" name="name" placeholder="Saisissez un titre..." value="<?= __OmsyMain->GFunction->htmlDecode($getBlog['blog_title'] ?? '') ?>">
			</div>
			<div class="kit-input">
				<label>Slug (URL)</label>
				<div style="display: flex; align-items: center;">
					<span>/blog/.../<?= $getBlog['blog_slug'] ?? '' ?><!-- <a onclick="aaaa(this)" style="cursor: pointer; color: rgba(var(--color-blue)); padding: calc(var(--size-base) * 2); border: solid 0.5px transparent; display: inline-block; margin-left: 0px;"><?= $getBlog['blog_slug'] ?? '' ?></a> --></span>
					<!-- <input type="text" name="title" placeholder="saisissez-un-titre" value="<?= $getBlog['blog_slug'] ?? '' ?>" style="display: none; margin-left: 0px; padding-left: 0;"> -->
				</div>
				<script>
	const aaaa = (t) => {
		t.parentNode.parentNode.querySelector('input').style.display = 'block'
		t.style.display = 'none'
	}
				</script>
			</div>
			<div class="kit-input">
				<label>Content</label>
				<div class="omsy-editor">
					<div class="option-editor">
						<ul>
							<li><button id="add-image-btn">Ajouter une image</button></li>
						</ul>
						<ul>
							<li>
								<button id="bold-btn">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M293.54-215v-530H488q61.15 0 110.58 38.08Q648-668.85 648-604.38q0 44.84-21.66 73.11-21.65 28.27-46.65 41.04 30.77 10.61 58.77 41.96 28 31.35 28 84.42 0 76.69-56.54 112.77T496-215H293.54Zm86-79.69h113.23q47.23 0 66.77-26.23t19.54-50.31q0-24.08-19.54-50.31-19.54-26.23-68.62-26.23H379.54v153.08Zm0-230.31h103.77q36.46 0 57.81-20.85 21.34-20.84 21.34-49.92 0-30.92-22.57-50.54-22.58-19.61-55.35-19.61h-105V-525Z"/></svg>
								</button>
							</li>
							<li>
								<button id="italic-btn">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M215.77-215v-72.31h152.69l129.62-385.38H345.39V-745h366.15v72.31H571.15L441.54-287.31h140.38V-215H215.77Z"/></svg>
								</button>
							</li>
							<li>
								<button id="underline-btn">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M213.85-155v-60h532.3v60h-532.3Zm120.5-200.5Q282-412.15 282-507.31v-316.15h74.15v319.84q0 60.62 32.23 97.16T480-369.92q59.39 0 91.62-36.54 32.23-36.54 32.23-97.16v-319.84H678v316.15q0 95.16-52.35 151.81-52.34 56.65-145.65 56.65T334.35-355.5Z"/></svg>
								</button>
							</li>
						</ul>
						<ul>
						</ul>
					</div>
					<div class="editor-appkit" contenteditable placeholder="Commencez à ecrire votre article ici..." id="editor-appkit-editcontent"><?= nl2br(__OmsyMain->GFunction->htmlDecode($getBlog['blog_content'] ?? '<p>Commencez à écrire votre article ici...</p>')) ?></div>
					<textarea id="editor-appkit-rendercontent" name="content" hidden><?= nl2br(__OmsyMain->GFunction->htmlDecode($getBlog['blog_content'] ?? '<p>Commencez à écrire votre article ici...</p>')) ?></textarea>
				</div>
			</div>
			<div class="kit-input">
				<label>Extrait</label>
				<textarea name="extra" placeholder="Courte description de l'article (affichée dans les aperçus et résultat de recherche)..."></textarea>
				<span class="sub-input">0 / 160 caractères recommandés</span>
			</div>
		</section>
		<section class="omsyblog-grid-contenaire">
			<div class="kit-input">
				<label>Statut</label>
				<select>
					<option value="Draft">Brouillon</option>
					<option value="Published">Publier</option>
					<option value="Archived">Archiver</option>
				</select>
			</div>
			<div class="kit-input">
				<label>Catgorie</label>
				<input type="text" name="group" placeholder="Sélectionner une catégorie" value="<?= __OmsyMain->GFunction->htmlDecode($getBlog['blog_group'] ?? '') ?>">
			</div>
			<div class="kit-input">
				<label>Tags</label>
				<input type="text" name="tags" placeholder="Ajouter des tag..." value="<?= __OmsyMain->GFunction->htmlDecode($getBlog['blog_tags'] ?? '') ?>">
				<span class="sub-input">Séparez les tags par des virgules</span>
			</div>
			<div class="kit-input">
				<label>Min</label>
				<input type="text" name="min" placeholder="Ajouter des tag..." value="<?= __OmsyMain->GFunction->htmlDecode($getBlog['blog_min'] ?? '') ?>">
				<div class="itemupload-image">
					<div class="left-rendering">
						<img src="<?= __OmsyMain->GFunction->htmlDecode($getBlog['blog_min'] ?? '') ?>" height="auto" width="100%" style="object-fit: contain; min-height: 140px;">
					</div>
					<div class="right-information">
						<div class="text">
							<span class="title">Ajouter une image</span>
							<p class="subtitle" style="text-align: center; font-size: 12px; margin-top: 8px;">JPG, PNG ou WebP.<br>1200x630px recommandé</p>
						</div>
						<span class="button-upload">
							<span class="text">
								
							</span>
							<input type="file" name="image[]" onchange="getImage(this, this.parentNode.parentNode.parentNode.querySelector('img'))">
							<input type="text" name="type[]" value="min" hidden style="display: none;">
						</span>
					</div>
				</div>
			</div>
			<div class="kit-input">
				<label>Date de publication</label>
				<div class="kit-input-space">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M224.62-120q-27.62 0-46.12-18.5Q160-157 160-184.62v-510.76q0-27.62 18.5-46.12Q197-760 224.62-760h70.76v-89.23h43.08V-760h286.16v-89.23h40V-760h70.76q27.62 0 46.12 18.5Q800-723 800-695.38v510.76q0 27.62-18.5 46.12Q763-120 735.38-120H224.62Zm0-40h510.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93v-350.76H200v350.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69ZM200-575.39h560v-119.99q0-9.24-7.69-16.93-7.69-7.69-16.93-7.69H224.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v119.99Zm0 0V-720-575.39Zm280 181.54q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19Zm-181.58-9.19q-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19-12.38 0-21.58-9.19ZM640-393.85q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.57 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.19 9.19 21.57 0 12.39-9.19 21.58-9.2 9.19-21.58 9.19ZM480-240q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q492.38-240 480-240Zm-181.58-9.19q-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q332.38-240 320-240q-12.38 0-21.58-9.19ZM640-240q-12.38 0-21.58-9.19-9.19-9.19-9.19-21.58 0-12.38 9.19-21.58 9.2-9.19 21.58-9.19 12.38 0 21.58 9.19 9.19 9.2 9.19 21.58 0 12.39-9.19 21.58Q652.38-240 640-240Z"/></svg>
					<input type="datetime-local" name="datetime" placeholder="Ajouter des tag..." value="<?= __OmsyMain->GFunction->htmlDecode($getBlog['date_time'] ?? '') ?>">
				</div>
			</div>
		</section>
	</div>
</section>
</form>
<script type="text/javascript">
const edit = document.getElementById('editor-appkit-editcontent');
const content = document.getElementById('editor-appkit-rendercontent');

const addImageBtn = document.getElementById('add-image-btn');
const boldBtn = document.getElementById('bold-btn');
const italicBtn = document.getElementById('italic-btn');
const underlineBtn = document.getElementById('underline-btn');

edit.addEventListener('input', () => {
    const sel = window.getSelection();
    const range = sel.getRangeAt(0);

    // Sauvegarde position curseur
    const startContainer = range.startContainer;
    const startOffset = range.startOffset;

    remplacerDivs(edit);
    content.value = edit.innerHTML;

    // Restaure position curseur
    try {
        const newRange = document.createRange();
        newRange.setStart(startContainer, startOffset);
        newRange.collapse(true);
        sel.removeAllRanges();
        sel.addRange(newRange);
    } catch(e) { console.log(e) }
});

addImageBtn.addEventListener('click', (e) => {
	const url = prompt('Entrez l’URL de l’image :');
	if (url) {
		const img = document.createElement('img');
		img.src = url;
		img.alt = 'Image';
		insererNodeAuCurseur(img);
		/// content.innerHTML = remplacerDivs(edit.innerHTML);
	}

	e.preventDefault()
});

// Bouton Gras
boldBtn.addEventListener('click', (e) => {
	document.execCommand('bold');
	edit.focus();

	e.preventDefault()
});

// Bouton Italique
italicBtn.addEventListener('click', (e) => {
	document.execCommand('italic');
	edit.focus();

	e.preventDefault()
});

// Bouton Souligné
underlineBtn.addEventListener('click', (e) => {
	document.execCommand('underline');
	edit.focus();

	e.preventDefault()
});

function remplacerDivs(node) {
    Array.from(node.querySelectorAll('div')).reverse().forEach(div => {
        div.outerHTML = div.outerHTML
            .replace(/^<div(\s|>)/, '<p$1')
            .replace(/<\/div>$/, '</p>');
    });
}

// Insérer un noeud au curseur
function insererNodeAuCurseur(node) {
	const selection = window.getSelection();
	if (!selection || selection.rangeCount === 0) return;

	const range = selection.getRangeAt(0);
	range.deleteContents();
	range.insertNode(node);

	// Déplacer le curseur après le noeud inséré
	range.setStartAfter(node);
	range.collapse(true);
	selection.removeAllRanges();
	selection.addRange(range);
}
</script>
<?= omsp_footer() ?>