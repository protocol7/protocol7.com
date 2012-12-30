<%@language=JScript%>
<%
Response.ContentType = "text/XML"

proxy = {}
proxy.xml = Server.CreateObject("MSXML2.DOMDocument")
proxy.xml.async=false
try{
	proxy.xml.load(Request)
	if(proxy.xml.parseError==0 && proxy.xml.documentElement){
		proxy.url = proxy.xml.documentElement.getAttribute("url")
		if(proxy.url){
			var xmlrpc = proxy.xml.documentElement.firstChild.xml
			if(xmlrpc){
				proxy.xmlrpc = '<?xml version="1.0"?>' + xmlrpc

				proxy.post = Server.CreateObject("MSXML2.ServerXMLHTTP")
				proxy.post.open("POST", proxy.url, false)

				proxy.post.setRequestHeader("Content-Type", "text/xml")
				proxy.post.send(proxy.xmlrpc)
				
				Response.write(proxy.post.responseText)
			}
			else{
				Response.write('<?xml version="1.0"?><error code="1002">Missing XMLRPC</error>')
			}
		}
		else{
			Response.write('<?xml version="1.0"?><error code="1001">Invalid or missing service URL</error>')
		}
	}
	else{
		Response.write('<?xml version="1.0"?><error code="1000">Invalid XML</error>')
	}
}
catch(e){
	Response.write('<?xml version="1.0"?><error code="1003">Error loading XML</error>')
}

%>