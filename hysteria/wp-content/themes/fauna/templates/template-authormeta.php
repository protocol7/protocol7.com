<?php /*

	Authormeta Template
	This template holds meta information for author pages. 
	It is included by sidebar.php.

*/ ?>
<h4><?php _e('Author Archive','fauna'); ?></h4>
<p><?php _e('This page details authors of this weblog.','fauna'); ?></p>

<?php /* Fix an annoying WP bug where wp_list_authors borks when there's only one user */
$numauthors = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->users");
if ($numauthors > 1) { ?> 
<p><?php printf(__('There are %s authors/users attached to this weblog:','fauna'), $num_authors) ?></p>
<ul>
	<?php wp_list_authors('optioncount=1&feed=RSS&exclude_admin=0'); ?>
</ul>
<?php } else { ?>
<p><?php _e('There is one author attached to this weblog.','fauna'); ?></p>
<?php } ?>