if(typeof(p7) == "undefined") var p7 = {}
if(typeof(p7.svg) == "undefined") p7.svg = {}
p7.svg.dragdrop = function(evt){	
	if(evt.type=="mousedown"){
		document.documentElement.addEventListener("mousemove", p7.svg.dragdrop, 0)
		document.documentElement.addEventListener("mouseup", p7.svg.dragdrop, 0)
		p7.svg.dragElement = {}
		p7.svg.dragElement.elm = evt.currentTarget
		p7.svg.dragElement.elm.draging = true
		var tr = p7.svg.getTranslate(evt.currentTarget)
		var scale = document.documentElement.currentScale
		p7.svg.dragElement.dx = evt.clientX/scale - tr.x
		p7.svg.dragElement.dy = evt.clientY/scale - tr.y
	}
	else if(evt.type=="mousemove"){
		var scale = document.documentElement.currentScale
		p7.svg.setTranslate(p7.svg.dragElement.elm, evt.clientX/scale - p7.svg.dragElement.dx, evt.clientY/scale - p7.svg.dragElement.dy)
	}
	else{
		p7.svg.dragElement.elm.draging = false
		document.documentElement.removeEventListener("mousemove", p7.svg.dragdrop, 0)
		document.documentElement.removeEventListener("mouseup", p7.svg.dragdrop, 0)
		p7.svg.dragElement = null
	}
}

p7.svg.getTranslate = function(node){
	var tr = node.getAttribute("transform")
	var re = /translate\(([^\,]*)\,\s*([^\)]*)\)/
	re.test(tr)
	return {x:RegExp.$1*1, y:RegExp.$2*1}
}

p7.svg.setTranslate = function(node, x, y){
	var tr = node.getAttribute("transform")
	var re = /translate\([^\)]*\)/
	var s = "translate(" + x + "," + y + ")"
	tr = tr.replace(re, s)
	node.setAttribute("transform", tr)
}

p7.svg.setRotate = function(node, a){
	var tr = node.getAttribute("transform")
	var re = /rotate\([^\)]*\)/
	var s = "rotate(" + a + ")"
	tr = tr.replace(re, s)
	node.setAttribute("transform", tr)
}