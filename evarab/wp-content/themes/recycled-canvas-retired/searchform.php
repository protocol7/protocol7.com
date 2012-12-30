<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
		<input id="search-input"type="text" value="<?php echo wp_specialchars($s, 1); ?>" style="<?php if(rr_ShowSidebar() == 'off') { echo "width:100px;"; } ?>" name="s" id="s"/>
		<input <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { echo "type=\"submit\" style=\"height:18px;\""; } else { echo "type=\"image\""; }?> id="search-button" id="searchsubmit" alt="search" value="/" src="<?php bloginfo('template_directory');?>/styles/images/link.png" />
</form>
