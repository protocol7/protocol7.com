/*
Author: mg12
Update: 2009/05/11
Author URI: http://www.neoease.com/
*/
(function() {

var Class = {
	create: function() {
		return function() {
			this.initialize.apply(this, arguments);
		}
	}
}

var GhostlyMenu = Class.create();
GhostlyMenu.prototype = {

	initialize: function(target, align, opacity, offset) {
		this.obj = cleanWhitespace(target);
		this.align = align || 'left';
		this.opacity = 0;
		this.maxopacity = opacity || 1;
		this.offset = offset || 0;

		this.menu = this.obj.childNodes
		if (this.menu.length < 2) { return; }

		this.title = this.menu[0];
		this.body = this.menu[1];

		cleanWhitespace(this.body).firstChild.className = 'first';

		setStyle(this.body, 'visibility', 'hidden');
		setStyle(this.body, 'position', 'absolute');
		setStyle(this.body, 'overflow', 'hidden');
		setStyle(this.body, 'display', 'block');

		addListener(this.obj, 'mouseover', bind(this, this.activate), false);
		addListener(this.obj, 'mouseout', bind(this, this.deactivate), false);
	},

	activate: function() {
		var pos = cumulativeOffset(this.title);
		var left = pos[0];
		if (this.align == 'right') {
			var offset = getWidth(this.title) - getWidth(this.body) + this.offset;
			left += offset;
		}
		var top = pos[1] + getHeight(this.title);

		if(this.opacity <= 0) {
			this.title.className += ' current';
		}

		setStyle(this.body, 'left', left + 'px');
		setStyle(this.body, 'top', top + 'px');
		setStyle(this.body, 'visibility', 'visible');
		setStyle(this.body, 'opacity', this.opacity);
		setStyle(this.body, 'MozOpacity', this.opacity);
		setStyle(this.body, 'KhtmlOpacity', this.opacity);
		setStyle(this.body, 'filter', 'alpha(opacity=' + this.opacity * 100 + ')');

		if(this.tid) {
			clearTimeout(this.tid);
		}
		this.tid = setInterval(bind(this, this.appear), 20);
	},

	deactivate: function(){
		if(this.tid) {
			clearTimeout(this.tid);
		}
		this.tid = setInterval(bind(this, this.fade), 40);
	},

	appear: function() {
		this.opacity += 0.1;
		if(this.opacity >= this.maxopacity) {
			this.opacity = this.maxopacity;
			clearTimeout(this.tid);
		}
		setStyle(this.body, 'opacity', this.opacity);
		setStyle(this.body, 'MozOpacity', this.opacity);
		setStyle(this.body, 'KhtmlOpacity', this.opacity);
		setStyle(this.body, 'filter', 'alpha(opacity=' + this.opacity * 100 + ')');
	},

	fade:function() {
		this.opacity -= 0.1;
		if(this.opacity <= 0) {
			this.opacity = 0;
			setStyle(this.body, 'visibility', 'hidden');
			this.title.className = this.title.className.replace('current', '');
			clearTimeout(this.tid);
		}
		setStyle(this.body, 'opacity', this.opacity);
		setStyle(this.body, 'MozOpacity', this.opacity);
		setStyle(this.body, 'KhtmlOpacity', this.opacity);
		setStyle(this.body, 'filter', 'alpha(opacity=' + this.opacity * 100 + ')');
	}
}

$A = function(iterable) {
	if(!iterable) {
		return [];
	}
	if(iterable.toArray) {
		return iterable.toArray();
	} else {
		var results = [];
		for(var i = 0; i < iterable.length; i++) {
			results.push(iterable[i]);
		}
		return results;
	}
}

bind = function() {
	var array = this.$A(arguments);
	var func = array[array.length - 1];
	var _method = func, args = array, object = args.shift();
	return function() {
		return _method.apply(object, args.concat(array));
	}
}

getHeight = function(element) {
	return element.offsetHeight;
}

getWidth = function(element) {
	return element.offsetWidth;
}

setStyle = function(element, key, value) {
	element.style[key] = value;
}

getStyle = function(element, key) {
	return element.style[key];
}

cleanWhitespace = function(list) {
	var node = list.firstChild;
	while (node) {
		var nextNode = node.nextSibling;
		if(node.nodeType == 3 && !/\S/.test(node.nodeValue)) {
			list.removeChild(node);
		}
		node = nextNode;
	}
	return list;
}

cumulativeOffset = function(element) {
	var valueT = 0, valueL = 0;
	do {
		valueT += element.offsetTop  || 0;
		valueL += element.offsetLeft || 0;
		element = element.offsetParent;
	} while (element);
	return [valueL, valueT];
}

addListener = function(element, name, observer, useCapture) {
	if(element.addEventListener) {
		element.addEventListener(name, observer, useCapture);
	} else if(element.attachEvent) {
		element.attachEvent('on' + name, observer);
	}
}

function loadMenus() {
	var align = 'left';
	for(var i = 0; (a = document.getElementsByTagName('link')[i]); i++) {
		if((a.getAttribute('rel') == 'stylesheet') && (a.getAttribute('href').indexOf('rtl.css') != -1)) {
			align = 'right';
		}
	}

	var subscribe = document.getElementById('subscribe');
	if (subscribe) {
		new GhostlyMenu(subscribe, align, 1, 1);
	}

	var menubar = document.getElementById('menus');
	if (menubar) {
		var list = cleanWhitespace(menubar.childNodes);
		for (var i = 0; i < list.length; i++) {
			new GhostlyMenu(list[i], align, 1, 1);
		}
	}
}

if (document.addEventListener) {
	document.addEventListener("DOMContentLoaded", loadMenus, false);

} else if (/MSIE/i.test(navigator.userAgent)) {
	document.write('<script id="__ie_onload_for_inove" defer src="javascript:void(0)"></script>');
	var script = document.getElementById('__ie_onload_for_inove');
	script.onreadystatechange = function() {
		if (this.readyState == 'complete') {
			loadMenus();
		}
	}

} else if (/WebKit/i.test(navigator.userAgent)) {
	var _timer = setInterval( function() {
		if (/loaded|complete/.test(document.readyState)) {
			clearInterval(_timer);
			loadMenus();
		}
	}, 10);

} else {
	window.onload = function(e) {
		loadMenus();
	}
}

})();