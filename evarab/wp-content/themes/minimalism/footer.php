<hr />
<div id="footer">
<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
	<p>
		<?php printf(__('%1$s powered by %2$s', 'minimalism'), get_bloginfo('name'),
		'<a href="http://wordpress.org/">WordPress</a>'); ?> | <a href="http://www.genaehr.com/minimalism/">minimalism</a> by <a href="http://www.genaehr.com/">www.genaehr.com</a>
		<br /><?php printf(__('%1$s and %2$s.', 'minimalism'), '<a href="' . get_bloginfo('rss2_url') . '">' . __('Entries (RSS)', 'minimalism') . '</a>', '<a href="' . get_bloginfo('comments_rss2_url') . '">' . __('Comments (RSS)', 'minimalism') . '</a>'); ?>
		<!-- <?php printf(__('%d queries. %s seconds.', 'minimalism'), get_num_queries(), timer_stop(0, 3)); ?> -->
	</p>
</div>
</div>

<!-- Gorgeous design by Michael Heilemann - http://binarybonsai.com/kubrick/ -->
<?php /* "Just what do you think you're doing Dave?" */ ?>

		<?php wp_footer(); ?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8401950-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>