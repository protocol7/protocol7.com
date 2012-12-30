<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<style type="text/css" media="screen">
	<!--
	@import url(<?php bloginfo('stylesheet_directory'); ?>/data/global.css);
	@import url(<?php bloginfo('stylesheet_directory'); ?>/data/articles.css);
	-->
	</style>
</head><body><div id="main">
<div id="intro">
  <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.gif" alt="AMY&PINK" border="0" height="32" /></p>
  <p><span class="localnav">AND THEIR DANCING AND THEIR LAUGHING.</span></p>
</div>
	<div class="clearer">
		&nbsp;
	</div>
		<ul id="localnav">
		<li><h6><a class="<?php if ( is_home() ) { ?>hi<?php } else { ?><?php if ( is_single() ) { ?>hi<?php } else { ?>all<?php } ?><?php } ?>" href="<?php bloginfo(url); ?>" id="localnav_home">Home</a></h6></li>
		<li><h6><a class="<?php if ( is_page(about) ) { ?>hi<?php } else { ?>all<?php } ?>" href="#" id="localnav_about">About</a></h6></li>
        <li><h6><a class="<?php if ( is_page(lieblinge) ) { ?>hi<?php } else { ?>all<?php } ?>" href="#" id="localnav_favourites">Favourites</a></h6></li>
		<li><h6><a class="<?php if ( is_page(fotos) ) { ?>hi<?php } else { ?>all<?php } ?>" href="#" id="localnav_photos">Photos</a></h6></li>
		<li><h6><a class="all" href="#" id="localnav_showall">Special<img src="<?php bloginfo('stylesheet_directory'); ?>/data/check_on20060502.gif" alt="" height="8" width="8" /></a></h6></li>
	</ul>
<div id="rss">
			<a id="rssbutton" href="#">RSS Feed</a>
	</div>
	</body></html>