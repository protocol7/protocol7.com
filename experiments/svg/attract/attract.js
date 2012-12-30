function showPoint(p, color){
	var e = document.createElement("circle")
	e.setAttribute("stroke", "red")
	e.setAttribute("fill", "#" + color)
	e.setAttribute("cx", p.x)
	e.setAttribute("cy", p.y)
	e.setAttribute("r", 5)
	document.documentElement.appendChild(e)
}

function getCenterPoint(node, ctm){
	var p = document.documentElement.createSVGPoint()
	p.x = 35
	p.y = 35
	var ctm = ctm.multiply(node.getCTM())
	return p.matrixTransform(ctm)
}
  
function getTransformToElement(node) {
	var ctm = node.getCTM();
	var node = node.getParentNode();
	while ( node != document.documentElement ) {
		ctm  = node.getCTM().multiply(ctm);
		node = node.getParentNode();
	}
	return ctm;
}

function random(i){
	var r = Math.random()*i + 1
	return Math.round(r)
}

function makecell() {
	var e = document.getElementById("cell").cloneNode(true)
	var id = "cell" + String(celldepth++)
	e.setAttribute("id", id)

	var x = random(400) + 100
	var y = random(250) + 50
	var tr = "translate(" + x +", " + y + ") "

	e.x = x
	e.y = y
	e.vr = (random(3)+1)/2 
	if (Math.round(Math.random())) e.vr*=-1;

	e.seekHome = seekHome

	cList.push(e)
	e.vals = []

	e.numvals=random(3) + 1
	for (var i = 0; i < e.numvals; i++) {
		var v = document.getElementById("val").cloneNode(true)

		var x = 100*Math.cos(Math.PI / 180 * i * 360 / e.numvals ) - 35
		var y = 100*Math.sin(Math.PI / 180 * i * 360 / e.numvals ) - 35
		v.setAttribute("transform", "translate(" + x + ", " + y + ")")
		v.color = random(colors.length)
		v.setAttribute("fill", "#" + colors[v.color])
		e.vals.push(v)
		e.appendChild(v)
	}

	var scl = (random(80) + 20) /100
	e.rot = random(180)
	tr += "scale(" + scl + ") rotate(" + e.rot + ")"
	e.setAttribute("transform", tr)
	
	e.addEventListener("mousedown", p7.dragdrop, false)
	
	var e = document.documentElement.appendChild(e)
}