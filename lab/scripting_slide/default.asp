<%@language=JScript%>
<%
var css = String(Request.querystring("css"))
if(css == "undefined" || css == "") css = "styles.css"
%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Slides for Dave</title>
	
	<style type="text/css">@import url(<%=css%>);</style>
</head>

<body>

<div id="logo"></div>

<div id="slideContent">
	<h1>Slides for Dave</h1>
	<ul>
		<li>Dave Winer wanted an CSS version of his slides.</li>
		<li>This example integrates XHTML and SVG and uses the same CSS to style them both<br />
			<embed src="edge.asp?css=<%=css%>&ie=.svg" type="image/svg+xml" width="250" height="210" /></li>
		<li>There are two stylesheets, <a href="default.asp">mine</a> and <a href="default.asp?css=daves.css">Daves</a></li>
	</ul>
</div>

<div id="controller">
	<span>8 of 11</span>
	<a href="http://radio.weblogs.com/0001015/slides/InfoWorld/slide0007.html">Prev</a>
	<a href="http://radio.weblogs.com/0001015/slides/InfoWorld/slide0009.html">Next</a>
	<a href="http://radio.weblogs.com/0001015/slides/InfoWorld/index.html">Home</a>
</div>

</body>
</html>
