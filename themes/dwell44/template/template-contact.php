<?php
/**
 * The template for displaying all pages
 * Template Name: Contact Us Page
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
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php
include('header-style.php');
// check if the flexible content field has rows of data
if( have_rows('contact_page_sections') ):

     // loop through the rows of data
    while ( have_rows('contact_page_sections') ) : the_row();

       
	   if( get_row_layout() == 'contact_form' ): 

        	$form_shortcode = get_sub_field('form_shortcode');
			?>
				<section class="contactpageform">			
			<?php echo do_shortcode($form_shortcode);?>
			</section>
			<?php

        endif;

    endwhile;

else :

    // no layouts found

endif;

?>
			
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
