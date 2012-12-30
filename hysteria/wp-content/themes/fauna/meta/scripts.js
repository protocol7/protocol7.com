/*

	JavaScripts used in Fauna.

*/

/*

	Show/Hide
	
*/
function showHide(element) {
	if (document.getElementById(element).style.display == "") {	// show it
		document.getElementById(element).style.display = "none";
		document.getElementById(element+"-hidden").style.display = "";
	} else {
		document.getElementById(element).style.display = "";
		document.getElementById(element+"-hidden").style.display = "none";
	}
}

/* 
	
	Show/Hide comment info
	
*/
function ShowInfo() {
	document.getElementById("comment-author").style.display = "";
	document.getElementById("showinfo").style.display = "none";
	document.getElementById("hideinfo").style.display = "";
}

function HideInfo() {
	document.getElementById("comment-author").style.display = "none";
	document.getElementById("showinfo").style.display = "";
	document.getElementById("hideinfo").style.display = "none";
}

/* 

	Show/Hide formatting info
	
*/
formattingOpen = false;
function toggleFormatting() {
	if (formattingOpen == false) {
		document.getElementById("tags-allowed").style.display = "";
		formattingOpen = true;
	} else {
		document.getElementById("tags-allowed").style.display = "none";
		formattingOpen = false;
	}
}

/* 

	Hide On Load
	
*/
function hideOnLoad(element) {
	document.getElementById(element).style.display = "none";
}

/* 

	Quote comment
	
*/
function addQuote(comment,quote){
	/*
	
		Derived from Alex King's JS Quicktags code (http://www.alexking.org/)
		Released under LGPL license
		
	*/
	
	// IE support
	if (document.selection) {
		comment.focus();
		sel = document.selection.createRange();
		sel.text = quote;
		comment.focus();
	}
	// Mozilla support
	else if (comment.selectionStart || comment.selectionStart == '0') {
		var startPos = comment.selectionStart;
		var endPos = comment.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = comment.scrollTop;
		if (startPos != endPos) {
			comment.value = comment.value.substring(0, startPos)
			              + quote
			              + comment.value.substring(endPos, comment.value.length);
			cursorPos = startPos + quote.length
		}
		else {
			comment.value = comment.value.substring(0, startPos) 
				              + quote
				              + comment.value.substring(endPos, comment.value.length);
			cursorPos = startPos + quote.length;
		}
		comment.focus();
		comment.selectionStart = cursorPos;
		comment.selectionEnd = cursorPos;
		comment.scrollTop = scrollTop;
	}
	else {
		comment.value += quote;
	}
	
	// If Live Preview Plugin is installed, refresh preview
	try {
		ReloadTextDiv();
	}
	catch ( e ) {
	}	
}
function quote(postid, author, commentarea, commentID, textile) {
	var posttext = '';
	if (window.getSelection){
		posttext = window.getSelection();
	}
	else if (document.getSelection){
		posttext = document.getSelection();
	}
	else if (document.selection){
		posttext = document.selection.createRange().text;
	}
	else {
		return true;
	}
	
	if (posttext==''){
		
		// quote entire comment as html
		var posttext = document.getElementById(commentID).innerHTML;
		var posttext = posttext.replace(/	/g, "");
		var posttext = posttext.replace(/<p>/g, "\n");
		var posttext = posttext.replace(/<\/\s*p>/g, "");
		var posttext = posttext.replace(/<br>/g, "")

		// remove nested blockquotes
		var posttext = posttext.replace(/<blockquote>[^>]*<\/\s*blockquote>/g, "");
		var posttext = posttext.replace(/<blockquote>[^>]*<\/\s*blockquote>/g, "");

		var quote='<blockquote>\n'+posttext+'</blockquote>\n\n';

		var comment=document.getElementById(commentarea);
		addQuote(comment,quote);
		
	} else {
		
		// quote selection a html or textile
		if (textile) {
			var quote='bq. '+posttext+'\n\n';
		} else {
			var quote='<blockquote>\n\n'+posttext+'\n\n</blockquote>\n';
		}
		var comment=document.getElementById(commentarea);
		addQuote(comment,quote);
		
	}
	return false;
}