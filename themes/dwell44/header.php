<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dwell44
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/wp-content/themes/dwell44/css/slick.css">
	<link rel="stylesheet" href="/wp-content/themes/dwell44/css/slick-theme.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dwell44' ); ?></a>
<?php 	
	if(is_home()){
	?>	
	<header id="masthead" class="site-header">
	<nav class="lighbghea navbar navbar-expand-lg navbar-light fixed-top">
	<div class="container">
  <?php the_custom_logo(); ?>
 <div class="socialmedia">
	<a href="<?php the_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/facebook.png"></a>	
	<a href="<?php the_field('instagram_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/intagram.png"></a>	
	<a href="<?php the_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/houzz.png"></a>
	<a href="<?php the_field('google_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/google.png"></a>
		</div>
<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'container'       => false, 'menu_id' => 'headermenu', 'menu_class'=>'navbar-nav ml-auto',
			) );
			?>

  </div>
 
</nav>
</header>
<?php
	} else{ ?>	
<header id="masthead" class="site-header">
	<nav class="darkligbg navbar navbar-expand-lg navbar-light fixed-top">
	<div class="container">
  <?php the_custom_logo(); ?>

<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'container'       => false, 'menu_id' => 'headermenu', 'menu_class'=>'navbar-nav mr-auto',
			) );
			?>

 
  <span class="socialmedia iconsss">
	<a href="<?php the_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/facebook.png"></a>	
	<a href="<?php the_field('instagram_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/intagram.png"></a>	
	<a href="<?php the_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/houzz.png"></a>
	<a href="<?php the_field('google_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/google.png"></a>
		
		 </span> </div>
		 
</nav>
</header>
<?php }  ?>



	<div id="content" class="site-content">
