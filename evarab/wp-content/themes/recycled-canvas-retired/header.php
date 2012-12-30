<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<title><?php wp_title(''); ?> <?php if ( is_home() == false ) { ?> :: <?php } ?> <?php bloginfo('name'); ?></title>

<link rel="icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" type="image/x-icon" />

<!-- Stylesheets-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); echo "/styles/" . rr_GetStyleSheet(); ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_directory'); 
	  if(rr_GetView() != "") {
		echo "/views/" . rr_GetView();  
	  }
	  else {
		echo "/views/boxview"; 
	  }
?>.css" />
<link rel="stylesheet" type="text/css" media="handheld" href="<?php bloginfo('template_directory'); echo "/styles/handheld.css" ?>" />
<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); echo "/styles/print.css" ?>" />

<style type='text/css'>#header img, #footer img, #postloop img { behavior: url(<?php bloginfo('template_directory'); ?>/styles/iepngfix.htc); }</style>

<!-- Blog Stuff-->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- Scripts-->

<!-- Generated-->
<?php wp_head(); ?>
<?php include_once(dirname(__FILE__) . '/functions.php'); ?>


</head>

<body>	
	<div id="page">
			<div id="header">
				<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>  <span><?php rr_GetSubtitle(); ?></span></h1>
				<div id="menu">
					<ul><?php wp_list_pages('title_li=&depth=1');?> 	<?php if(rr_ShowSidebar() == 'off' && is_home()) {?><li><?php include (TEMPLATEPATH . "/searchform.php"); ?></li><?php }?> <li><a href="<?php bloginfo('rss2_url'); ?>">Subscribe <img alt="RSS Feed Icon" src="<?php bloginfo('template_url')?>/images/feed.png" style="vertical-align:top; border:none;"/></a></li></ul>
				</div>
			</div>				

			<?php if(rr_UseHeader() != 'false') {?>
				<div id="headerimg"><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo rr_UseHeader(); ?>-header.jpg"/></div>
			<?php } ?>
	
			<div id="printspecial">
				<strong>This page has been designed specifically for the printed screen. It may look different than the page you were viewing on the web. <br/>Please recycle it when you're done reading.<br/><br/>
				The URI for this page is { <?php bloginfo('url'); ?> }</strong>
			</div>