<%
Dim xObj, url
url = Request.querystring("xmlurl") & ""
'Response.write(url)
if url <> "" then
	Set xObj = Server.CreateObject("Microsoft.XMLDOM")
	xObj.async=false
	xObj.load(url)
	if xObj.parseError = 0 then
		Response.write(xObj.documentElement.xml)
	end if
end if
%>