
<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>

				This post is password protected. Enter the password to view comments.

				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'normcomment';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>
<div id="comments">
	<h4><?php comments_number('No Responses', 'One Response', 'Some Responses' );?> to &#8220;<?php the_title(); ?>&#8221; :</h4>

	<ol class="commentlist">

	<?php foreach ($comments as $comment) : ?>
		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">
			<?php if ($comment->comment_approved == '0') : ?>
			<em>Your comment is awaiting moderation.</em>
			<?php endif; ?>
			<?php comment_text() ?>
			<span class="commentmeta">Commented <strong><?php comment_author_link() ?></strong> on <?php comment_date('F jS, Y') ?>. </span>
			
			<?php /* Changes every other comment to a different class */
			if ('altcomment' == $oddcomment) $oddcomment = 'normcomment';
			else $oddcomment = 'altcomment';
			?>
		</li>
	<?php endforeach; /* end for each comment */ ?>

	</ol>

</div>	
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>
<div id="respond">
	
	<form  action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		
	<strong>Leave your own comments about this post:</strong>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.
	
	<?php else : ?>
		<textarea name="comment" id="comment" rows="4" cols="20" tabindex="4"></textarea>
			<?php if ( $user_ID ) : ?>
				<div style=" padding-top:15px; padding-bottom:15px;">
					<div style="float:right;"><input name="submit" type="submit" id="submit" tabindex="5" value="Post it" /></div>
					<div>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></div>
				</div>

			<?php else : ?>
				<div style="padding-bottom:15px; padding-top:15px;">
	
				<input name="submit" type="submit" id="submit" tabindex="5" value="Post it" style="float:right;"/>
				
				<label for="author"><small>Name:</small></label>
				<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
				<label for="email"><small>Email:</small></label>
				<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
				<label for="url"><small>Url:</small></label>
				<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			</div>
			<?php endif; ?>

			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

			<?php do_action('comment_form', $post->ID); ?>
	</form>
</div>
<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
