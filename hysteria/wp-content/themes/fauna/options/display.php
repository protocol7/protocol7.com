<?php

global $wpdb;

$update = fauna_options::update();

$scheme_name = get_option('fauna_style');
$scheme_title = $scheme_name !== false ? $scheme_name : __('No Style', 'fauna');

$scheme_files = fauna::files_scan(TEMPLATEPATH . '/styles/', 'css', 2);

?>

<?php if(isset($_POST['submit'])) { ?>
<div id="message2" class="updated fade">
	<p><strong><?php _e('Options saved.', 'fauna'); ?></strong>
	<?php if (get_option('fauna_header') == 'custom-header-api' &&  $_POST["fauna"]["header"] == 'custom-header-api') { ?>
	<?php echo wptexturize(__('To upload your own custom header graphic, click the <a href="themes.php?page=custom-header">Custom Image Header</a> tab.', 'fauna')); ?></p>
	<?php } ?></p>
</div>
<?php } ?>


<div class="wrap fauna-options">

	<form name="dofollow" action="" method="post">
		<input type="hidden" name="action" value="<?php echo($update); ?>" />
		<input type="hidden" name="page_options" value="'dofollow_timeout'" />

		<div class="configure">
			<h2><?php _e('Fauna Options', 'fauna'); ?></h2>
			
				<?php if (version_compare(get_bloginfo('version'), '2.1', '>')) { $widget_link = "widgets.php"; } else { $widget_link = "http://automattic.com/code/widgets/"; } ?>
                <p><?php printf(__('You can find help, documentation and more custom styles for Fauna at the <a href="http://www.noscope.com/fauna/">Fauna home page</a>. Added functionality is available through <a href="%s">Widgets</a>. ','fauna'), $widget_link); ?></p><hr />
			
			<p class="submit">
				<input type="submit" name="submit" value="<?php _e('Update Options', 'fauna'); ?> &raquo;" />
			</p>

			<table cellpadding="5">
				<tr valign="top">
					<th><?php _e('Custom Style', 'fauna') ?></th>
					<td>
						<select id="fauna-style" name="fauna[style]">
						<?php foreach($scheme_files as $scheme_file) { ?>
							<option value="<?php echo($scheme_file); ?>"<?php selected($scheme_name, $scheme_file); ?>><?php echo($scheme_file); ?></option>
						<?php } ?>
						</select><br />
						<?php _e('Get custom styles from the <a href="http://www.noscope.com/fauna/styles/">Fauna style directory</a>.','fauna'); ?>
					</td>
				</tr>
				<tr valign="top">
					<th><?php _e('About Text', 'fauna') ?></th>
					<td><textarea id="fauna-about" name="fauna[about]" rows="6" cols="30"><?php echo stripslashes(get_option('fauna_about')); ?></textarea>
                    <br /><?php _e('Text typed here will be used as an introduction to your site.','fauna'); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e('Sticky Note Text', 'fauna') ?></th>
					<td><textarea id="fauna-note" name="fauna[note]" rows="6" cols="30"><?php echo stripslashes(get_option('fauna_note')); ?></textarea>
					<br /><?php _e('Text typed here will appear as a yellow "sticky-hote".','fauna'); ?></td>
					</td>
				</tr>
				<tr valign="top">
					<th><?php echo wptexturize(__('Rename the "Blog" tab', 'fauna')); ?></th>
					<td><input id="fauna-tab" name="fauna[tab]" value="<?php echo stripslashes(get_option('fauna_tab')); ?>" /></td>
				</tr>
				<tr valign="top">
					<th><?php _e('Sidebar Position', 'fauna'); ?></th>
					<td><label><input id="fauna-sidebar-left" name="fauna[sidebar]" type="radio" value="left" <?php checked('left', get_option('fauna_sidebar')); ?> /> <?php _e('Left', 'fauna'); ?></label><br /><label><input id="fauna-sidebar-right" name="fauna[sidebar]" type="radio" value="right" <?php checked('right', get_option('fauna_sidebar')); ?> /> <?php _e('Right (Default)', 'fauna'); ?></label></td>
				</tr>
				<tr valign="top">
					<th><?php _e('Scalemode', 'fauna'); ?></th>
					<td><label><input id="fauna-scalemode-fixed" name="fauna[scalemode]" type="radio" value="fixed" <?php checked('fixed', get_option('fauna_scalemode')); ?> /> <?php _e('Fixed width (Default)', 'fauna'); ?></label><br /><label><input id="fauna-scalemode-liquid" name="fauna[scalemode]" type="radio" value="liquid" <?php checked('liquid', get_option('fauna_scalemode')); ?> /> <?php _e('Liquid', 'fauna'); ?></label></td>
				</tr>
				<tr valign="top">
					<th><?php _e('Noteworthy Icon', 'fauna'); ?></th>
					<td><input id="fauna-noteworthy" name="fauna[noteworthy]" value="<?php echo stripslashes(get_option('fauna_noteworthy')); ?>" />
					<br /><?php _e('Default is a heart: <code>%26hearts;</code>. To show a star use: <code>%26#9733;</code>. HTML is allowed.','fauna'); ?></td>
				</tr>
				<tr valign="top">
					<th><?php _e('Comments and Trackbacks', 'fauna'); ?></th>
					<td>
						<label><input id="fauna-comment" name="fauna[comment]" type="checkbox" value="1" <?php checked('1', get_option('fauna_comment')); ?> /> <?php _e('Enable Popup Comments', 'fauna'); ?></label><br />
						<label><input id="fauna-trackback" name="fauna[trackback]" type="checkbox" value="1" <?php checked('1', get_option('fauna_trackback')); ?> /> <?php _e('Show Trackback Body Text', 'fauna'); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th><?php _e('Author info', 'fauna'); ?></th>
					<td>
						<label><input id="fauna-author" name="fauna[author]" type="checkbox" value="1" <?php checked('1', get_option('fauna_author')); ?> /> <?php _e('Show author link in posts','fauna'); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th><?php _e('Archives', 'fauna'); ?></th>
					<td>
						<input id="fauna-archives" name="fauna[archives]" type="checkbox" value="1" <?php checked('1', get_option('fauna_archives')); ?> />
						<?php _e('Enable Archives Page', 'fauna'); ?>
					</td>
				</tr>
				<tr valign="top">
					<th><?php _e('Custom Header', 'fauna') ?></th>
					<td>
						<script type="text/javascript">
						<!--
						function faunaShow(id) {
							document.getElementById(id).style.display = "";
						}
						function faunaHide(id) {
							document.getElementById(id).style.display = "none";
						}
						function toggleUrl() {
							if (document.getElementById("fauna-header").value == "url-header") {
								faunaShow('fauna-headerurl-box')
							} else {
								faunaHide('fauna-headerurl-box')
							}
						}
						-->
						</script>
						<select id="fauna-header" name="fauna[header]" onchange="toggleUrl();">
							<option value="fauna"<?php selected('fauna', get_option('fauna_header')); ?>>Fauna</option>
							<option value="flora"<?php selected('flora', get_option('fauna_header')); ?>>Flora</option>
							<option value="frost"<?php selected('frost', get_option('fauna_header')); ?>>Frost</option>
							<option value="custom-header-api"<?php selected('custom-header-api', get_option('fauna_header')); ?>>Upload Custom</option>
							<option value="no-header"<?php selected('no-header', get_option('fauna_header')); ?>>No Header</option>
							<option value="url-header"<?php selected('url-header', get_option('fauna_header')); ?>>Custom URL</option>
						</select>
						<div id="fauna-headerurl-box">
							<div class="updated">
								<p><?php echo wptexturize(__('Type in the full URL to your custom header below.', 'fauna')); ?></p>
							</div>
							<input id="fauna-headerurl" name="fauna[headerurl]" value="<?php echo stripslashes(get_option('fauna_headerurl')); ?>" /> <?php _e('Header URL', 'fauna'); ?>
						</div>
						<?php if (get_option('fauna_header') != 'url-header') { ?>
						<script type="text/javascript">
						<!--
						document.getElementById("fauna-headerurl-box").style.display = "none";
						-->
						</script>
						<?php } ?>
						<?php if (get_option('fauna_header') == 'custom-header-api') { ?>
						<div class="updated">
							<p><?php echo wptexturize(__('To upload your own custom header graphic, click the <a href="themes.php?page=custom-header">Custom Image Header</a> tab.', 'fauna')); ?></p>
						</div>
						<?php } ?>
					</td>
				</tr>
				<?php if (get_option('fauna_header') != 'no-header') { ?>
				<tr valign="top">
					<th><?php _e('Header Extras', 'fauna'); ?></th>
					<td>
						<input id="fauna-height" name="fauna[height]" value="<?php echo stripslashes(get_option('fauna_height')); ?>" /> <?php _e('Height (px)', 'fauna'); ?><br />
						<label><input id="fauna-rounded" name="fauna[rounded]" type="checkbox" value="1" <?php checked('1', get_option('fauna_rounded')); ?> /> <?php _e('Round top header corners (requires JavaScript)', 'fauna'); ?></label>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top">
					<th><?php _e('Custom Background', 'fauna'); ?></th>
					<td><input id="fauna-background" name="fauna[background]" value="<?php echo stripslashes(get_option('fauna_background')); ?>" /><br />
					<?php _e('Type in the full URL to the background graphic.','fauna'); ?>
					</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" value="<?php _e('Update Options', 'fauna'); ?> &raquo;" />
			</p>
			
			<h2><?php _e('Uninstall Fauna', 'fauna'); ?></h2>
			<p><?php _e('If you want to uninstall Fauna, press the "Delete Options " button to clean things up in the database. You will be redirected to Presentation section and the Default theme will have been activated.', 'fauna'); ?></p>
			
			<p class="submit">
				<input id="uninstall" name="uninstall" type="submit" value="<?php _e('Delete Options', 'fauna'); ?> &raquo;" />
			</p>
		</div>

	</form>
</div>