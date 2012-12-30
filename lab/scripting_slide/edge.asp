<%@language=JScript%>
<%
Response.ContentType = "image/svg+xml"
%><?xml version="1.0" standalone="no"?>
<%
var css = String(Request.querystring("css"))
if(css == "undefined" || css == "") css = "styles.css"
%><?xml-stylesheet href="<%=css%>" type="text/css"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
    "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg xml:lang="en" xmlns="http://www.w3.org/2000/svg">
<title>Move stuff to the edges</title>

<defs>
	<marker id="arrow"
      viewBox="0 0 10 10" refX="0" refY="5" 
      markerUnits="strokeWidth"
      markerWidth="4" markerHeight="3"
      orient="auto">
		<path d="M 0 0 L 10 5 L 0 10 z" />
	</marker>
</defs>


<rect id="bgRect" x="-1000000" y="-1000000" width="10000000%" height="10000000%" />

<g transform="translate(5,5)">
	<title>Diagram of the move to edge services</title>
	<desc>Some text describing this diagram</desc>
	<circle cx="100" cy="100" r="40" />
	<circle cx="100" cy="100" r="100" />

	<line x1="70" y1="70" x2="33" y2="33" marker-end="url(#arrow)" />
	<line x1="130" y1="130" x2="167" y2="167" marker-end="url(#arrow)" />
	<line x1="70" y1="130" x2="33" y2="167" marker-end="url(#arrow)" />
	<line x1="130" y1="70" x2="167" y2="33" marker-end="url(#arrow)" />

	<text x="100" y="100" text-anchor="middle">
		<tspan>Centralized</tspan>
		<tspan x="100" dy="1em">server</tspan>
	</text>

	<text x="200" y="45" text-anchor="middle">
		Edge
	</text>
</g>

</svg>