<html>
<head>
<title>Scroll</title>
<script language="javascript" type="text/javascript" src="functions.js"></script>
<script language="javascript" type="text/javascript" src="mainscript.js"></script>
	
</head>

<body onload="ff.initScroll('maintext1'), document.all['roffe'].scrollTop = 5000">

<div id="maintext1" style="position:absolute; top:35px; left:20px; width:330px; z-index:5; clip:rect(0 330 130 0);">
ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco lab
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab eniam, quis nostrud exercitation ullamco lab Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco lab
Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
Ut enim ad minim veniam, quis nostrud exercitation ullamco lab</div>

<div id='maintext1ArrowUp' style='position:absolute; left: 360px; top: 35px; z-index:9;visibility:hidden;'><a href='javascript:void(0)' onmouseover="ff.scrollWithArrows('maintext1', 1)" onmouseout="clearTimeout(ff.scrollBarTimer)"><img src='arrow_up.gif' border='0'></a></div>
<div id='maintext1ArrowDown' style='position:absolute; left: 360px; top: 154px; z-index:9;visibility:hidden;'><a href='javascript:void(0)' onmouseover="ff.scrollWithArrows('maintext1', -1)" onmouseout="clearTimeout(ff.scrollBarTimer)"><img src='arrow_down.gif' border='0'></a></div>
<div id='maintext1BarBg' style='position:absolute; left: 361px; top: 46px; clip:rect(0 9 108 0); z-index:7;visibility:hidden;'><img src='bar_bg.gif' width=9 height=108></div>
<div id='maintext1Drag' style='position: absolute; top: 46px; left: 360px; z-index:7;visibility:hidden;'><img name='maintext1DragPic' src='bar.gif' border='0'></div>

<div onscroll="_scr()" id="roffe" style="z-index:6;position:absolute; top:35px; left:20px; width: 370px; height:160px; overflow:scroll;">
<img src="spacer.gif" width="10000" height="10000" border="0" alt="">
</div>


<script language="javascript" type="text/javascript">
st = document.all["roffe"].style
st.scrollbar3dLightColor = "white"
st.scrollbarArrowColor = "white"
st.scrollbarBaseColor = "white"
st.scrollbarDarkShadowColor = "white"
st.scrollbarFaceColor = "white"
st.scrollbarHighlightColor = "white"
st.scrollbarShadowColor = "white"
st.scrollbarTrackColor = "white"

scTop = 5000

function _scr(){
	if(scTop>document.all["roffe"].scrollTop) ff.scrollLayer2("maintext1", null, 1)
	else ff.scrollLayer2("maintext1", null, -1)
	
	scTop = document.all["roffe"].scrollTop
}

</script>

</body>
</html>
