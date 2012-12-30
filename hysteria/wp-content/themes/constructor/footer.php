<?php
/**
 * @package WordPress
 * @subpackage Constructor
 */
?>
    </div><!-- id='wrapcontent' -->
    <div id="wrapfooter" class="wrapper">
    	<div id="footer">	
    		<?php
    	    // switch statement for widget place
    	    switch (true) {
    	        case (is_archive() && dynamic_sidebar('footer categories')):
    	            break;
    	        case (is_page() && dynamic_sidebar('footer pages')):
    	            break;
    	        case (is_single() && dynamic_sidebar('footer posts')):
    	            break;
                case (dynamic_sidebar('footer')):
    	            break;
    	        default:
    	            // nothing
    	            break;
    	    }
    	    ?>
        	<p class="clear copy">
            	<?php get_constructor_footer(); ?>
        	</p>
    	</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>