let countWindow = 0
let maxWindow = 5
let currentFocusWindow = null

const makeDraggable = (element, handle) => {
  let offsetX = 0, offsetY = 0, isDown = false;

  handle.addEventListener("mousedown", (e) => {
    isDown = true;
    offsetX = e.clientX - element.offsetLeft;
    offsetY = e.clientY - element.offsetTop;
    document.body.style.userSelect = "none";
  });

  document.addEventListener("mouseup", () => {
    isDown = false;
    document.body.style.userSelect = "auto";
  });

  document.addEventListener("mousemove", (e) => {
    if (!isDown) return;
    element.style.left = e.clientX - offsetX + "px";
    element.style.top = e.clientY - offsetY + "px";
  });
};

// Active le drag sur ta div
//dragElement(document.getElementById("dragMe"));


const toggleWindowOmsyViews = (act, link = '') => {
	//if(act) document.querySelector('.windowpop-omsy').style.display = 'block'
	//else document.querySelector('.windowpop-omsy').style.display = 'none'

	if(countWindow >= maxWindow) return

	fetch(link)
	.then((res) => { return res.text() })
	.then((res) => {
		createWindow(res)
	})
}

const removeWindow = (windows) => {
	windows.parentNode.parentNode.parentNode.parentNode.parentNode.remove()
}

let zIndex = 99

const windowFocus = (windows) => {
	if(currentFocusWindow) {
		currentFocusWindow.classList.remove('focused')
		currentFocusWindow.style.zIndex = '0'
	}
	zIndex++
	windows.classList.add('focused')
	windows.style.zIndex = zIndex
	currentFocusWindow = windows
}

const createWindow = (contentWindow) => {
	const parser = new DOMParser(),
	dom = parser.parseFromString(contentWindow, "text/html")

	let configWindow = null

	console.log(dom.querySelectorAll('omsy-window-configuration')[0])
	if(dom.querySelectorAll('omsy-window-configuration')[0]) {
		configWindow = dom.querySelectorAll('omsy-window-configuration')[0]
	}

	countWindow = document.querySelectorAll('.windowpop-omsy').length
	// Création du <section>
	const section = document.createElement("section");
	section.className = "windowpop-omsy";
	section.style.top = 'calc(36px + '+(10*countWindow)+'px)'
	section.style.left = 'calc(13px + '+(10*countWindow)+'px)'
	if(configWindow) {
		section.style.width = (configWindow.getAttribute('width') ?? 300)+'px'
		section.style.minHeight = (configWindow.getAttribute('height') ?? 300)+'px'
	}

	section.setAttribute('onclick', 'windowFocus(this)')

	// ----- BARRE DE TITRE -----
	const titlebar = document.createElement("div");
	titlebar.className = "titlebar-content-windopop";

	// Partie gauche avec bouton
	const leftBtns = document.createElement("div");
	leftBtns.className = "left-btns";

	const ul = document.createElement("ul");
	const li = document.createElement("li");

	const btn = document.createElement("button");
	btn.className = "btn-windowpop-controls";
	btn.setAttribute('onclick', 'removeWindow(this)'); // référence à ta fonction

	li.appendChild(btn);
	ul.appendChild(li);
	leftBtns.appendChild(ul);

	// Titre
	const leftTitle = document.createElement("div");
	leftTitle.className = "left-title";

	const bold = document.createElement("b");
	if(configWindow) bold.textContent = configWindow.getAttribute('title') ?? '';
	else bold.textContent = '';

	leftTitle.appendChild(bold);

	// Ajout au titlebar
	titlebar.appendChild(leftBtns);
	titlebar.appendChild(leftTitle);

	// ----- CONTENU -----
	const content = document.createElement("div");
	content.className = "content-windowpop";
	content.setAttribute("contenteditable", "true");
	content.innerHTML = contentWindow

	// ----- Assemblage -----
	section.appendChild(titlebar);
	section.appendChild(content);

	windowFocus(section)
	makeDraggable(section, titlebar)

	// Insertion dans le body
	document.querySelector('body #content-window-omsy').appendChild(section);

}