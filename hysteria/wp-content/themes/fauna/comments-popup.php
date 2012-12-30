<?php
/* Don't remove these lines. */
add_filter('comment_text', 'popuplinks');
foreach ($posts as $post) { start_wp();
?>
<?php load_theme_textdomain('fauna'); ?>
<?php include TEMPLATEPATH . "/functions-custom.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">

	<title><?php echo get_option('blogname'); ?> | <?php echo sprintf(__('Comments on %s','fauna'), the_title('','',false)); ?></title>

	<!-- Meta -->
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo get_option('blog_charset'); ?>" />

	<!-- Stylesheet -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" title="Fauna" href="<?php bloginfo('stylesheet_directory'); ?>/<?php if ($fauna->option['custom_stylesheet'] != "") { echo $fauna->option['custom_stylesheet']; } else { ?>styles/default/default.css<?php } ?>" />
	
	<!-- JavaScript -->
	<script language="javascript" type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/meta/scripts.js"></script>
</head>

<body id="commentspopup">

<?php
// this line is WordPress' motor, do not delete it.
$comment_author = (isset($_COOKIE['comment_author_' . COOKIEHASH])) ? trim($_COOKIE['comment_author_'. COOKIEHASH]) : '';
$comment_author_email = (isset($_COOKIE['comment_author_email_'. COOKIEHASH])) ? trim($_COOKIE['comment_author_email_'. COOKIEHASH]) : '';
$comment_author_url = (isset($_COOKIE['comment_author_url_'. COOKIEHASH])) ? trim($_COOKIE['comment_author_url_'. COOKIEHASH]) : '';
$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $id AND comment_approved = '1' ORDER BY comment_date");
$commentstatus = $wpdb->get_row("SELECT comment_status, post_password FROM $wpdb->posts WHERE ID = $id");
if (!empty($commentstatus->post_password) && $_COOKIE['wp-postpass_'. COOKIEHASH] != $commentstatus->post_password) {  // and it doesn't match the cookie
	echo(get_the_password_form());
} else { ?>

<div class="box">

	<h2><?php echo sprintf(__('Comments on %s','fauna'), the_title('','',false)); ?></h2>

	<?php include (TEMPLATEPATH . '/templates/template-commentmeta.php'); ?>

</div>

<? /* Comments and Trackbacks */ ?>

<?php if ( $comments ) :			// If there are comments ?>

<div class="box"><?php // Open box container ?>
	
		<?php include (TEMPLATEPATH . '/templates/template-trackbacks.php'); ?>
		
		<?php include (TEMPLATEPATH . '/templates/template-comments.php'); ?>
	
<?php else : 						// If there are no comments yet ?>

<?php /* 
<div class="box"><?php // Open box container ?>
	<p><?php _e('No comments yet.', 'fauna'); ?></p>
*/ ?>
		
<?php endif; ?>


<? /* Commentform */ ?>
<?php if ( comments_open() ) :	// Comments are open, show the commentform ?>

		<?php if (!$comments) { ?><div class="box"><?php }
			// If there are no comments, then the box container wasn't opened, open it here ?>

		<?php include (TEMPLATEPATH . '/templates/template-commentform.php'); ?>

		</div><?php // Close the box container after the commentform ?>
	
	<?php else : 				// Comments are closed, don't show the commentform ?>
	
			<? /* 
			<p><?php _e('Comments are not allowed at this time.', 'fauna'); ?></p>
			*/ ?>

		<?php if ($comments) { ?></div><?php } 
			// If there are comments, then the box container was opened,
			// but comments are closed so the commentform above isn't closing it,
			// so close it here ?>
	
<?php endif; ?>

<? } } ?>

<?php // Seen at http://www.mijnkopthee.nl/log2/archive/2003/05/28/esc(18) ?>
<script type="text/javascript">
<!--
document.onkeypress = function esc(e) {	
	if(typeof(e) == "undefined") { e=event; }
	if (e.keyCode == 27) { self.close(); }
}
// -->
</script>

</body>
</html>
