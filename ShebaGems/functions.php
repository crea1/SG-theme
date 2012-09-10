<?php
/**
 * User: Marius Haugli Kristensen
 * Date: 12.11.11
 * Time: 16:05
 */

/**
* Post thumbnails support
*/
add_theme_support( 'post-thumbnails' );
// Width 960px height unlimited
add_image_size( 'big-thumb', 960, 9999, true );
/**
* Register menu support
*/
register_nav_menu( 'primary', __( 'Primary Menu', 'shebagems' ) );

/** 
* Register sidebars
*/
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Sidebar',
        'before_widget' => '<div class="sidebar-box">',
		'after_widget' => '<div class="sidebar-box-bottom"></div></div>',
		'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
		'id' => 'footer-area-1',
		'name' => 'Footer Column 1',
		'before_widget' => '<div class="footercontent">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
    ));   
    
    register_sidebar(array(
		'id' => 'footer-area-2',
		'name' => 'Footer Column 2',
		'before_widget' => '<div class="footercontent">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
    ));     
    
    register_sidebar(array(
		'id' => 'footer-area-3',
		'name' => 'Footer Column 3',
		'before_widget' => '<div class="footercontent">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
    ));     
    
    register_sidebar(array(
		'id' => 'footer-area-4',
		'name' => 'Footer Column 4',
		'before_widget' => '<div class="footercontent">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
    ));     

/**
*
*
* --- BELOW THIS LINE ARE CUSTOM FUNCTIONS :D
*
*/



/* this function gets thumbnail from Post Thumbnail or Custom field or First post image */
if ( ! function_exists( 'get_thumbnail' ) ){
	function get_thumbnail($width=100, $height=100, $class='', $alttext='', $titletext='', $fullpath=false, $custom_field='', $post='')
	{
		if ( $post == '' ) global $post;
		global $shortname;
		
		$thumb_array['thumb'] = '';
		$thumb_array['use_timthumb'] = true;
		if ($fullpath) $thumb_array['fullpath'] = ''; //full image url for lightbox
		
		$new_method = true;
		
		if ( has_post_thumbnail( $post->ID ) && !( '' != $custom_field && get_post_meta( $post->ID, $custom_field, true ) ) ) {
			$thumb_array['use_timthumb'] = false;
			
			$et_fullpath =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$thumb_array['fullpath'] =  $et_fullpath[0];
			$thumb_array['thumb'] = $thumb_array['fullpath'];
		}

		if ($thumb_array['thumb'] == '') {
			if ($custom_field == '') $thumb_array['thumb'] = esc_attr( get_post_meta($post->ID, 'Thumbnail', $single = true) );
			else {
				$thumb_array['thumb'] = esc_attr( get_post_meta($post->ID, $custom_field, $single = true) );
				if ($thumb_array['thumb'] == '') $thumb_array['thumb'] = esc_attr( get_post_meta($post->ID, 'Thumbnail', $single = true) );
			}
			
			if (($thumb_array['thumb'] == '') && ((get_option($shortname.'_grab_image')) == 'on')) { 
				$thumb_array['thumb'] = esc_attr( et_first_image() );
				if ( $fullpath ) $thumb_array['fullpath'] = $thumb_array['thumb'];
			}
			
			#if custom field used for small pre-cropped image, open Thumbnail custom field image in lightbox
			if ($fullpath) {
				$thumb_array['fullpath'] = $thumb_array['thumb'];
				if ($custom_field == '') $thumb_array['fullpath'] = apply_filters('et_fullpath', et_path_reltoabs(esc_attr($thumb_array['thumb'])));
				elseif ( $custom_field <> '' && get_post_meta($post->ID, 'Thumbnail', $single = true) ) $thumb_array['fullpath'] = apply_filters( 'et_fullpath', et_path_reltoabs(esc_attr(get_post_meta($post->ID, 'Thumbnail', $single = true))) );
			}
		}
		
		return $thumb_array;
	}
}



