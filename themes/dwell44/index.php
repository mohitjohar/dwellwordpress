<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package dwell44
 */

get_header();
	$exclude_posts = array();
	$cat_id = '';
	if(is_category()):
		$category = get_category( get_query_var( 'cat' ) );
		$cat_id = $category->term_id;
	endif; ?>

	<!-- Featured Post -->
	<?php $args = array(
					'posts_per_page' 	=> 1,
					'meta_key' 			=> '_is_ns_featured_post',
					'meta_value' 		=> 'yes',
					'cat'				=> $cat_id,
				);
	$featured_post = new WP_Query( $args );
	if ( $featured_post->have_posts() ) :
		while ( $featured_post->have_posts() ) : $featured_post->the_post();
			$exclude_posts[] = $post->ID;
			$post_id = $post->ID;
			$featured_img_url = get_the_post_thumbnail_url($post_id, 'large');
			$user_id = get_the_author_meta('ID');
			if($featured_img_url){
				$featured_img_style = 'background-image: url('.$featured_img_url.');';
			} ?>
			<div class="blog-page-hero-wrapper" style="<?php echo $featured_img_style; ?> background-repeat: no-repeat; background-size:cover;">
				<?php //include(get_template_directory().'/template-parts/parts/header.php'); ?>
				<div class="overlay"></div>
				<div class="container">
					<div class="blog-page-hero-content-wrapper">
						<h2 class="blog-page-hero-header"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="blog-page-hero-content"><?php echo word_limit(get_the_excerpt(), 180); ?></div>
						<div class="blog-page-hero-meta"><img class="author-img" src="<?php echo get_avatar_url($user_id); ?>"  /> <p> BY <?php echo get_the_author_meta('display_name'); ?><span>|</span><?php echo get_the_date('F j, Y'); ?></p></div>
						<div class="blog-page-hero-button-wrapper">
							<a class="btn btn-lg green-bg-btn" href="<?php the_permalink(); ?>">READ MORE</a>
						</div>
					</div>
					<div class="blog-page-hero-down-button-wrraper"><span class="icon-circular-arrow-down icon"></span></div>
				</div>
				<img src="<?php bloginfo('template_url');?>/img/arrowblog.png" class="arrowimg">
			</div>
		<?php endwhile;
		wp_reset_query();
	endif; ?>
	<div class="clearfix"></div>

	<!-- Featured Post Loop -->
	<div class="post-section-wrapper">
		<div class="container">
			<div id="postsLazyload">
				<?php
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				// $cat_id = get_query_var('cat');
				$cat_id = $_GET['category'];
				$args = array(
					'posts_per_page' => get_option('posts_per_page'),
					'order' => 'DESC',
					'order_by' => 'date',
					'cat' => $cat_id,
					// 'post__not_in' => $exclude_posts,
					'paged' => $paged,
				);
				$posts_query = new WP_Query( $args );
				if ( $posts_query->have_posts() ): ?>
					<input type="hidden" id="WPTotalNumPages" value="<?php echo $posts_query->max_num_pages; ?>" />
					<input type="hidden" id="WPPerPage" value="<?php echo get_option('posts_per_page'); ?>" />
					<div class="post-filter-section-wrapper">
						<div class="post-filter-section">
							<input type="hidden" id="categoryId" value="" />
							<!--div class="filter-text">Filter By: </div-->
							<div class="filter-button-wrap">
								<a href="javascript:void(0);" class="brown-button filter-button" id=""><span class="filter-button-text text">All</span></a>
								<?php
								$terms = get_terms( 'category', 'orderby=name' );
								foreach ( $terms as $term ) :
									echo '<a href="javascript:void(0);" class="brown-button filter-button" id="' . $term->term_id . '"><span class="filter-button-text text">' . $term->name . '</span></a>';
								endforeach
								?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="post_section" id="post_section<?php echo $paged; ?>">
						<input type="hidden" class="WPTotalNumPages2" value="<?php echo $posts_query->max_num_pages; ?>" />
						<?php $bi = 1; while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
							<div class="blog-article-block">
								<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-listing-image');
								if($featured_img_url){
									$featured_img_style = 'background-image: url('.$featured_img_url.');';
								} ?>
								<div class="blog-article-image" style="<?php echo $featured_img_style; ?>"> </div>
								<div class="blog-article-content-wrapper">
									<h3 class="blog-article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>									
									<div class="blog-article-meta"> <p>BY <?php echo get_the_author_meta('display_name'); ?> <span>|</span> <?php echo get_the_date('F j, Y'); ?> </p></div>
									<div class="blog-article-content"><?php echo word_limit(get_the_excerpt(), 220); ?></div>
									<div class="blog-article-button-wrapper">
										<a class="buttonread-btn" href="<?php the_permalink(); ?>">READ MORE <img src="<?php bloginfo('template_url');?>/img/greenarrow.png"></a>
									</div>
								</div>
							</div>
							<?php echo (($bi%2)==0)?'<div class="clearfix"></div>':''; ?>
						<?php $bi++; endwhile; ?>
					</div>
				<?php endif; wp_reset_query(); ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<?php if ( $posts_query->max_num_pages > 1 ) : ?>
		<div class="clearfix"></div>
		<div class="container">
			<div class="loadmoreanchor-wrap"><a href="javascript:void(0);" class="blue-button green-bg-btn btn" id="loadmoreanchor">LOAD MORE POSTS </a></div>
		</div>
	<?php endif; ?>
	<div class="clearfix" style="height: 70px;"></div>

	<script>
		var page = 2;
		var perPage = jQuery('#WPPerPage').val();

		// Ajax Pagination
		jQuery(function(){
			 jQuery('#loadmoreanchor').click(function(){
				jQuery('#loadmoreanchor .text').html('LOADING POSTS, PLEASE WAIT..');
				if((jQuery('#categoryId').val())){
					var load_url = '?paged=' + page + '&product_cat=' + jQuery('#categoryId').val();
				} else {
					var load_url = '?paged=' + page;
				}
		 		jQuery('#postsLazyload').append(jQuery('<div class="post_section" id="post_section' + page + '">').load( load_url + ' .post_section > *', function() {
					jQuery('#loadmoreanchor .text').html('VIEW MORE POSTS');
					if(jQuery('#post_section' + page + ' .WPTotalNumPages2').val() <= page) {
		 				jQuery('.loadmoreanchor-wrap').css('display', 'none');
		 			} else {
						jQuery('.loadmoreanchor-wrap').css('display', 'block');
					}
					page++;
		 		}));
		 	});
		 });

		// Category Filter Ajax
		jQuery(function(){
			jQuery('.filter-button').click(function(){
				page = 1;
				var currentCategory = jQuery(this).attr('id');
				var load_url = '?paged=' + page + '&product_cat=' + currentCategory;
				console.log(load_url);
				jQuery('.post-filter-section a').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('#categoryId').val(currentCategory);
				jQuery( ".post_section" ).remove();
				jQuery('#postsLazyload').append('<div class="category-posts-loading">Loading... Please wait.</div>');
				jQuery('#postsLazyload').append(jQuery('<div class="post_section" id="post_section' + page + '">').load( load_url + ' .post_section > *', function() {
					jQuery('.category-posts-loading').remove();
					if(jQuery('#post_section' + page + ' .WPTotalNumPages2').val() <= page) {
		 				jQuery('.loadmoreanchor-wrap').css('display', 'none');
		 			} else {
						jQuery('.loadmoreanchor-wrap').css('display', 'block');
					}
					page++;
		 		}));
			});
		});
	</script>
<?php get_footer();