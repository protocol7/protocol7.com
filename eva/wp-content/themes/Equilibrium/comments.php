<?php


// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h2 class="comment-heading"><?php comments_number('No Comments', '1 Comment', '% Comments' );?></h2>

	<ul class="commentlist">
	<?php wp_list_comments('callback=eq_comment'); ?>
	</ul>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<h3 id="reply"><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>

<div class="cancel-comment-reply">
<p><?php cancel_comment_reply_link(); ?></p>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

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

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<label class="wrap"  for="url">Comment:</label>
	<textarea name="comment" id="comment" cols="100%" rows="10" class="textarea" tabindex="4"></textarea>
	<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->



<p><input name="submit" type="submit" id="submit" class="button" tabindex="5" value="Post" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>
</div>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
