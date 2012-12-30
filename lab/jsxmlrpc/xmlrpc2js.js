if(typeof(p7)!="object") p7 = new Object()
p7.xmlrpc2js = function(data){
	var ret, i;
	var tn = data.tagName.toLowerCase()
	if(tn=="string") return (data.firstChild) ? new String(data.firstChild.nodeValue) : "";
	else if(tn=="int" || tn=="i4" || tn=="double") return (data.firstChild) ? new Number(data.firstChild.nodeValue) : 0;
	else if(tn=="dateTime.iso8601"){
		/*
		Have to read the spec to be able to completely 
		parse all the possibilities in iso8601
		07-17-1998 14:08:55
		19980717T14:08:55
		*/
				
		var sn = (isIE) ? "-" : "/";
			
		if(/^(\d{4})(\d{2})(\d{2})T(\d{2}):(\d{2}):(\d{2})/.test(data.firstChild.nodeValue)){;//data.text)){
     		return new Date(RegExp.$2 + sn + RegExp.$3 + sn + RegExp.$1 + " " + RegExp.$4 + ":" + RegExp.$5 + ":" + RegExp.$6);
      	}
   		else{
   			return new Date();
   		}
	}
	else if(tn=="array"){
		data = data.firstChild
		if(data && data.tagName == "data"){
			ret = new Array();
			var i = 0;
			for(var i = 0; i<data.childNodes.length; i++) ret.push(p7.xmlrpc2js(data.childNodes.item(i)));

			return ret;
		}
		else{
			this.error = "Malformed XMLRPC Message1"
			return false;
		}
	}
	else if(tn=="struct"){
		ret = {};
					
		var i = 0;
		for(var i = 0; i<data.childNodes.length; i++){
			var child = data.childNodes.item(i)
			if(child.tagName == "member"){
				ret[child.firstChild.firstChild.nodeValue] = this.xmlrpc2js(child.childNodes.item(1));
			}
			else{
				this.error = "Malformed XMLRPC Message2"
				return false;
			}
		}
		return ret;
	}
	else if(tn=="boolean") return Boolean(isNaN(parseInt(data.firstChild.nodeValue)) ? (data.firstChild.nodeValue == "true") : parseInt(data.firstChild.nodeValue))
	else if(tn=="base64"){
		this.error = "base64 not supported"
		return false
	}
	else if(tn=="value"){
		child = data.firstChild
		return (!child) ? ((data.firstChild) ? new String(data.firstChild.nodeValue) : "") : this.xmlrpc2js(child);
	}
	else{
		this.error = "Malformed XMLRPC Message3"
		return false;
	}
}
