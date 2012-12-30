var quotcoll_requrl, quotcoll_nexquote, quotcoll_loading, quotcoll_errortext;

function quotescollection_init(requrl, nextquote, loading, errortext)
{
	quotcoll_requrl = requrl;
	quotcoll_nextquote = nextquote;
	quotcoll_loading = loading;
	quotcoll_errortext = errortext;
}

function quotescollection_refresh(instance, exclude, show_author, show_source)
{
    // function body defined below
	var mysack = new sack( quotcoll_requrl );

	mysack.execute = 1;

	mysack.method = 'POST';
	mysack.setVar("refresh", instance);
	mysack.setVar("exclude", exclude);
	mysack.setVar("show_author", show_author);
	mysack.setVar("show_source", show_source);
	
	mysack.onError = function() { document.getElementById('quotescollection_randomquote-'+instance).innerHTML = quotcoll_errortext; };
	mysack.onLoading = function() { document.getElementById('quotescollection_nextquote-'+instance).innerHTML = quotcoll_loading; };
	mysack.onLoaded = function() { document.getElementById('quotescollection_nextquote-'+instance).innerHTML = '<a style="cursor:pointer" onclick="quotescollection_refresh('+instance+','+exclude+','+show_author+','+show_source+');">' + quotcoll_nextquote + ' &raquo</a>'; };
//	mysack.onInteractive = function() { document.getElementById('quotescollection_nextquote-'+instance).innerHTML += '...'; };
//	mysack.onCompletion = function() { document.getElementById('quotescollection_randomquote-'+instance).innerHTML = mysack.response; };
	mysack.runAJAX();
	return true;
}
