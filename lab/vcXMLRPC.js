//
//    Copyright (C) 2000, 2001, 2002  Virtual Cowboys info@virtualcowboys.nl
//		
//		Author: Ruben Daniels <ruben@virtualcowboys.nl>
//		Version: 0.86
//		Date: 18-08-2001
//		Site: www.vcdn.org/Public/XMLRPC/
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


Object.prototype.toXMLRPC = function(){
	var wo = this.valueOf();
	
	if(wo.toXMLRPC == this.toXMLRPC){
		retstr = "<struct>";
		
		for(prop in this){
			if(typeof wo[prop] != "function"){
				retstr += "<member><name>" + prop + "</name><value>" + XMLRPC.getXML(wo[prop]) + "</value></member>";
			}
		}
		retstr += "</struct>";
		
		return retstr;
	}
	else{
		return wo.toXMLRPC();
	}
}

String.prototype.toXMLRPC = function(){
	//<![CDATA[***your text here***]]>
	return "<string><![CDATA[" + this.replace(/\]\]/g, "] ]") + "]]></string>";//.replace(/</g, "&lt;").replace(/&/g, "&amp;")
}

Number.prototype.toXMLRPC = function(){
	if(this == parseInt(this)){
		return "<int>" + this + "</int>";
	}
	else if(this == parseFloat(this)){
		return "<double>" + this + "</double>";
	}
	else{
		return false.toXMLRPC();
	}
}

Boolean.prototype.toXMLRPC = function(){
	if(this) return "<boolean>1</boolean>";
	else return "<boolean>0</boolean>";
}

Date.prototype.toXMLRPC = function(){
	//Could build in possibilities to express dates 
	//in weeks or other iso8601 possibillities
	//hmmmm ????
	//19980717T14:08:55
	return "<dateTime.iso8601>" + doYear(this.getUTCYear()) + doZero(this.getMonth()) + doZero(this.getUTCDate()) + "T" + doZero(this.getHours()) + ":" + doZero(this.getMinutes()) + ":" + doZero(this.getSeconds()) + "</dateTime.iso8601>";
	
	function doZero(nr) {
		nr = String("0" + nr);
		return nr.substr(nr.length-2, 2);
	}
	
	function doYear(year) {
		if(year > 9999 || year < 0) 
			XMLRPC.handleError(new Error("Unsupported year: " + year));
			
		year = String("0000" + year)
		return year.substr(year.length-4, 4);
	}
}

Array.prototype.toXMLRPC = function(){
	var retstr, isStruct = false;
	
	for(prop in this){
		if(prop != parseInt(prop)){
			isStruct = true;
			break;
		}
	}
	
	if(isStruct){
		retstr = "<struct>";
		
		for(prop in this){
			retstr += "<member><name>" + prop + "</name><value>" + XMLRPC.getXML(this[prop]) + "</value></member>";
		}
		retstr += "</struct>";
	}
	else{
		var i;
		
		retstr = "<array><data>";
		
		for(i=0;i<this.length;i++){
			retstr += "<value>" + XMLRPC.getXML(this[i]) + "</value>";
		}
		retstr += "</data></array>";
	}
	
	return retstr;
}

function rpcCall(){
	//[optional receive, ] servername, functionname, args......
	var args = new Array(), i, as, fn, sn;
	
	if(typeof arguments[0] == "function"){
		i = 3;
		as = arguments[0];
		sn = arguments[1];
		fn = arguments[2];
	}
	else{
		i = 2;
		sn = arguments[0];
		fn = arguments[1];
	}
		
	for(i=i;i<arguments.length;i++){
		args.push(arguments[i]);
	}
	
	result = XMLRPC.call(sn, fn, args, as);
	return result;
}

