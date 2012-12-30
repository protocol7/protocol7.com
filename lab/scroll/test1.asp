<%

Function addScroll(strLayerName, xPos, yPos, width, height)
	Dim str, xOffset
	xOffset = 10
	str = "<div style=""position:absolute; left: " & (xPos+width+xOffset) & "px; top: " & (yPos) & "px;""><a href=""#"" onmouseover=""ff.scrollWithArrows('" & strLayerName & "', 1)"" onmouseout=""clearTimeout(ff.scrollBarTimer)""><img src=""arrow_up.gif"" border=""0""></a></div>" & VBNewline
	str = str & "<div style=""position:absolute; left: " & (xPos+width+xOffset) & "px; top: " & (yPos+height-11) & "px;""><a href=""#"" onmouseover=""ff.scrollWithArrows('" & strLayerName & "', -1)"" onmouseout=""clearTimeout(ff.scrollBarTimer)""><img src=""arrow_down.gif"" border=""0""></a></div>" & VBNewline
	str = str & "<div style=""position:absolute; left: " & (xPos+width+xOffset+1) & "px; top: " & (yPos+11) & "px;""><img src='bar_bg.gif' width=9 height=" & (height-22) & "></div>" & VBNewline
	str = str & "<div id=""" & strLayerName & "Drag"" style=""position: absolute; top: " & (yPos+11) & "px; left: " & (xPos+width+xOffset) & "px;""><img name=""" & strLayerName & "DragPic"" src=""bar.gif"" border=""0""></div>" & VBNewline
	
	addScroll = str
End function
%>

<html>
<head>
	<title>Untitled</title>

<script language="javascript" type="text/javascript" src="/clvdev/inc_file/clv/functions.js"></script>
</head>

<body scroll=no onload="ff.initScroll('kalle');ff.initScroll('roffe');">

<div id="kalle" style="position: absolute; top: 200px; left: 200px; width: 200px; clip:rect(0 200 300 0);">
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum Et harumd und dereud facilis est er expedit distinct. Nam liber te conscient to factor tum poen legum odioque civiuda. Et tam neque pecun modut est neque nonor et imper ned libidig met, consectetur adipiscing elit, sed ut labore et dolore magna aliquam Bis nostrud exercitation ullam mmodo consequet. Duis aute in voluptate velit esse cillum dolore eu fugiat nulla pariatur. At vver eos et accusam dignissum qui blandit est praesent luptatum delenit aigue excepteur sint occae. Et harumd dereud facilis est er expedit distinct. Nam libe soluta nobis eligent optio est congue nihil impedit doming id
</div>

<%=addScroll("kalle", 200, 200, 200, 300)%>

<div id="roffe" style="position: absolute; top: 20px; left: 200px; width: 100px; clip:rect(0 100 300 0);">
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum Et harumd und dereud facilis est er expedit distinct. Nam liber te conscient to factor tum poen legum odioque civiuda. Et tam neque pecun modut est neque nonor et imper ned libidig met, consectetur adipiscing elit, sed ut labore et dolore magna aliquam Bis nostrud exercitation ullam mmodo consequet. Duis aute in voluptate velit esse cillum dolore eu fugiat nulla pariatur. At vver eos et accusam dignissum qui blandit est praesent luptatum delenit aigue excepteur sint occae. Et harumd dereud facilis est er expedit distinct. Nam libe soluta nobis eligent optio est congue nihil impedit doming id
</div>

<%=addScroll("roffe", 200, 20, 100, 300)%>



<script language="javascript" type="text/javascript">

// code for the nice scrollbar
// written by nikgus
ff.startScrollBar = function(evt){
	if(ff.ie){
		var e = window.event.srcElement.parentElement.id.indexOf("Drag")
		ff.scrollDrag.curDrag = window.event.srcElement.parentElement.id.substr(0,e)
	}
	else if(ff.ns4 || ff.w3c){
		var e = evt.target.name.indexOf("DragPic")
		ff.scrollDrag.curDrag = evt.target.name.substr(0,e)
	}
	ff.scrollDrag.deltaY = ff.getEventY(evt) - ff.getY(ff.scrollDrag.curDrag + "Drag")
	return false
}

