
/************************
Standlibs
Developed by Framfab
************************/

var ff = new Object()
//1.0
ff.version = parseFloat(navigator.appVersion)
ff.opera = (navigator.appName=="Opera") ? 1:0
ff.ie = ff.da = (document.all && !ff.opera) ? 1:0
ff.ns4 = ff.dl = (navigator.appName=="Netscape" && ff.version<5) ? 1:0
ff.w3c = (document.getElementById && !ff.da) ? 1:0
ff.win = (navigator.userAgent.toLowerCase().indexOf("win") > 0) ? 1:0
ff.mac = (navigator.userAgent.toLowerCase().indexOf("mac") > 0) ? 1:0





//1.0
ff.all = new Array()
ff.makeAllObj = function(objParent) {
	if(!objParent){ff.all = new Array();objParent=window}
	var arrCol = [objParent.document.layers, objParent.document.images, objParent.document.forms]
	for (var z in arrCol) {
		for (var t=0; t<arrCol[z].length; t++) {
			var strCurId = arrCol[z][t].name
			if (strCurId!="" && !ff.all[strCurId]){
				if(strCurId.indexOf("_js_layer") > -1) for(k in objParent.document.layers) if(objParent.document.layers[k].name==strCurId){strCurId = k;break}
				ff.all[strCurId] = arrCol[z][t]
				if(z == 0) ff.makeAllObj(arrCol[z][t])
				else ff.all[strCurId].parentLayer = objParent
			}
		}
	}
}

ff.getObj = function(strObj) {
	if(typeof(strObj)=="object") return strObj
	else{
		var obj
		if (ff.ie) obj = document.all[strObj]
		else if (ff.w3c) {
			obj = document.getElementById(strObj)
			if (!obj) obj = document.getElementsByName(strObj)[0]
		} 
		else if(ff.ns4) {
			if (!ff.all[strObj]) ff.makeAllObj()
			obj = ff.all[strObj]
		}
		return obj
	}
}










//1.0
ff.getX = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie||ff.w3c||ff.opera) return objLayer.offsetLeft;
		else if (ff.ns4) return objLayer.left;
	}
}











//1.0
ff.getY = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie||ff.w3c||ff.opera) return objLayer.offsetTop;
		else if (ff.ns4) return objLayer.top;
	}
}











//1.0
ff.getW = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie) return objLayer.style.pixelWidth
		else if(ff.ns4) return objLayer.clip.width
		else if(ff.w3c){
			var style=getComputedStyle(objLayer,null);
			return parseInt(style.getPropertyValue('width'));
		}
	}
}



//1.0
ff.getH = function(strLayer) {
	var h
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie || ff.w3c) {
			var c = objLayer.style.clip
			if (c.length > 0) {
				var arC = c.substr(c.indexOf("(") + 1).split(" ")
				h = parseInt(arC[2])
			} 
			else
				h = null
		} 
		else if(ff.ns4) h = objLayer.clip.height
	}
	return h
}

//1.0
ff.getScrollH = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie) return (ff.mac) ? objLayer.offsetHeight : objLayer.scrollHeight
		else if(ff.ns4) return objLayer.document.height
		else if(ff.w3c) return objLayer.offsetHeight 
	}
}

//1.0
ff.moveTo = function(strLayer, x, y) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie||ff.opera) {
			if (x || x==0) objLayer.style.pixelLeft = x
			if (y || y==0) objLayer.style.pixelTop = y
		} 
		else if (ff.ns4) {
			if (x || x==0) objLayer.left = x
			if (y || y==0) objLayer.top = y
		} 
		else if (ff.w3c) {
			if (x || x==0) objLayer.style.left = x + "px";
			if (y || y==0) objLayer.style.top = y + "px";
		}		
	}
}


//1.0
ff.clip = function(strLayer,t,r,b,l) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie||ff.w3c) objLayer.style.clip = "rect("+t+"px "+r+"px "+b+"px "+l+"px)";
		else if (ff.ns4) {
			objLayer.clip.top = t;
			objLayer.clip.right = r;
			objLayer.clip.bottom = b;
			objLayer.clip.left = l;
		}
	}
}



