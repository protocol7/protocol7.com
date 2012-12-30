var cur = null
var timer = null
var maximizing = false
var vb = null
var svg = null
var orgVB = null

function _onload(){
	svg = document.getElementById("kalle")
	hist = document.getElementById("hist")
	go = document.getElementById("go")
	updateHistory("protocol7")
	orgVB = getViewBox(svg)
}


function _onclick(evt){
	maximizing = (cur != evt.target.parentNode)

	cur = evt.target.parentNode

	clearTimeout(timer)

	if(maximizing) zoomIn()
	else zoomOut()
}

function getCTMRelative(e, relTo){
	var p = e.parentNode
	var ctm = e.getCTM()

	while(p != relTo){
		ctm = p.getCTM().multiply(ctm)
		p = p.parentNode
	}
	return ctm
}

function getPosAndSize(e){
	v = {}
	var ctm = getCTMRelative(e, svg)
	var p1 = document.documentElement.createSVGPoint()
	p1.x = 0
	p1.y = 0

	p1 = p1.matrixTransform(ctm)
	v.x = p1.x
	v.y = p1.y

	var p2 = document.documentElement.createSVGPoint()
	p2.x = 81
	p2.y = 81
	p2 = p2.matrixTransform(ctm)	

	v.w = p2.x - p1.x
	v.h = p2.y - p1.y
	return v
}

function zoomOut(){
	
	var e = cur.parentNode
	vb = {}
	if(e.tagName == "svg") vb = orgVB
	else vb = getPosAndSize(e)
	
	cur = e
	updateHistory()
	animateToViewBox()
}

function updateHistory(back){
	var s = ""
	if(cur){
		var e = cur

		while(e.tagName != "svg"){
			var text = e.getElementsByTagName("text").item(0).firstChild.nodeValue
			s = text + " > " + s
			e = e.parentNode
		}
	}
	s = title + " > " + s
	hist.firstChild.nodeValue = s.substr(0, s.length-2)
	if(cur){
		var url = cur.getAttributeNS("http://www.protocol7.com/ns", "url")
		/*if(url){
			go.setAttributeNS("http://www.w3.org/1999/xlink", "href", url)
			go.setAttributeNS("visibility", "visible")
		}
		else{
			go.setAttributeNS("visibility", "hidden")
		}*/
	}
}

function zoomIn(){
	vb = getPosAndSize(cur)
	updateHistory()
	animateToViewBox()
}


function getViewBox(e){
	var v = e.getAttribute("viewBox").split(" ")
	return {x:v[0]*1, y:v[1]*1, w:v[2]*1, h:v[3]*1}
}

function animateToViewBox(){
	var svg = document.getElementById("kalle")
	var v = getViewBox(svg)
	var dx = vb.x - v.x
	var dy = vb.y - v.y
	var dw = vb.w - v.w
	var dh = vb.h - v.h

	var lim = 0.01
	if(Math.abs(dx)>lim || Math.abs(dy)>lim || Math.abs(dw)>lim || Math.abs(dh)>lim){
		var div = 5

		var x = v.x + dx/div
		var y = v.y + dy/div
		var w = v.w + dw/div
		var h = v.h + dh/div
		svg.setAttributeNS(null, "viewBox", x + " " + y + " " + w + " " + h)
		timer = setTimeout("animateToViewBox()", 25)
	}
}
