<?php
/**
 * @package WordPress
 * @subpackage Constructor
 */
?>
<div id="comments">
<?php // Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'constructor'); ?></p>
	<?php
		return;
	}
?>

<!-- Comments list -->

<?php if ( have_comments() ) : ?>
	<h3><?php comments_number(__('No Responses', 'constructor'), __('One Response', 'constructor'), __('% Responses', 'constructor'));?> <?php printf(__('to &#8220;%s&#8221;', 'constructor'), the_title('', '', false)); ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments('avatar_size='.get_constructor_avatar_size());?>
	</ol>
    <?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
    <?php endif; ?>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if (comments_open()) : ?>
        <!-- If comments are open, but there are no comments. -->
	<?php else : // comments are closed ?>
    	<!-- If comments are closed. -->
    	<p class="nocomments"><?php _e('Comments are closed.', 'constructor'); ?></p>
	<?php endif; ?>
<?php endif; ?>


<!-- Respond form -->

<?php if ('open' == $post->comment_status) : ?>

    <div id="respond">
    
    <h3><?php comment_form_title( __('Leave a Reply', 'constructor'), __('Leave a Reply for %s' , 'constructor') ); ?></h3>
    <div id="cancel-comment-reply"> 
    	<small><?php cancel_comment_reply_link() ?></small>
    </div> 
    
    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
        <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'constructor'), get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink())); ?></p>
    <?php else : ?>
    
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        <?php comment_id_fields(); ?> 
        <?php if ( $user_ID ) : ?>    
            <p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.', 'constructor'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'constructor'); ?>"><?php _e('Log out &raquo;', 'constructor'); ?></a></p>    
        <?php else : ?>
            <p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
            <label for="author"><?php _e('Name', 'constructor'); ?> <?php if ($req) _e("(required)", "constructor"); ?></label></p>        
            <p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
            <label for="email"><?php _e('Mail (will not be published)', 'constructor'); ?> <?php if ($req) _e("(required)", "constructor"); ?></label></p>        
            <p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />        
            <label for="url"><?php _e('Website', 'constructor'); ?></label></p>
        
        <?php endif; ?>    
        <!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>', 'constructor'), allowed_tags()); ?></small></p>-->    
        <p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>    
        <p class="submit"><input name="submit" type="submit" id="submit" tabindex="5" class="button" value="<?php _e('Submit Comment', 'constructor'); ?>" /></p>
        <?php do_action('comment_form', $post->ID); ?>    
        </form>
    
    <?php endif; // If registration required and not logged in ?>
    </div><!--id="respond"-->    
<?php endif; // if you delete this the sky will fall on your head ?>

</div>