/* this function prints thumbnail from Post Thumbnail or Custom field or First post image */
if ( ! function_exists( 'print_thumbnail' ) ){
	function print_thumbnail($thumbnail = '', $use_timthumb = true, $alttext = '', $width = 100, $height = 100, $class = '', $echoout = true, $forstyle = false, $resize = true, $post='') {
		global $shortname;
		if ( $post == '' ) global $post;
		
		$output = '';
		$thumbnail_orig = $thumbnail;
		
		$thumbnail = et_multisite_thumbnail( $thumbnail );
		
		$cropPosition = '';
		
		$allow_new_thumb_method = false;
		
		$new_method = true;
		$new_method_thumb = '';
		$external_source = false;
			
		$allow_new_thumb_method = !$external_source && $new_method && $cropPosition == '';
		
		if ( $allow_new_thumb_method && $thumbnail <> '' ){
			$et_crop = get_post_meta( $post->ID, 'et_nocrop', true ) == '' ? true : false; 
			$new_method_thumb =  et_resize_image( et_path_reltoabs($thumbnail), $width, $height, $et_crop );
			if ( is_wp_error( $new_method_thumb ) ) $new_method_thumb = '';
		}
		
		if ($forstyle === false) {
			$output = '<img src="' . esc_url( $new_method_thumb ) . '"';
			
			if ($class <> '') $output .= " class='" . esc_attr( $class ) . "' ";

			$output .= " alt='" . esc_attr( strip_tags( $alttext ) ) . "' />";
			
			if (!$resize) $output = $thumbnail;
		} else {
			$output = $new_method_thumb;
		}
		
		if ($echoout) echo $output;
		else return $output;
	}
}

if ( ! function_exists( 'et_multisite_thumbnail' ) ){
	function et_multisite_thumbnail( $thumbnail = '' ) {
		// do nothing if it's not a Multisite installation or current site is the main one
		if ( is_main_site() ) return $thumbnail;
		
		# get the real image url
		preg_match( '#([_0-9a-zA-Z-]+/)?files/(.+)#', $thumbnail, $matches );
		if ( isset( $matches[2] ) ){
			$file = rtrim( BLOGUPLOADDIR, '/' ) . '/' . str_replace( '..', '', $matches[2] );
			if ( is_file( $file ) ) $thumbnail = str_replace( ABSPATH, get_site_url( 1 ), $file );
			else $thumbnail = '';
		}

		return $thumbnail;
	}
}

