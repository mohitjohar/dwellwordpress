<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Dwell44
 */

get_header();
?>
<?php while ( have_posts() ) :
	the_post(); 
	$featured_img_url = get_the_post_thumbnail_url(get_the_ID()); 
	$user_id = get_the_author_meta('ID');
	if($featured_img_url){
				$featured_img_style = 'background-image: url('.$featured_img_url.');';
			}?>
		<div class="blog-page-hero-wrapper" style="<?php echo $featured_img_style; ?> background-repeat: no-repeat; background-size:cover;">
			<?php //include(get_template_directory().'/template-parts/parts/header.php'); ?>
			<div class="overlay"></div>
			<div class="container">
				<div class="blog-page-hero-content-wrapper">
					<h2 class="blog-page-hero-header"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="blog-page-hero-meta"><img class="author-img" src="<?php echo get_avatar_url($user_id); ?>"  /> <p> BY <?php echo get_the_author_meta('display_name'); ?><span>|</span><?php echo get_the_date('F j, Y'); ?></p></div>
				</div>
				<div class="blog-page-hero-down-button-wrraper"><span class="icon-circular-arrow-down icon"></span></div>
			</div>
				<img src="<?php bloginfo('template_url');?>/img/arrowblog.png" class="arrowimg">
			</div>
		<div class="container">
		<div class="postcontentsec">
		<div class="authorsection desk">
							<?php      echo get_avatar( get_the_author_email(), '100' );?>		
								<br/>
									<span class="authe"><?php the_author(); ?></span>
							<p><?php echo nl2br(get_the_author_meta('description')); ?></p>

<div class="author-social-links">
    <?php echo cfw_get_user_social_links(); ?>
</div></div>
		<?php the_content('');?>
		<div class="authorsection mobilh">
							<?php      echo get_avatar( get_the_author_email(), '100' );?>		
								<br/>
									<span class="authe"><?php the_author(); ?></span>
							<p><?php echo nl2br(get_the_author_meta('description')); ?></p>

<div class="author-social-links">
    <?php echo cfw_get_user_social_links(); ?>
</div></div>
			<div class="share-social-wrap">
					<?php echo social_sharing_buttons(); ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="container">
				<?php if ( comments_open() || get_comments_number() ) :
					comments_template();
			   endif; ?>
		   </div>

		
	</div><?php endwhile; // End of the loop.
		?>
	</div>

<?php
get_footer();