XMLRPC = {
	servers : new Array(),
	
	addServer : function(serverAddress){
		var srv = {
			address : serverAddress,
			receive : new Array(),
			
			add : function(name, alias){
				//name, argument1, argument2, ....
				//only works synchronously this way, else use the call function
				XMLRPC.validateMethodName();
				this[(alias || name)] = new Function('var args = new Array(), i;for(i=0;i<arguments.length;i++){args.push(arguments[i]);}return XMLRPC.call("' + this.address + '", "' + name + '", args);');
			},
			
			call : function(name){
				var args = new Array(), i;
				
				for(i=0;i<arguments.length;i++){
					args.push(arguments[i]);
				}
				
				return XMLRPC.call(this.serverAddress, name, args, receive, async);
			}
		}
		this.servers.push(srv);
		
		return srv;
	},
	
	getNode : function(data, tree){
		var nc = 0;//nodeCount
		//node = 1
		if(data != null){
			for(i=0;i<data.childNodes.length;i++){
				if(data.childNodes[i].nodeType == 1){
					if(nc == tree[0]){
						data = data.childNodes[i];
						if(tree.length > 1){
							tree.shift();
							data = this.getNode(data, tree);
						}
						return data;
					}
					nc++
				}
			}
		}
		
		return false;
	},
	
	toObject : function(data){
		var ret, i;
		switch(data.tagName){
			case "string":
				return (data.firstChild) ? new String(data.firstChild.nodeValue) : "";
				break;
			case "int":
			case "i4":
			case "double":
				return new Number(data.firstChild.nodeValue);//data.text);
				break;
			case "dateTime.iso8601":
				/*
				Have to read the spec to be able to completely 
				parse all the possibilities in iso8601
				07-17-1998 14:08:55
				19980717T14:08:55
				*/
				
				var sn = (isIE) ? "-" : "/";
				
				if(/^(\d{4})(\d{2})(\d{2})T(\d{2}):(\d{2}):(\d{2})/.test(data.firstChild.nodeValue)){;//data.text)){
	      		return new Date(RegExp.$2 + sn + RegExp.$3 + sn + 
	      							RegExp.$1 + " " + RegExp.$4 + ":" + 
	      							RegExp.$5 + ":" + RegExp.$6);
	      	}
	    		else{
	    			return new Date();
	    		}

				break;
			case "array":
				data = this.getNode(data, [0]);
				
				if(data && data.tagName == "data"){
					ret = new Array();
					
					var i = 0;
					while(child = this.getNode(data, [i++])){
      				ret.push(this.toObject(child));
					}
					
					return ret;
				}
				else{
					this.handleError(new Error("Malformed XMLRPC Message1"));
					return false;
				}
				break;
			case "struct":
				ret = new Array();
					
				var i = 0;
				while(child = this.getNode(data, [i++])){
					if(child.tagName == "member"){
						ret[this.getNode(child, [0]).firstChild.nodeValue] = this.toObject(this.getNode(child, [1]));
					}
					else{
						this.handleError(new Error("Malformed XMLRPC Message2"));
						return false;
					}
				}
				
				return ret;
				break;
			case "boolean":
				return Boolean(isNaN(parseInt(data.firstChild.nodeValue)) ? (data.firstChild.nodeValue == "true") : parseInt(data.firstChild.nodeValue))

				break;
			case "base64":
				return this.decodeBase64(data.firstChild.nodeValue);
				break;
			case "value":
				child = this.getNode(data, [0]);
				return (!child) ? data.firstChild.nodeValue : this.toObject(child);

				break;
			default:
				this.handleError(new Error("Malformed XMLRPC Message: " + data.tagName));
				return false;
				break;
		}
	},
	
	/*** Decode Base64 ******
	* Original Idea & Code by thomas@saltstorm.net
	* from Soya.Encode.Base64 [http://soya.saltstorm.net]
	**/
	decodeBase64 : function(sEncoded){
		// Input must be dividable with 4.
		if(!sEncoded || (sEncoded.length % 4) > 0)
		  return sEncoded;
	
		/* Use NN's built-in base64 decoder if available.
		   This procedure is horribly slow running under NN4,
		   so the NN built-in equivalent comes in very handy. :) */
	
		else if(typeof(atob) != 'undefined')
		  return atob(sEncoded);
	
	  	var nBits, i, sDecoded = '';
	  	var base64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
		sEncoded = sEncoded.replace(/\W|=/g, '');
	
		for(i=0; i < sEncoded.length; i += 4){
			nBits =
				(base64.indexOf(sEncoded.charAt(i))   & 0xff) << 18 |
				(base64.indexOf(sEncoded.charAt(i+1)) & 0xff) << 12 |
				(base64.indexOf(sEncoded.charAt(i+2)) & 0xff) <<  6 |
				base64.indexOf(sEncoded.charAt(i+3)) & 0xff;
			sDecoded += String.fromCharCode(
				(nBits & 0xff0000) >> 16, (nBits & 0xff00) >> 8, nBits & 0xff);
		}
	
		// not sure if the following statement behaves as supposed under
		// all circumstances, but tests up til now says it does.
	
		return sDecoded.substring(0, sDecoded.length -
		 ((sEncoded.charCodeAt(i - 2) == 61) ? 2 :
		  (sEncoded.charCodeAt(i - 1) == 61 ? 1 : 0)));
	},
	
	getObject : function(type, message){
		if(type == "HTTP"){
			if(isIE)
				obj = new ActiveXObject("microsoft.XMLHTTP"); 
			else if(isNS)
				obj = new XMLHttpRequest();
		}
		else if(type == "XMLDOM"){
			if(isIE){
				obj = new ActiveXObject("microsoft.XMLDOM"); 
				obj.loadXML(message)
			}else if(isNS){
				obj = new DOMParser();
				obj = obj.parseFromString(message, "text/xml");
			}
			if(self.DEBUG) alert(result + ": " + message);
			
		}
		else{
			this.handleError(new Error("Unknown Object"));
		}

		return obj;
	},
	
	validateMethodName : function(name){
		/*do Checking:
		
		The string may only contain identifier characters, 
		upper and lower-case A-Z, the numeric characters, 0-9, 
		underscore, dot, colon and slash. 
		
		*/
		if(/^[A-Za-z0-9\._\/:]*$/.test(name))
			return true
		else
			this.handleError(new Error("Incorrect method name"));
	},
	
	getXML : function(obj){
		if(typeof obj == "function"){
			this.handleError(new Error("Cannot Parse functions"));
		}else if(obj == null || obj == undefined || (typeof obj == "number" && !isFinite(obj)))
			return false.toXMLRPC();
		else
			return obj.toXMLRPC();
	},
	
	handleError : function(e){
		if(!this.onerror || !this.onerror(e)){
			//alert("An error has occured: " + e.message);
			throw e;
		}
		this.stop = true;
	},
	
	queue : new Array(),
	getID : function(){
		do{
			id = Math.round(Math.random()*1000);
		}while(this.queue[id])

		return id;
	},
	
	cancel : function(id){
		//You can only cancel a request when it was executed async
		if(!this.queue[id]) return false;
		
		this.queue[id][0].abort();
		return true;
	},
	
	routeServer : "http://www.vcdn.org/cgi-bin/rpcroute.cgi",
	routing : "auto",
	call : function(serverAddress, functionName, args, receive){
		//default is sync
		this.validateMethodName();
		
		//Check for security maybe it shouldn't be called here
		var http = this.getObject("HTTP");
		
		if(!receive || isIE55 || isNS){;
			async = false;
		}
		else{
			async = true;
			id = this.getID();
			http.onreadystatechange = new Function("var id='" + id + "'; if(XMLRPC.queue[id][0].readyState == 4){XMLRPC.queue[id][0].onreadystatechange = function(){};XMLRPC.receive(id);}");
			this.queue[id] = [http, receive];
		}
		
		var srv;
		try{
			if(this.routing == "auto"){
				srv = serverAddress;
			}
			else if(this.routing == "active"){
				srv = this.routeServer;
			}
			else{
				srv = serverAddress;
			}
			
			http.open('POST', srv, async);
			http.setRequestHeader("User-Agent", "VCDN Javelin Library (" + navigator.userAgent + ")");
			http.setRequestHeader("Host", srv.replace(/^https?:\/{2}([:\[\]\-\w\.]+)\/?.*/, '$1'));
			//alert(srv.replace(/^https?:\/{2}([:\[\]\-\w\.]+)\/?.*/, '$1'));
			http.setRequestHeader("Content-type", "text/xml");
			http.setRequestHeader("X-Route-Request", serverAddress);
		}
		catch(e){
			if(this.routing == "auto"){
				this.routing = "active";
				//this.handleError(new Error("Rerouting Call.. Stand By...")); this.stop = false;
				return this.call(serverAddress, functionName, args, receive);
			}
			
			this.handleError(new Error("Could not sent XMLRPC Message (Reason: Access Denied on client)"));
			if(this.stop){
				this.stop = false;
				return false
			}
		}
		
		//Construct the message
		var message = '<?xml version="1.0"?><methodCall><methodName>' + functionName + '</methodName><params>';
   	for(i=0;i<args.length;i++){
   		message += '<param><value>' + this.getXML(args[i]) + '</value></param>';
		}
		message += '</params></methodCall>';
		
		var xmldom = this.getObject('XMLDOM', message);
		
		//send message
		try{
			http.send(xmldom);
		}
		catch(e){
			this.handleError(new Error("XMLRPC Message not Sent(Reason: " + e.message + ")"));
			if(this.stop){
				this.stop = false;
				return false
			}
		}
		
		if(!async && receive){
			return receive(this.processResult(http));
		}
		else if(receive)
			return id;
		else
			return this.processResult(http);
	},
	
	receive : function(id){
		var data
		
		if(this.queue[id]){
			data = this.processResult(this.queue[id][0]);
			this.queue[id][1](data);
			delete this.queue[id];
		}
		else{
			this.handleError(new Error("Error while processing queue"));
		}
	},
	
	processResult : function(http){
		//implement support for 302????
		if(http.status == 200){

		   //parse incoming message
		   dom = http.responseXML;
		   if(self.DEBUG) alert(dom.xml);

		   if(dom){
		   	var rpcErr, main;

		   	//Check for XMLRPC Errors
		   	rpcErr = dom.getElementsByTagName("fault");
		   	if(rpcErr.length > 0){
		   		rpcErr = this.toObject(rpcErr[0].firstChild);
		   		this.handleError(new Error(rpcErr.faultCode, rpcErr.faultString));
		   		return false
		   	}

		   	//handle method result
		   	main = dom.getElementsByTagName("param");
		      if(main.length == 0)
		      	this.handleError(new Error("Malformed XMLRPC Message"));
				data = this.toObject(this.getNode(main[0], [0]));

				//handle receiving
				if(this.onreceive) this.onreceive(data);
				return data;
		   }
		   else{
		  		this.handleError(new Error("Malformed XMLRPC Message"));
			}
		}
		else{
			this.handleError(new Error("HTTP Exception: (" + http.status + ") " + http.statusText + "\n\n" + http.responseText));
		}
	}
}

//Smell something
ver = navigator.appVersion;
app = navigator.appName;
isNS = Boolean(navigator.productSub)
//moz_can_do_http = (parseInt(navigator.productSub) >= 20010308)

isIE = (ver.indexOf("MSIE 5") != -1 || ver.indexOf("MSIE 6") != -1) ? 1 : 0;
isIE55 = (ver.indexOf("MSIE 5.5") != -1) ? 1 : 0;

(!isNS && !isIE) ? isOTHER = 1 : 0;
