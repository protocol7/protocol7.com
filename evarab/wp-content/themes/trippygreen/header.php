<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

 <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<!--[if lt ie 7]>	
		<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/ie-win.css" />
	<![endif]-->	
	
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />

<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_get_archives('type=monthly&format=link'); ?>

<?php wp_head(); ?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-5627178-1");
pageTracker._trackPageview();
</script>

</head>

<body>
<div id="wrapper2">
<div id="headerbg">
	<div id="navigation">
		       
         	<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
					<div style="clear: both;"></div>
						<div class="slogan"><?php bloginfo('description'); ?></div>
			<div style="clear: both;"></div>
			 <ul>
              		<li class="page_item"><a href="<?php bloginfo('url'); ?>">Home</a></li>

	<?php wp_list_pages('depth=1&title_li='); ?>
	
                </ul>
	<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header-image.gif" alt="header" style="margin-left: 10px; margin-top: 0px;" />
	</div>
	</div>
	

