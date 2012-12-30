<%@language=JScript%>
<html>
<head>
	<title>Untitled</title>
</head>

<body>
<%
function retriveMsg(nr){
	var http = new ActiveXObject("Microsoft.XMLHTTP")
	var sUrl = "http://groups.yahoo.com/group/svg-developers/message/12170"
//	var sUrl = "http://groups.yahoo.com/"
	http.open("GET", sUrl, false)
	http.send("")
	return http.responseText
}

Response.write(retriveMsg())
%>
	
	
</body>
</html>
