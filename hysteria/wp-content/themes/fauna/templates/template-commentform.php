<?php /*

	Commentform Template
	This is the comment form design. It is included in comments.php.

*/ ?>
<a name="respond" id="respond"></a>

<div id="leavecomment">

<h2><?php echo (isset($_GET['jal_edit_comments'])) ? __('Edit your Comment','fauna') : __('Leave a Comment','fauna'); ?></h2>

<?php if ( get_option('comment_registration') && !$user_ID ) :
		// Not logged in, and registration required ?>

<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.','fauna'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink()) ?></p>
<?php else :
		// Logged in, or registration not required ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<?php if ( isset($_GET['jal_edit_comments']) ) : $jal_comment = jal_edit_comment_init();
	if (!$jal_comment) : return; endif;
	elseif ( $user_ID ) :
			// Show/hide user info form if user is cookied or logged in ?>

	<div class="commentbox">
		<?php printf( __('Logged in as %s.','fauna'), '<a href="' . get_option('siteurl') . '/wp-admin/profile.php">' . $user_identity . '</a>') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','fauna'); ?>"><?php _e('Logout','fauna'); ?> &raquo;</a>
	</div>

	<?php else : ?>

	<div class="commentbox">

		<?php if ($comment_author != "") { ?>

		<?php printf(__('Welcome back <strong>%s</strong>','fauna'), $comment_author) ?>
		<span id="showinfo">(<a href="javascript:ShowInfo();"><?php _e('Change','fauna'); ?></a>)</span>
		<span id="hideinfo">(<a href="javascript:HideInfo();"><?php _e('Close','fauna'); ?></a>)</span>
		<script type="text/javascript">hideOnLoad("hideinfo");</script>

		<div id="comment-author">
		<script type="text/javascript">hideOnLoad("comment-author");</script>

		<?php } else { ?>

		<div id="comment-author">

		<?php } ?>

			<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="28" tabindex="1" class="inputbox" /> <label for="author"><?php _e('Name','fauna'); ?></label> <?php if ($req) _e('(required)','fauna'); ?><input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" /></p>

			<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="28" tabindex="2" class="inputbox" /> <label for="email"><?php _e('E-mail','fauna'); ?></label> <?php if ($req) _e('(required)','fauna'); ?></p>

			<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="28" tabindex="3" class="inputbox" /> <label for="url"><?php _e('Website','fauna'); ?></label></p>

		</div>

	</div>

	<?php endif; ?>

<div class="formatting">
	<a class="toggle" href="#formatting" onclick="toggleFormatting();" title="<?php _e('Description opens inline (right here)','fauna'); ?>"><?php _e('Formatting your Comment','fauna'); ?></a>
</div>

<div id="tags-allowed"><a name="formatting" id="formatting"></a>
	<div class="close">(<a href="#formatting" onclick="toggleFormatting();" title="<?php _e('Hide this description','fauna'); ?>"><?php _e('Close','fauna'); ?></a>)</div>
	<h4><?php _e('Formatting Your Comment','fauna'); ?></h4>
	<p><?php _e('The following <abbr title="eXtensible Hypertext Markup Language">XHTML</abbr> tags are available for use:','fauna'); ?></p>
	<p><code><?php echo allowed_tags(); ?></code></p>
	<p><?php _e('URLs are automatically converted to hyperlinks.','fauna'); ?></p>

	<?php if (function_exists('do_textile') || function_exists('textile') || class_exists('Textile2_New') ) { ?>

	<h4><?php _e('Textile','fauna'); ?></h4>
	<p><?php _e('Textile is a method that uses simple symbols to quickly write rich text markup. These are the most common:','fauna'); ?></p>
	<ul class="column-left">
		<li><em><?php _e('_emphasis_','fauna'); ?></em></li>
		<li><strong><?php _e('*strong*','fauna'); ?></strong></li>
		<li><del><?php _e('-deleted text-','fauna'); ?></del></li>
		<li><?php _e('!imageurl.gif!','fauna'); ?></li>
	</ul>
	<ul class="column-right">
		<li><a href="#"><?php _e('"link text":http://link.url','fauna'); ?></a></li>
		<li><blockquote><?php _e('bq. quoted content','fauna'); ?></blockquote></li>
		<li><code><?php _e('@short code snippet@','fauna'); ?></code></li>
	</ul>

	<?php } ?>

</div>
<script type="text/javascript">hideOnLoad("tags-allowed");</script>

<p><textarea name="comment" id="comment" cols="70" rows="15" tabindex="4"><?php if (isset($_GET['jal_edit_comments'])) { jal_comment_content($jal_comment); } ?></textarea></p>
<p><input name="submit" type="submit" tabindex="6" value="<?php echo (isset($_GET['jal_edit_comments'])) ? __('Submit Edit','fauna') : __('Post','fauna'); ?>" class="pushbutton-wide" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></p>
<?php if (function_exists(show_subscription_checkbox)) { show_subscription_checkbox(); } ?>
<?php do_action('comment_form', $post->ID); ?>
</form>

<?php endif; ?>

</div>