<?php
/**
* The template for displaying content in the single.php template
*
* @package WordPress
* @subpackage Shebagems
* @since Shebagems 0.1
*/
?>
	<div class="thumbnail-div">

		<?php 
			$width = 960;
		  $height = 287;
				  
		  $classtext = 'single-image';
		  $titletext = get_the_title();

		  $thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Image');
		  $thumb = $thumbnail["thumb"]; ?>

		<?php // if there's a thumbnail
		if($thumb != '') { ?>
			<?php 
			print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); 
			?>
			<?php }; ?>
	</div>
<?php get_template_part( 'content', 'sidebar' ); ?>	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->



	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'shebagems' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'shebagems' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->

