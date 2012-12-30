if(typeof(p7) == "undefined") var p7 = {}
p7.dragdrop = function(evt){	
	if(evt.type=="mousedown"){
		document.documentElement.addEventListener("mousemove", p7.dragdrop, 0)
		document.documentElement.addEventListener("mouseup", p7.dragdrop, 0)
		p7.dragElement = {}
		p7.dragElement.elm = evt.currentTarget
		var tr = p7.getTranslate(evt.currentTarget)
		var scale = document.documentElement.currentScale
		p7.dragElement.dx = evt.clientX/scale - tr.x
		p7.dragElement.dy = evt.clientY/scale - tr.y
	}
	else if(evt.type=="mousemove"){
		var scale = document.documentElement.currentScale
		p7.dragElement.elm.setAttribute("x", evt.clientX/scale - p7.dragElement.dx)
		p7.dragElement.elm.setAttribute("y", evt.clientY/scale - p7.dragElement.dy)
	}
	else{
		document.documentElement.removeEventListener("mousemove", p7.dragdrop, 0)
		document.documentElement.removeEventListener("mouseup", p7.dragdrop, 0)
		p7.dragElement = null
	}
}

p7.getTranslate = function(node){
	var tr = node.getAttribute("transform")
	var re = /translate\(([^\,]*)\,\s*([^\)]*)\)/
	re.test(tr)
	return {x:RegExp.$1*1, y:RegExp.$2*1}
}

p7.setTranslate = function(node, x, y){
	

}