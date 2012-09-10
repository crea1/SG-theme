<!DOCTYPE HTML>

<html>
	<head>
		<title><?php bloginfo('name'); ?></title>

      <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	    <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
			<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/reset.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />		
			<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
			<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
      <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	    <link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	    <link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	    <link rel="icon" type="image/png" href="http://www.shebagems.com/favicon.ico">
	    
	    
	    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/scripts/jquery-1.7.1.min.js"></script>
	    <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/nivo-slider/jquery.nivo.slider.pack.js"></script>
	    <script type="text/javascript">
		    $(window).load(function() {
		        $('#slider').nivoSlider();
		    });
	    </script>
      <?php wp_head(); ?>
	</head>
	<body>
		<div class="page">
			<!-- HEADER -->
			<div class="header">
				<div class="logopic">
					<a href="<?php echo site_url();?>"><img src="<?php bloginfo('template_url'); ?>/images/logo.jpg" alt="Sheba Gems" title="Sheba Gems"/></a>

				</div>

			</div>
			<!-- END HEADER -->

<div id="access">
	<?php wp_nav_menu(array(
	‘theme_location’ => ‘main-menu’,
	‘menu_class’ => ‘dropdown’,
	‘container_id’ => ‘navigation’,
	‘fallback_cb’ => ‘wp_page_menu’
	)); ?>
</div>			
