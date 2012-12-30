if(typeof(p7) == "undefined") var p7 = {}
if(typeof(p7.xhtml) == "undefined") p7.xhtml = {}	
p7.xhtml.collapsable = {}

p7.xhtml.collapsable.init = function(){
	if(document.getElementsByTagName){
		var uls = document.getElementsByTagName("ul");
		for(var i = 0; i<uls.length; i++){
			var ul = uls[i];
			if(ul.className){
				var cn = " " + ul.className + " ";
				if(cn.indexOf(" collapsable ") > -1){
					// our ul got the class "collapsable" => init
					p7.xhtml.collapsable.initUl(ul);
										
				}
			}
		}
	}
}

p7.xhtml.collapsable.initUl = function(ul){
	for(var i = 0; i<ul.childNodes.length; i++){
		var li = ul.childNodes[i];
		if(li.nodeName.toLowerCase() == "li"){
			li.style.cursor = "pointer";
			li.onclick = function(){
				var childUl = this.getElementsByTagName("ul")[0];
				if(childUl.style.display == "none"){
					childUl.style.display = "";
				}
				else{
					childUl.style.display = "none";
				}
			}
			// li should contain one ul
			var childUl = li.getElementsByTagName("ul")[0];
			if(childUl){
				childUl.style.cursor = "default";
				childUl.onclick = function(e){
					if(typeof(e) == "undefined"){
						// IE event handling
						event.cancelBubble = true;
					}
					else{
						e.stopPropagation();
					}
				}
				childUl.style.display = "none";
			}
		}
	}
}

if(window.attachEvent){
	window.attachEvent("onload", p7.xhtml.collapsable.init);
}
else if(window.addEventListener){
	window.addEventListener("load", p7.xhtml.collapsable.init, false);
}