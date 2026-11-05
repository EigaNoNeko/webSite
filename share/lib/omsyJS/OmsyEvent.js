/*const OmsyEvent = {
	inArray(needle, haystack, strict = false) {
		if (!Array.isArray(haystack)) return false;

		for (let i = 0; i < haystack.length; i++) {
			if (strict) {
				if (haystack[i] === needle) return true;
			} else {
				if (haystack[i] == needle) return true;
			}
		}

		return false;
	},
	resetEvent() {
		for(const [key, value] of Object.entries(this.event)) {
			if(this.inArray(key, ['resize', 'scroll'])) {
				window.removeEventListener(key, (event) => {
					for(const [keys, values] of Object.entries(value)) {
						this.event[key][keys](event)
					}
				})
			}
		}
		for(const [key, value] of Object.entries(this.var)) {
			if(this.inArray(key, ['timeout'])) {
				for(const [keys, values] of Object.entries(value)) {
					clearTimeout(this.var[key][keys]);
				}
			}

			if(this.inArray(key, ['interval'])) {
				for(const [keys, values] of Object.entries(value)) {
					clearInterval(this.var[key][keys]);
				}
			}
		}
	},
	loadEvent() {
		this.resetEvent()

		for(const [key, value] of Object.entries(this.event)) {
			if(this.inArray(key, ['resize', 'load'])) {
				window.addEventListener(key, (event) => {
					for(const [keys, values] of Object.entries(value)) {
						this.event[key][keys](event)
					}
				})
			}
		}
	},
	event: {
		resize: [],
		load: [],
		scroll: []
	},
	var: {
		timeout: [],
		interval: []
	},
	function: {}
}*/