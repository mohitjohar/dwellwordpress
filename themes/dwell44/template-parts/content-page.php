<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dwell44
 */

?>
	<div class="top-sec services-top" style="background-image:url('/wp-content/uploads/2019/07/top-image.jpg');">
				<div class="banner">
				<div class="container">
					
					<div class="content">
					<h1><?php  the_title(); ?></h1>

					
					</div>
					
		<div class="empty-space"></div>		
</div>
			</div>
			</div>
<div class="container">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php dwell44_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dwell44' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'dwell44' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
</div>
