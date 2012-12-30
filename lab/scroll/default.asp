<html>
<head>
<title>Scroll</title>
<script language="javascript" type="text/javascript" src="standlibs.js"></script>
</head>

<body onload="ff.barScroll.init('maintext1');" leftmargin=0 topmarging=0 topmargin=0>
<div style="position:absolute; top: 50px; left: 40px;z-index:10;">
<a href="javascript:ff.barScroll.gotoAnchor('maintext1', 'anc1')">gå till anchor 1</a><br>
<a href="javascript:ff.barScroll.gotoAnchor('maintext1', 'anc2')">gå till anchor 2</a><br>
<a href="javascript:ff.barScroll.reset('maintext1')">reset</a><br>
<a href="javascript:ff.barScroll.show('maintext1')">show</a><br>
<a href="javascript:ff.barScroll.hide('maintext1')">hide</a><br>
</div>


<div id="maintext1" style="position:absolute; top:135px; left:20px; width:330px; z-index:5; clip:rect(0 330 130 0);">
<img src="evaonice.jpg" border="0" alt=""><br><br>
<img src="knapp.gif" name="anc1">
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco lab
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab eniam, quis nostrud exercitation ullamco lab Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco lab
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab
<img src="knapp.gif" name="anc2">
</div>

<script language="javaScript" type="text/javascript">

ff.barScroll.reset = function(strLayer){
	var objLayer = ff.getObj(strLayer)
	if(objLayer){
		ff.moveTo(strLayer, null, ff.barScroll[strLayer].y)
		ff.clip(strLayer, 0,ff.getW(strLayer),ff.barScroll[strLayer].h,0)
		if(ff.getObj(strLayer)+"Drag") ff.moveTo(strLayer+"Drag", null, ff.barScroll[strLayer].y + ff.barScroll.intArrowHeight)
		ff.barScroll[strLayer].curpos = 0
	}
}

ff.barScroll.show = function (strLayer){
	ff.show(strLayer + "ArrowUp")
	ff.show(strLayer + "ArrowDown")
	ff.show(strLayer + "BarBg")
	ff.show(strLayer + "Drag")
}

ff.barScroll.hide = function(strLayer){
	ff.hide(strLayer + "ArrowUp")
	ff.hide(strLayer + "ArrowDown")
	ff.hide(strLayer + "BarBg")
	ff.hide(strLayer + "Drag")
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

</script>
</body>
</html>
