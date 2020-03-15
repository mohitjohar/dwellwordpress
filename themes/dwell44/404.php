<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Dwell44
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
<div class="top-sec services-top" style="background-image:url('/wp-content/uploads/2019/07/top-image.jpg');">
				<div class="banner">
				<div class="container">
					
					<div class="content">
					<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'dwell44' ); ?></h1>
					<h6><?php esc_html_e( 'It looks like nothing was found at this location. Please try again using below form or go to home.', 'dwell44' ); ?></h6>	
							<div class="searchformc">
					<h2>
						Search Here:
					</h2>
					<?php get_search_form();?>
				</div>
						<div class="back-home">
					<a href="/" class="btn brown-btn">Go To Home</a>
				</div>
					
					</div>
					
		<div class="empty-space"></div>		
</div>
			</div>
			</div>
		

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
