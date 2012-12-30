<%@language=Jscript%>
<html>
<head>
	<title>Untitled</title>
</head>

<body>

<%
shoutcast = {}

shoutcast.errorText = "Nothing playing right now"
shoutcast.getCurrentSong = function(){
	var oDoc = Server.CreateObject("Microsoft.XMLDOM")
	oDoc.async = false
	oDoc.load("http://194.236.60.30:8080/admin.cgi?pass=knurra&mode=viewxml")

	var ret = shoutcast.errorText
	if(oDoc.parseError == 0){
		var oNode = oDoc.selectSingleNode("/SHOUTCASTSERVER/SONGHISTORY/SONG/TITLE")
		if(oNode) ret = oNode.firstChild.nodeValue
	}
	oDoc = null
	return ret
}

shoutcast.getCurrentSongAsHTML = function(){
	var song = this.getCurrentSong()
	if(song == this.errorText) return this.errorText
	else{
		s = "playing right now:<br />"
		s += song
		s += "<a href='http://194.236.60.30:8080/listen.pls'>listen!</a>"
		return s
	}
}

Response.write(shoutcast.getCurrentSongAsHTML())

%>

</body>
</html>