ff.stopScrollBar = function(){
	clearTimeout(ff.scrollTimer)
	ff.scrollDrag.curDrag = ""
}

ff.dragScrollBar = function(evt){
	if(ff.scrollDrag.curDrag != ""){
		var curDrag = ff.scrollDrag.curDrag
		var mouseY = ff.getEventY(evt)
		var topY = mouseY - ff.scrollDrag.deltaY
		var scrollTop = ff.scrollDrag[curDrag].y + ff.scrollDrag.arrowH
		var scrollHeight = ff.scrollDrag[curDrag].h - 2*ff.scrollDrag.arrowH
		
		var scrollBottom = scrollTop + ff.scrollDrag[curDrag].h - 2*ff.scrollDrag.arrowH - ff.scrollDrag.barH

		if(topY>=scrollTop && topY<=scrollBottom) var scrollPerc = (topY - scrollTop) / (scrollHeight-ff.scrollDrag.barH)
		else if(topY<scrollTop) var scrollPerc = 0
		else if(topY>scrollBottom) var scrollPerc = 1

		ff.scrollLayer2(curDrag, scrollPerc)
	}
}

ff.scrollBarTimer = null
ff.scrollWithArrows = function(strLayer, direction){
	if(ff.getObj(strLayer)){
		ff.scrollLayer2(strLayer, null, direction*3)
		ff.scrollBarTimer = setTimeout("ff.scrollWithArrows('" + strLayer + "', " + direction + ")", 30)
	}
}


ff.scrollLayer2 = function(strLayer, perc, speed) {
	if(typeof(speed)!="undefined"){
		ff.scrollDrag[strLayer].curpos -= speed

		if(ff.scrollDrag[strLayer].curpos<0) ff.scrollDrag[strLayer].curpos = 0
		else if(ff.scrollDrag[strLayer].curpos>ff.scrollDrag[strLayer].maxscroll) ff.scrollDrag[strLayer].curpos = ff.scrollDrag[strLayer].maxscroll

		perc = ff.scrollDrag[strLayer].curpos / ff.scrollDrag[strLayer].maxscroll
	}
	else ff.scrollDrag[strLayer].curpos = parseInt(perc * ff.scrollDrag[strLayer].maxscroll)

	var scrollTop = ff.scrollDrag[strLayer].y + ff.scrollDrag.arrowH
	var scrollHeight = ff.scrollDrag[strLayer].h - 2*ff.scrollDrag.arrowH
	var barY = perc * (scrollHeight-ff.scrollDrag.barH) + scrollTop

	ff.moveTo(strLayer+"Drag", null, barY)
	ff.moveTo(strLayer, null, ff.scrollDrag[strLayer].y - ff.scrollDrag[strLayer].curpos)
	ff.clip(strLayer, ff.scrollDrag[strLayer].curpos,ff.getW(strLayer) , ff.scrollDrag[strLayer].curpos+ff.scrollDrag[strLayer].h,0)
}



ff.initScroll = function(strLayer){
	if(!ff.scrollDrag) ff.scrollDrag = new Object()
	
	ff.scrollDrag.arrowH = 11
	ff.scrollDrag.barH = 21
	ff.scrollDrag.curDrag = ""
	ff.scrollDrag[strLayer] = new Object()
	ff.scrollDrag[strLayer].y = ff.getY(strLayer)
	ff.scrollDrag[strLayer].h = ff.getH(strLayer)
	ff.scrollDrag[strLayer].sh = ff.getScrollH(strLayer)
	ff.scrollDrag[strLayer].maxscroll = ff.scrollDrag[strLayer].sh - ff.scrollDrag[strLayer].h
	ff.scrollDrag[strLayer].curpos = 0

	if(ff.ie) document.ondragstart = function(){ return false}
	
	ff.catchMouseEvent("mousedown", "ff.startScrollBar", strLayer+"Drag")	
	ff.catchMouseEvent("mousemove", "ff.dragScrollBar")	
	ff.catchMouseEvent("mouseup", "ff.stopScrollBar")
}
// end of - code for the nice scrollbar

</script>

</body>
</html>
