if(typeof(p7)!="object") p7 = new Object()
p7.js2xmlrpc = function(o){
	if(typeof(o) == "function"){
		return false
		this.error = "Cannot Parse functions"
//		this.handleError(new Error("Cannot Parse functions")
	}
	else if(o == null || o == undefined || (typeof(obj) == "number" && !isFinite(o))) return false.toXMLRPC();
	else return o.toXMLRPC();
}

Object.prototype.toXMLRPC = function(){
	var wo = this.valueOf();

	if(wo.toXMLRPC == this.toXMLRPC){
		retstr = "<struct>";
		
		for(prop in this){
			if(typeof wo[prop] != "function"){
				retstr += "<member><name>" + prop + "</name><value>" + p7.js2xmlrpc(wo[prop]) + "</value></member>";
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
	var retstr = "<array><data>";
	for(var i=0;i<this.length;i++) retstr += "<value>" + p7.js2xmlrpc(this[i]) + "</value>";
	return retstr + "</data></array>";
}