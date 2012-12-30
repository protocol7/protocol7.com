<?php /*

	Trackbacks Template
	This page holds the layout of trackbacks.
	It is included by comments.php.
	
*/ ?>
<?php foreach ($comments as $comment) : ?>
	<? if ($comment->comment_type == "trackback" || $comment->comment_type == "pingback") {
			// Show only trackbacks, not comments ?>

		<? if (!$runonce_trackbacks) { $runonce_trackbacks = true; ?>
	<h2 id="trackbacks"><?php _e('Trackbacks &amp; Pingbacks','fauna'); ?></h2>
	<ol id="trackbacklist">
		<? } ?>

	<li>
		<?php edit_comment_link(__('Edit This','fauna'), '<span class="edit">', '</span>'); ?>

		<a name="comment-<?php comment_ID() ?>" href="<? echo($comment->comment_author_url); ?>" title="<?php printf(__('Visit %s','fauna'), ($comment->comment_author)) ?>">
		<? if (function_exists('comment_favicon')) { comment_favicon($before='<img src="', $after='" alt="" class="trackback-favatar" />'); }; ?>
		<strong><span><? echo($comment->comment_author); ?></span></strong>
		<small>
		<?php comment_type(__('commented','fauna'), __('trackbacked','fauna'), __('pingbacked','fauna')); ?>
		<?php if (function_exists('time_since')) {
		printf(__('%s ago','fauna'), time_since(abs(strtotime($comment->comment_date_gmt . " GMT")), time()));
		} else { ?>
		<?php printf(__('Posted  %1$s, %2$s','fauna'), get_comment_date(), get_comment_time()); } ?>

		</small>
		</a>
		<?php if (get_option('fauna_trackback') != '0') {
		comment_text();
		}
		?>
	</li>
	
	<? } ?>

<?php endforeach; ?>

	<? if ($runonce_trackbacks) { ?>
	</ol>
	<? } ?>
