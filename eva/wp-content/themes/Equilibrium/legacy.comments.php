<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>

 	<h2 class="comment-heading"><?php comments_number('No Comments', '1 Comment', '% Comments' );?></h2>
    		
		<?php foreach ($comments as $comment) : ?>
      <div class="commentwrap">
<div>
			<ul class="commentmetadata">
				<li><?php echo get_avatar( $comment, 32 ); ?></li>
				<li><?php comment_author_link() ?></li>
				<li><?php comment_date('M j, Y G:i') ?></li>
			</ul>
</div>
			<div class="commentpost"><?php comment_text() ?></div>
<div class="clear"></div>
</div>

        
		<?php endforeach; /* end for each comment */ ?>
	
 	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
		</div>  <!-- closing comments -->
        
	<?php endif; ?>
<?php endif; ?>

	
<?php if ('open' == $post->comment_status) : ?>

<h1 id="respond">Leave a Reply</h1>
<?php if ($comment->comment_approved == '0') : ?>
<p>If you are a first time poster, your comment will not appear publically until it has been approved.  This usually happens in 24 hours or less.  Sorry for any inconvenience!</p>
<?php endif; ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>

<label class="wrap" for="author">
<span>Name (required)</span>
<br />
<input id="author" name="author" value="<?php echo $comment_author; ?>" type="text" tabindex="1" size="27"/>
</label>
<br />
<label class="wrap" for="email">
<span>Email (required) - will not be published</span>
<br />
<input id="email" name="email" value="<?php echo $comment_author_email; ?>" type="text" tabindex="2" size="27"/>
</label>
<br />
<label class="wrap"  for="url">
<span>Website (optional)</span>
<br />
<input id="url" name="url" value="<?php echo $comment_author_url; ?>" type="text" tabindex="3" size="27"/>
</label>
<br />
	<?php endif; ?>
	<label class="wrap"  for="url">Comment:</label>
	<textarea name="comment" id="comment" cols="20" rows="10" class="textarea" tabindex="4"></textarea>
	<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->
	<input name="submit" type="submit" id="submit" class="button" value="Post" tabindex="5" />
	<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
	<?php do_action('comment_form', $post->ID); ?>
	</form>

</form>


<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
