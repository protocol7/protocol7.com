<?php /*

	Comments Template
	This template holds the layout of comments.
	It is included by comments.php.

*/ ?>
<?php 
	$even = "comment-even";
	$odd = "comment-odd";
	$author = "comment-author";
	$bgcolor = $even;
?>
<?php foreach ($comments as $comment) : ?>
	<?php if ($comment->comment_type != "trackback" && $comment->comment_type != "pingback") {
			// Show only comments, not trackbacks ?>

<?php if (!$runonce_comments) { $runonce_comments = true; ?>
<h2 id="comments"><?php _e('Comments','fauna'); ?></h2>
<ol id="commentlist">
<?php } ?>
	
	<?php /* Comment number and bg colors */ 
	$comment_number++;
	if($odd == $bgcolor) { $bgcolor = $even; } else { $bgcolor = $odd; }

	/* Assign .comment-author CSS class to weblog administrator */
	$is_author = false;
	if($comment->comment_author_email == get_option(admin_email)) {
		$is_author = true;
	}
	?>	
	
	<li id="comment-<?php comment_ID() ?>" class="<?php if ($is_author == true) { echo $author; } else { echo $bgcolor; }?>">
		<a name="comment-<?php comment_ID() ?>"></a>
		<small>
		
			<?php if (comments_open()) { ?><a class="quote" href="javascript:void(null)" title="<?php _e('Click here or select text to quote comment','fauna') ?>"  onmousedown="quote('<?php comment_ID() ?>', '<?php echo($comment->comment_author); ?>', 'comment','quotecomment-<?php comment_ID() ?>'<?php if (function_exists('do_textile') || function_exists('textile') || class_exists('Textile2_New') ) { ?>, true<?php } ?>); return false;"><?php _e('Quote','fauna'); ?></a><?php } ?>

			<?php if (function_exists("jal_edit_comment_link")) { ?>
			<?php jal_edit_comment_link(__('Edit Comment','fauna'), '<span class="edit">', '</span>'); ?>
			<?php } else { ?>
			<?php edit_comment_link(__('Edit Comment','fauna'), '<span class="edit">', '</span>'); ?>
			<?php } ?>

		</small>
		
		<div class="comment-header">
		
			<?php if (function_exists("comment_favicon")) { ?>

				<a href="<?php echo($comment->comment_author_url); ?>" title="<?php printf(__('Visit %s','fauna'), $comment->comment_author) ?>" class="comment-favatar">
					<?php comment_favicon($before='<img src="', $after='" alt="" />'); ?>
				</a>

			<?php } ?>
			
			<strong><?php comment_author_link(); ?></strong>
			<?php if ( function_exists("comment_subscription_status") ) { if (comment_subscription_status() || $is_author == true && get_option('comments_notify')) { ?><?php _e('(subscribed)','fauna'); ?><?php }} ?>
			<?php _e('said','fauna'); ?>
			
			<a class="comment-permalink" href="#comment-<?php echo($comment->comment_ID) ?>" title="<?php _e('Permanent link to this comment','fauna'); ?>"><?php if (function_exists('relativeDate')) { relativeDate(get_the_time('YmdHis')); } else if (function_exists('time_since')) { printf(__('%s ago','fauna'), time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time())); } else { comment_date(); ?>, <? comment_time(); } ?></a>:

			<?php if ($comment->comment_approved == '0') : ?>
			<div class="notice"><?php _e('Your comment is awaiting moderation. This is just a spam counter-measure, and will only happen the first time you post here. Your comment will be approved as soon as possible.','fauna') ?></div>
			<?php endif; ?>

		</div>

		<div id="quotecomment-<?php comment_ID() ?>"><?php comment_text() ?></div>

</li>

	<?php } ?>
<?php endforeach; ?>

	<? if ($runonce_comments) { ?>
	</ol>
	<? } ?>