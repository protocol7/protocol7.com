
/************************
Standlibs
Developed by Framfab
************************/

var ff = new Object()
ff.version = parseFloat(navigator.appVersion)
ff.opera = (navigator.appName=="Opera") ? 1:0
ff.ie = ff.da = (document.all && !ff.opera) ? 1:0
ff.ns4 = ff.dl = (navigator.appName=="Netscape" && ff.version<5) ? 1:0
ff.w3c = (document.getElementById && !ff.da) ? 1:0
ff.win = (navigator.userAgent.toLowerCase().indexOf("win") > 0) ? 1:0
ff.mac = (navigator.userAgent.toLowerCase().indexOf("mac") > 0) ? 1:0







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

ff.show = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie||ff.w3c||ff.opera) objLayer.style.visibility = "visible";
		else if (ff.ns4) objLayer.visibility = "show";
	}
}








ff.hide = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie||ff.w3c||ff.opera) objLayer.style.visibility = "hidden";
		else if (ff.ns4) objLayer.visibility = "hide";
	}
}





ff.getX = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie||ff.w3c||ff.opera) return objLayer.offsetLeft;
		else if (ff.ns4) return objLayer.left;
	}
}













ff.getY = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie||ff.w3c||ff.opera) return objLayer.offsetTop;
		else if (ff.ns4) return objLayer.top;
	}
}













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



ff.getVis = function(strLayer){
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie||ff.w3c) return (objLayer.style.visibility=="hidden") ? false:true
		else if(ff.ns4) return !objLayer.hidden
	}
}








ff.getScrollH = function(strLayer) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie) return (ff.mac) ? objLayer.offsetHeight : objLayer.scrollHeight
		else if(ff.ns4) return objLayer.document.height
		else if(ff.w3c) return objLayer.offsetHeight 
	}
}



ff.moveBy = function(strLayer, dx, dy) {
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if (ff.ie||ff.opera) {
			if (dx) objLayer.style.pixelLeft += dx;
			if (dy) objLayer.style.pixelTop += dy;
		} 
		else if (ff.ns4) {
			if (dx) objLayer.left += dx;
			if (dy) objLayer.top += dy;
		} 
		else if (ff.w3c) {
			if (dx) objLayer.style.left = (parseInt(objLayer.style.left) + dx) + "px";
			if (dy) objLayer.style.top = (parseInt(objLayer.style.top) + dy) + "px";
		}
	}
}













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





ff.clipBy = function(strLayer,dt,dr,db,dl){
	var objLayer=ff.getObj(strLayer)
	if(objLayer){
		if(ff.ns4){
			var w=objLayer.clip.width
			var h=objLayer.clip.height
			objLayer.clip.top+=dt
			objLayer.clip.left+=dl
			objLayer.clip.height=h+db
			objLayer.clip.width=w+dr
		}
		else if(ff.ie||ff.w3c){
			var c=objLayer.style.clip.substr(5)
			c=c.substr(0,c.length-3).split("p")
			c[1] = c[1].substr(2)
			c[2] = c[2].substr(2)
			c[3] = c[3].substr(2)
			var t=dt+(c[0]*1)
			var r=dr+(c[1]*1)+dl
			var b=db+(c[2]*1)+dt
			var l=dl+(c[3]*1)
			objLayer.style.clip="rect("+t+"px "+r+"px "+b+"px "+l+"px)"
		}
	}
}

ff.preload = function(strName, strSrc) {
	eval(strName + " = new Image()");
	eval(strName+".src = '"+strSrc+"'");
}







ff.catchMouseEvent = function(strEventName, strHandlerFunction, strLayer){
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
			if(ff.ns4) objLayer.document["on"+strEventName] = eval(strHandlerFunction)
			else objLayer["on"+strEventName] = eval(strHandlerFunction)
		}
	}
	else document["on"+strEventName] = eval(strHandlerFunction)
}







ff.getImgPos = function(strImgName, blnParentPos) {
	var objImg=ff.getObj(strImgName)
	var objRet = new Object()
	if(objImg){
		var objParent
		if (ff.ns4) {
			objRet.x = objImg.x
			objRet.y = objImg.y
			if (!blnParentPos) {
				objParent = objImg.parentLayer
				while (objParent != window && objParent) {
					objRet.x += objParent.left
					objRet.y += objParent.top
					objParent = objParent.parentLayer
				}
			}
		} else {
			objRet.x = objImg.offsetLeft
			objRet.y = objImg.offsetTop
			if (ff.w3c) {
				objParent = objImg.parentNode
				if (objParent == document.body){
					objRet.x += document.body.offsetLeft
					objRet.y += document.body.offsetTop
				}
				else if (blnParentPos){
					objRet.x -= objParent.offsetLeft
					objRet.y -= objParent.offsetTop
				}
			} 
			else if (ff.ie) {
				objParent = objImg.offsetParent
				if (!blnParentPos) {
					objParent = (ff.ie) ? objImg.offsetParent : objImg.parentNode
					while (objParent != document.body) {
						objRet.x += objParent.offsetLeft
						objRet.y += objParent.offsetTop					
						objParent = objParent.offsetParent
					}
				}
			}
		}
	}	
	return objRet
}





ff.deleteLayer = function(strLayer){
	var objLayer = ff.getObj(strLayer)
	if(objLayer){
		if(ff.ie || ff.w3c){
			if(document.body.removeNode) objLayer.removeNode(1)
			else{
				objLayer.innerHTML = ""
				objLayer.outerHTML = ""
			}
		}
		else if(ff.ns4) ff.hide(strLayer)
	}
}


ff.swapImage = function(strTarget, strNewPic, strLayer){
	if(ff.ie){
		var objImage=document.images[strTarget]
		if(objImage) objImage.src=eval(strNewPic+".src")
	}
	else if(ff.ns4){
		if(strLayer){
			var objLayer=ff.getObj(strLayer)
			if(objLayer) objLayer.document.images[strTarget].src=eval(strNewPic+".src")
		}
		else document.images[strTarget].src=eval(strNewPic+".src")
	}
	else if(ff.w3c){
		var objImage=document.getElementsByName(strTarget)[0]
		if(objImage) objImage.src=eval(strNewPic+".src")
	}
}



ff.getEventY = function(evt){
		if(ff.ns4) return evt.pageY
		else if (ff.ie) return window.event.clientY
		else if (ff.w3c) return evt.clientY
}



ff.getEventX = function(evt){
		if(ff.ns4) return evt.pageX
		else if (ff.ie) return window.event.clientX
		else if (ff.w3c) return evt.clientX
}




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
	return ff.getObj(id)
}