//1.0
ff.catchMouseEvent = function(strEventName, strHandlerFunction, strLayer, detachEvent){
	if(ff.ns4){
		if(strLayer){
			var objLayer = ff.getObj(strLayer)
			if(objLayer) objLayer.document.captureEvents(Event[strEventName.toUpperCase()])
		}
		else document.captureEvents(Event[strEventName.toUpperCase()])
	}
	if(strLayer){
		var objLayer = ff.getObj(strLayer)
		if(objLayer){
			if(ff.ns4) objLayer.document["on"+strEventName] = (detachEvent) ? null : eval(strHandlerFunction)
			else objLayer["on"+strEventName] = (detachEvent) ? null : eval(strHandlerFunction)
		}
	}
	else document["on"+strEventName] = (detachEvent) ? null : eval(strHandlerFunction)
}//1.0
ff.getEventY = function(evt){
		if(ff.ns4) return evt.pageY
		else if (ff.ie) return window.event.clientY
		else if (ff.w3c) return evt.clientY
}

//1.0
ff.createLayer = function(id, left, top, width, zIndex, content, parentLayer){
	if(parentLayer) parentLayer = ff.getObj(parentLayer)
	else parentLayer = (ff.ns4) ? window : document.body
	top = top || 0; left = left || 0; width = width || 0; content = content || " swesdsd"
	if(ff.ie || ff.w3c){
		if(document.createElement){
			var curLay = document.createElement("DIV");
			curLay.id = id
			curLay.style.position = "absolute"
		}
		else{
			parentLayer.innerHTML += "<div id='" + id + "' style='position:absolute;'></div>"
			var curLay = ff.getObj(id)
		}
		curLay.style.left = left + "px"
		curLay.style.top = top + "px"
		curLay.style.width = width + "px"
		curLay.style.zIndex = zIndex || 1
		curLay.innerHTML = content
		if(document.createElement) parentLayer.appendChild(curLay)
	}
	else if(ff.ns4){
		var curLay = new Layer(width, parentLayer)
		curLay.left = left
		curLay.top = top
		curLay.visibility = "show"
		curLay.zIndex = zIndex || 1
		curLay.document.open()
		curLay.document.write(content)
		curLay.document.close()
		if(parentLayer) parentLayer.document.layers[id] = curLay
		else document.layers[id] = curLay
	}
}
//1.0
ff.barScroll = new Object()
ff.barScroll.intPicWidth = 11
ff.barScroll.intArrowHeight = 11
ff.barScroll.intBarHeight = 11
ff.barScroll.xOffset = 10
ff.barScroll.scrollSpeed = 6
ff.barScroll.urlBarPic = "bar.gif"
ff.barScroll.urlBarBgPic = "bar_bg.gif"
ff.barScroll.urlUpArrowPic = "arrow_up.gif"
ff.barScroll.urlDownArrowPic = "arrow_down.gif"
ff.barScroll.timer = null

ff.barScroll.init = function(strLayer, blnNoBar, blnNoBarBg){
	ff.barScroll[strLayer] = new Object()
	ff.barScroll[strLayer].h = ff.getH(strLayer)
	ff.barScroll[strLayer].sh = ff.getScrollH(strLayer)
	ff.barScroll[strLayer].y = ff.getY(strLayer)
	if(ff.barScroll[strLayer].sh>ff.barScroll[strLayer].h){
		ff.barScroll[strLayer].maxscroll = ff.barScroll[strLayer].sh - ff.barScroll[strLayer].h
		ff.barScroll[strLayer].curpos = 0

		var x = ff.getX(strLayer) + ff.getW(strLayer) + ff.barScroll.xOffset
		var str1 = "<a href='javascript:void(0)' onmouseover=\"ff.barScroll.arrow('" + strLayer + "', "; var str2 = ")\" onmouseout=\"clearTimeout(ff.barScroll.timer)\"><img src='"; var str3 = "' border='0'></a>"

		if(!ff.getObj(strLayer + "ArrowUp")) ff.createLayer(strLayer + "ArrowUp", x, ff.barScroll[strLayer].y, ff.barScroll.intPicWidth, 5, str1 + "1" + str2 + ff.barScroll.urlUpArrowPic + str3)

		var a = ff.barScroll[strLayer].y+ff.barScroll[strLayer].h-ff.barScroll.intArrowHeight
		ff.createLayer(strLayer + "ArrowDown", x, a, ff.barScroll.intPicWidth, 5, str1 + "-1" + str2 + ff.barScroll.urlDownArrowPic + str3)

		var a = ff.barScroll[strLayer].y+ff.barScroll.intArrowHeight
		var b = ff.barScroll[strLayer].h - 2*ff.barScroll.intArrowHeight

		if(!blnNoBarBg){
			ff.createLayer(strLayer + "BarBg", x, a, ff.barScroll.intPicWidth, 6,  "<img src='" + ff.barScroll.urlBarBgPic + "' name='" + strLayer + "BgPic' width=" + ff.barScroll.intPicWidth + " height=" + b + ">")
			ff.catchMouseEvent("click", "ff.barScroll.click", strLayer+"BarBg")	
		}
		if(!blnNoBar){
			ff.createLayer(strLayer + "Drag", x, a, ff.barScroll.intPicWidth, 7, "<img name='" + strLayer + "DragPic' src='" + ff.barScroll.urlBarPic + "' border='0'>")
			if(ff.ie) document.ondragstart = function(){ return false}
			ff.catchMouseEvent("mousedown", "ff.barScroll.startDrag", strLayer+"Drag")	
		}
	}
}

