<?php

/*
Plugin Name: MyHeaders
Plugin URI: http://protocol7.com
Description: Inserts custom header tags
Version: 1.0
Author: Niklas Gustavsson
Author URI: http://protocol7.com

*/

function insert_tags()
{
	echo "        <script src='/mint/?js' type='text/javascript' language='javascript'></script>
        <link rel='openid.server' href='http://www.myopenid.com/server' />
        <link rel='openid.delegate' href='http://niklas.gustavsson.myopenid.com/' />
        <meta http-equiv='X-XRDS-Location' content='http://niklas.gustavsson.myopenid.com/xrds' />
	 <!-- Start Quantcast tag -->
	 <script type='text/javascript' src='http://edge.quantserve.com/quant.js'></script>
	 <script type='text/javascript'>
		_qacct='p-33NoKC8zjhcr6';quantserve();</script>
	<!-- End Quantcast tag -->


         <link rel='stylesheet' type='text/css' href='http://protocol7.com/wp-custom/my-styles.css'></head>
<script type='text/javascript'>
var gaJsHost = (('https:' == document.location.protocol) ? 'https://ssl.' : 'http://www.');
document.write(unescape('%3Cscript src=\"' + gaJsHost + 'google-analytics.com/ga.js\" type=\"text/javascript\"%3E%3C/script%3E'));
</script>
<script type='text/javascript'>
var pageTracker = _gat._getTracker('UA-4857807-1');
pageTracker._initData();
pageTracker._trackPageview();
</script>
";
}

add_action('wp_head', 'insert_tags');

?>
