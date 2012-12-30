<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!','fauna'));

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<div class="box">
					<h2><?php _e('Protected Post','fauna'); ?></h2>
					<p><?php _e('This post is password protected. Enter the password to view comments.','fauna'); ?><p>
				</div>
				
				<?php
				return;
            }
        }
?>

<a name="comments"></a>

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
