<?php get_header(); ?>

<!-- CONTENT -->
<div class="content">

		<div class="slider-wrapper theme-default">
			<div id="slider" class="nivoSlider">
				<img src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/images/Sheba-slider-1.jpg" alt="" />
				<img src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/images/Sheba-slider-2.jpg" alt="" />
				<img src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/images/Sheba-slider-3.jpg" alt="" />
				<img src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/images/Sheba-slider-4.jpg" alt="" />
				<img src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/images/Sheba-slider-5.jpg" alt="" />
			</div>
		</div>

	<div id="contentunder">
		<?php get_template_part( 'content', 'sidebar' ); ?>



		<div id="articles">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="frontpageArticle">

				<div class="postThumbnail">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
				</div>
				<div class="postContentTeaser">
					<h2 class="postTitle"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php printf(strip_tags(substr($post->post_content,0,256)) . "...")?></p>
					<p><a href="<?php the_permalink(); ?>">Read more</a></p>
				</div>

			</div>
			<?php endwhile; else: ?>
			<p><?php _e('There is currently no articles in this section.'); ?></p>
			<?php endif; ?>


		</div>
	</div>

</div>
<!-- END CONTENT -->

<?php get_footer(); ?>
