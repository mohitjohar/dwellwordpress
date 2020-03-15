<?php
/**
 * The template for displaying all pages
 * Template Name: Event Page
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dwell44
 */

get_header();
$background_image = get_field('background_image');
$header_title = get_field('header_heading');
	$header_content = get_field('header_content');
	$want_to_show = get_field('want_to_show');
	$intro_heading = get_field('intro_heading');
	$intro_content = get_field('intro_content');
	$intro_image = get_field('intro_image');
	  $partner_section_heading = get_field('partner_section_heading');
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			
			<div class="top-sec services-top" style="background-image:url(<?php echo $background_image['url'];?>);">
				<div class="banner">
				<div class="container">					
					<div class="content">
						<?php if($header_title){ ?>
					<h1><?php echo $header_title;?></h1>
						<?php } 
						if($header_content){
						?>
					<h6><?php echo $header_content;?> </h6>
						<?php } ?>
					</div>
					<?php  
					if($partner_section_heading){ 
					?>
					<h6 class="text-uppercase"><?php echo $partner_section_heading;?></h6>
					<?php } 
					
					if( have_rows('partner_logo_section') ):
							?>
					<div class="green-bg-logo">
					<?php
						while ( have_rows('partner_logo_section') ) : the_row();
							$aded_logo = get_sub_field('aded_logo');
							?>
						<img src="<?php echo $aded_logo['url'];?>" alt="<?php echo $aded_logo['alt'];?>"/>
						<?php
							
						endwhile;
							?>
						</div>
						<?php
					else :
					endif;

					?>
				</div>
			</div>
			</div>
			

		
			<section class="post-latest-grid container">
				<h2>Latest Articles</h2>
			<div class="row">
			<div class="col-lg-4 col-md-6">
			<div class="post-sec">
			<div class="latest-img"><a href="#"><img src="/wp-content/uploads/2019/07/pst_img_1.jpg"/></a></div>
			<a href="#"><h5 class="latest-title">20 Stunning Examples of Modern and Sustainable Home Design Across the USA</h5></a>
			<div class="post-prgrph-desc">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis 

ipsum suspendisse ultrices gravida.</p>
			</div>
			<a href="" class="post-btn">READ MORE <i class="fa fa-chevron-right circle"></i></a>
			</div>
			</div>
			<div class="col-lg-4 col-md-6">
			<div class="post-sec">
			<div class="latest-img"><a href="#"><img src="/wp-content/uploads/2019/07/pst_img.jpg"/></a></div>
			<a href="#"><h5 class="latest-title">The Top 10 Things You Can do Right Now to Make Your Home More Green</h5></a>
			<div class="post-prgrph-desc">
			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum 

suspendisse ultrices gravida.</p>
			</div>
			<a href="" class="post-btn">READ MORE <i class="fa fa-chevron-right circle"></i></a>
			</div>
			</div>
			<div class="col-lg-4 col-md-6">
			<div class="post-sec">
			<div class="latest-img"><a href="#"><img src="/wp-content/uploads/2019/07/pst_img2.jpg"/></a></div>
			<a href="#"><h5 class="latest-title">The Top 10 Things You Can do Right Now to Make Your Home More Green</h5></a>
			<div class="post-prgrph-desc">
			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum 

suspendisse ultrices gravida.</p>
			</div>
			<a href="" class="post-btn">READ MORE <i class="fa fa-chevron-right circle"></i></a>
			</div>
			</div>
			</div>
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
