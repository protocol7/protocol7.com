if(typeof(p7)!="object") p7 = new Object()

p7.xmlrpc = function(url){
	this.url = url
	this.proxy = null
	
	if(typeof(p7xmlrpcObjects)=="undefined") p7xmlrpcObjects = []
	this.rndID = "p7xmlrpc" + Math.round(Math.random()*10000)
	p7xmlrpcObjects[this.rndID] = this
	this.cue = new p7.cue()
	this.activeItem = null
	
	
	this.addFunction = function(name, callback, alias){
		this[(alias || name) + "callback"] = callback
alert(1)
		this[(alias || name)] = new Function('var args = new Array(), i;for(i=0;i<arguments.length;i++){args.push(arguments[i]);}; alert(7);this.cue.add({name:name, args:args}); alert(8);this.call()')
alert(2)
	}
	
	this.call = function(){
		if(this.cue.getLength>0){
			var o = this.cue.pop()
			this.activeItem = o
			var name = o.name
			var args = o.args

			var message = '<?xml version="1.0"?>'
			if(this.proxy) message += '<proxy url="' + this.url + '">'
			message += '<methodCall><methodName>' + name + '</methodName><params>';
		   	for(i=0;i<args.length;i++) message += '<param><value>' + this.js2xmlrpc(args[i]) + '</value></param>';
			message += '</params></methodCall>';
			if(this.proxy) message += '</proxy>'

			//var fp = new Function('o', 'if(o.success){var x = p7xmlrpc2js(o.content);p7xmlrpcCallbacks["' + this.rndID + name + '"](x)}')

			this.post = new ActiveXObject("Microsoft.XMLHTTP")
			this.post.open('POST', this.proxy || this.url, false)
			this.post.setRequestHeader("User-Agent", "p7.xmlrpc 0.1 (" + navigator.userAgent + ")");
			this.psetRequestHeader("Content-type", "text/xml");
			var oDoc = new ActiveXObject("Msxml2.DOMDocument.3.0")
			oDoc.loadXML(message)

			this.post.send(oDoc)
//			setInterval("p7xmlRpcHandleResponse(" + this.rndID + ")", 200)
			eval("p7xmlRpcHandleResponse(" + this.rndID + ")")

		}
	}

	p7xmlRpcHandleResponse = function(rndID){
		var o = p7xmlrpcObjects[rndID]
		alert(o)
alert(this.status)
		if(this.status == 200){

/*		if(ost.status==200){
alert(oPost.responseText)
			oDoc.loadXML(oPost.responseText)
			return p7.xmlrpc2js(oDoc.documentElement.firstChild.firstChild.firstChild)
		}
*/

		
		}
	
	}
	p7xmlrpc2js = function(sXML){
//alert(sXML)
		var oXML = parseXML(sXML)
		
		return p7.xmlrpc2js(oXML.documentElement.firstChild.firstChild.firstChild)
	}
	
	this.js2xmlrpc = p7.js2xmlrpc
}

p7.cue = function(){
	this.items = []
}

p7.cue.add = function(o){
	this.items.push(o)
}

p7.cue.getLength = function(){
	return this.items.length
}


p7.cue.pop = function(){
	return this.items.pop()
}