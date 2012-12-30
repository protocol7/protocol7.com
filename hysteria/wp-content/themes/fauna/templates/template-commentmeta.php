<?php /*

	Commentmeta Template
	This template shows comment meta information such as trackback URL and so on.
	It is included by sidebar.php and comments-popup.php.
	
*/ ?>
<?php // Both Comments and Pings are open
if (('open' == $post->comment_status) && ('open' == $post->ping_status)) { ?>

	<p><?php comments_number(__('There are no responses','fauna'), __('There is one response','fauna'), __('There are % responses','fauna')); ?>. <?php _e( '<a href="#respond">Respond</a>.','fauna'); ?></p>

	<?php // if (function_exists('show_manual_subscription_form')) { show_manual_subscription_form(); }; ?>

	<p><span id="trackback">
		<a href="<?php trackback_url(display) ?>" onclick="showHide('trackback');return false;" title="<?php _e('Trackback URI to this entry','fauna'); ?>" rel="nofollow"><?php _e('Trackback','fauna'); ?></a>
	</span>
	<span id="trackback-hidden">
		<input name="textfield" type="text" value="<?php trackback_url() ?>" class="inputbox" onclick="select();" />
		<input name="hide" type="button" id="hide" value="<?php _e('Hide','fauna'); ?>" onclick="showHide('trackback');return false;" />
	</span>
	<script type="text/javascript">hideOnLoad("trackback-hidden");</script>
	</p>

<?php // Only Pings are Open 
} elseif (!('open' == $post->comment_status) && ('open' == $post->ping_status)) { ?>

	<p><span id="trackback">
		<a href="<?php trackback_url(display) ?>" onclick="showHide('trackback');return false;" title="<?php _e('Trackback URI to this entry','fauna'); ?>" rel="nofollow"><?php _e('Trackback','fauna'); ?></a>
	</span>
	<span id="trackback-hidden">
		<input name="textfield" type="text" value="<?php trackback_url() ?>" class="inputbox" onclick="select();" />
		<input name="hide" type="button" id="hide" value="<?php _e('Hide','fauna'); ?>" onclick="showHide('trackback');return false;" />
	</span>
	<script type="text/javascript">hideOnLoad("trackback-hidden");</script>
	</p>

<?php // Comments are open, Pings are not
} elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>

	<p><?php comments_number(__('There are no responses','fauna'), __('There is one response','fauna'), __('There are % responses','fauna')); ?>. <?php _e( '<a href="#respond">Respond</a>.','fauna'); ?></p>

	<?php // if (function_exists('show_manual_subscription_form')) { show_manual_subscription_form(); }; ?>

<?php // Neither Comments, nor Pings are open
} elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>
<?php } ?>