if ( ! function_exists( 'et_resize_image' ) ){
	function et_resize_image( $thumb, $new_width, $new_height, $crop ){
		if ( is_ssl() ) $thumb = preg_replace( '#^http://#', 'https://', $thumb );
		$info = pathinfo($thumb);
		$ext = $info['extension'];
		$name = wp_basename($thumb, ".$ext");
		$is_jpeg = false;
		$site_uri = apply_filters( 'et_resize_image_site_uri', site_url() );
		$site_dir = apply_filters( 'et_resize_image_site_dir', ABSPATH );
		
		#get main site url on multisite installation 
		if ( is_multisite() ){
			switch_to_blog(1);
			$site_uri = site_url();
			restore_current_blog();
		}
		
		if ( 'jpeg' == $ext ) {
			$ext = 'jpg';
			$name = preg_replace( '#.jpeg$#', '', $name );
			$is_jpeg = true;
		}
		
		$suffix = "{$new_width}x{$new_height}";
		
		$destination_dir = '' != get_option( 'et_images_temp_folder' ) ? preg_replace( '#\/\/#', '/', get_option( 'et_images_temp_folder' ) ) : null;
		
		$matches = apply_filters( 'et_resize_image_site_dir', array(), $site_dir );
		if ( !empty($matches) ){
			preg_match( '#'.$matches[1].'$#', $site_uri, $site_uri_matches );
			if ( !empty($site_uri_matches) ){
				$site_uri = str_replace( $matches[1], '', $site_uri );
				$site_uri = preg_replace( '#/$#', '', $site_uri );
				$site_dir = str_replace( $matches[1], '', $site_dir );
				$site_dir = preg_replace( '#\\\/$#', '', $site_dir );
			}
		}
		
		#get local name for use in file_exists() and get_imagesize() functions
		$localfile = str_replace( apply_filters( 'et_resize_image_localfile', $site_uri, $site_dir, et_multisite_thumbnail($thumb) ), $site_dir, et_multisite_thumbnail($thumb) );
		
		$add_to_suffix = '';
		if ( file_exists( $localfile ) ) $add_to_suffix = filesize( $localfile ) . '_';
		
		#prepend image filesize to be able to use images with the same filename
		$suffix = $add_to_suffix . $suffix;
		$destfilename_attributes = '-' . $suffix . '.' . $ext;
		
		$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
		$checkfilename .= $destfilename_attributes;
		
		if ( $is_jpeg ) $checkfilename = preg_replace( '#.jpeg$#', '.jpg', $checkfilename );
		
		$uploads_dir = wp_upload_dir();
		$uploads_dir['basedir'] = preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] );
		
		if ( null !== $destination_dir && '' != $destination_dir && apply_filters('et_enable_uploads_detection', true) ){
			$site_dir = trailingslashit( preg_replace( '#\/\/#', '/', $uploads_dir['basedir'] ) );
			$site_uri = trailingslashit( $uploads_dir['baseurl'] );
		}
		
		#check if we have an image with specified width and height
		
		if ( file_exists( $checkfilename ) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );

		$size = @getimagesize( $localfile );
		if ( !$size ) return new WP_Error('invalid_image_path', __('Image doesn\'t exist'), $thumb);
		list($orig_width, $orig_height, $orig_type) = $size;
		
		#check if we're resizing the image to smaller dimensions
		if ( $orig_width > $new_width || $orig_height > $new_height ){
			if ( $orig_width < $new_width || $orig_height < $new_height ){
				#don't resize image if new dimensions > than its original ones
				if ( $orig_width < $new_width ) $new_width = $orig_width;
				if ( $orig_height < $new_height ) $new_height = $orig_height;
				
				#regenerate suffix and appended attributes in case we changed new width or new height dimensions
				$suffix = "{$add_to_suffix}{$new_width}x{$new_height}";
				$destfilename_attributes = '-' . $suffix . '.' . $ext;
				
				$checkfilename = ( '' != $destination_dir && null !== $destination_dir ) ? path_join( $destination_dir, $name ) : path_join( dirname( $localfile ), $name );
				$checkfilename .= $destfilename_attributes;
				
				#check if we have an image with new calculated width and height parameters
				if ( file_exists($checkfilename) ) return str_replace( $site_dir, trailingslashit( $site_uri ), $checkfilename );
			}
			
			#we didn't find the image in cache, resizing is done here
			$result = image_resize( $localfile, $new_width, $new_height, $crop, $suffix, $destination_dir );
				
			if ( !is_wp_error( $result ) ) {
				#transform local image path into URI
				
				if ( $is_jpeg ) $thumb = preg_replace( '#.jpeg$#', '.jpg', $thumb);
				
				$site_dir = str_replace( '\\', '/', $site_dir );
				$result = str_replace( '\\', '/', $result );
				$result = str_replace( $site_dir, trailingslashit( $site_uri ), $result );
			}
			
			#returns resized image path or WP_Error ( if something went wrong during resizing )
			return $result;
		}
		
		#returns unmodified image, for example in case if the user is trying to resize 800x600px to 1920x1080px image
		return $thumb;
	}
}

if ( ! function_exists( 'et_path_reltoabs' ) ){
	function et_path_reltoabs( $imageurl ){
		if ( strpos(strtolower($imageurl), 'http://') !== false || strpos(strtolower($imageurl), 'https://') !== false ) return $imageurl;
		
		if ( strpos( strtolower($imageurl), $_SERVER['HTTP_HOST'] ) !== false )
			return $imageurl;
		else {
			$imageurl = apply_filters( 'et_path_relative_image', site_url() . '/' ) . $imageurl;
		}
		
		return $imageurl;
	}
}
?>
