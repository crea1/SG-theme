<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Shebagems
 * @since Shebagems 0.1
 */
?>

<div id="sidebar">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sidebar") ) : ?>
    
    <?php endif; ?>
	<!--<a href="http://www.shebagems.com/shop"><img src="<?php echo get_bloginfo('template_url'); ?>/images/webshop.png" alt="Webshop" title="Webshop" /></a>-->
</div>