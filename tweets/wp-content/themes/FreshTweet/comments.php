<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>

			<?php
			return;
		}
	}

?>

<!-- You can start editing here. -->

<?php if ('open' == $post->comment_status) : ?>

<div id="comments">

<div id="respond">
	<h4>What Is Your Comment?</h4>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<div class="entry">
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
</div>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<input class="text_input" type="text" name="author" id="author" value="your name*" onblur="if (this.value == '') {this.value = 'your name*';}"  onfocus="if (this.value == 'your name*') {this.value = '';}" tabindex="1" />

<input class="text_input" type="text" name="email" id="email" value="your email*" onblur="if (this.value == '') {this.value = 'your email*';}"  onfocus="if (this.value == 'your email*') {this.value = '';}" tabindex="2" />

<input class="text_input" type="text" name="url" id="url" value="your website" onblur="if (this.value == '') {this.value = 'your website';}"  onfocus="if (this.value == 'your website') {this.value = '';}" tabindex="3" /><br />

<?php endif; ?>

<p><textarea class="text_input text_area" name="comment" rows="5" value="your comment*" onblur="if (this.value == '') {this.value = 'your comment*';}"  onfocus="if (this.value == 'your comment*') {this.value = '';}" tabindex="4">your comment*</textarea>
</p>
<p style="text-align: right; font-size: 85%; padding: 0px; margin: 0px;">* = required</p>

<input name="submit" class="form_submit" type="submit" id="submit" tabindex="5" value="comment" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

<div class="clear"></div>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>


<?php if ($comments) : ?>

	<h5>Recent Comments</h5>

<ol id="commentlist">

	<?php foreach ($comments as $comment) : ?>

	<li id="comment-<?php comment_ID() ?>">
		
			<div class="avatar"><?php echo get_avatar( $comment, 48 ); ?></div>

			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			
			<?php endif; ?>

		<div class="entry">
		<p><strong><?php comment_author_link() ?></strong> @ <?php comment_time() ?><?php edit_comment_link('Edit Comment',' | '); ?></p>
			<?php comment_text() ?> 
		</div>

		<div class="perm">
			<a href="#comment-<?php comment_ID() ?>" title="Comment Trackback"><img src="<?php bloginfo('template_directory'); ?>/images/star.gif" alt="Star" width="16" height="16" border="0" /></a>
			<a href="#respond" title="Respond"><img src="<?php bloginfo('template_directory'); ?>/images/arrow.gif" alt="Comment Trackback" width="16" height="16" border="0" /></a>
		</div>
		<div class="clear"></div>
	
	</li>

	<?php endforeach; /* end for each comment */ ?>

</ol>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>
</div>