ff.barScroll.scroll = function(strLayer, perc) {
	ff.barScroll[strLayer].curpos = parseInt(perc * ff.barScroll[strLayer].maxscroll)

	var scrollHeight = ff.barScroll[strLayer].h - 3*ff.barScroll.intArrowHeight

	ff.moveTo(strLayer+"Drag", null, perc * (scrollHeight-ff.barScroll.intBarHeight) + ff.barScroll[strLayer].y + ff.barScroll.intArrowHeight)
	ff.moveTo(strLayer, null, ff.barScroll[strLayer].y - ff.barScroll[strLayer].curpos)
	ff.clip(strLayer, ff.barScroll[strLayer].curpos, ff.getW(strLayer), ff.barScroll[strLayer].curpos+ff.barScroll[strLayer].h,0)
}

ff.barScroll.arrow = function(strLayer, direction){
	if(ff.getObj(strLayer)){
		ff.barScroll[strLayer].curpos -= direction*ff.barScroll.scrollSpeed
		if(ff.barScroll[strLayer].curpos<0) ff.barScroll[strLayer].curpos = 0
		else if(ff.barScroll[strLayer].curpos>ff.barScroll[strLayer].maxscroll) ff.barScroll[strLayer].curpos = ff.barScroll[strLayer].maxscroll

		ff.barScroll.scroll(strLayer, ff.barScroll[strLayer].curpos / ff.barScroll[strLayer].maxscroll)
		ff.barScroll.timer = setTimeout("ff.barScroll.arrow('" + strLayer + "', " + direction + ")", 30)
	}
}

ff.barScroll.startDrag = function(evt){
	if(ff.ie) var targetID = window.event.srcElement.parentElement.id
	else if(ff.ns4 || ff.w3c) var targetID = evt.target.name
	ff.barScroll.curDrag = targetID.substr(0,targetID.indexOf("Drag"))
	ff.barScroll.deltaY = ff.getEventY(evt) - ff.getY(ff.barScroll.curDrag + "Drag")
	ff.catchMouseEvent("mousemove", "ff.barScroll.drag")	
	ff.catchMouseEvent("mouseup", "ff.barScroll.stopDrag")
	return false
}

ff.barScroll.stopDrag = function(evt){
	ff.catchMouseEvent("mousemove", "ff.barScroll.drag", null, true)	
	ff.catchMouseEvent("mouseup", "ff.barScroll.stopDrag", null, true)
}

ff.barScroll.drag = function(evt){
	var topY = ff.getEventY(evt) - ff.barScroll.deltaY 
	var scrollTop = ff.barScroll[ff.barScroll.curDrag].y + ff.barScroll.intArrowHeight
	var scrollHeight = ff.barScroll[ff.barScroll.curDrag].h - 2*ff.barScroll.intArrowHeight
		
	var scrollBottom = scrollTop + ff.barScroll[ff.barScroll.curDrag].h - 2*ff.barScroll.intArrowHeight - ff.barScroll.intBarHeight

	if(topY>=scrollTop && topY<=scrollBottom) var scrollPerc = (topY - scrollTop) / (scrollHeight-ff.barScroll.intBarHeight)
	else if(topY<scrollTop) var scrollPerc = 0
	else if(topY>scrollBottom) var scrollPerc = 1

	ff.barScroll.scroll(ff.barScroll.curDrag, scrollPerc)
}

ff.barScroll.click = function(evt){
	if(ff.ie) var targetID = window.event.srcElement.parentElement.id
	else if(ff.ns4 || ff.w3c) var targetID = evt.target.name
	targetID = targetID.substr(0, targetID.length-5)
	var clickY = ff.getEventY(evt) - ff.barScroll[targetID].y - ff.barScroll.intArrowHeight
	var scrollHeight = ff.barScroll[targetID].h - 2*ff.barScroll.intArrowHeight
	ff.barScroll.scroll(targetID, clickY / scrollHeight)
}