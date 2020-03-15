<?php
/**
 * The template for displaying all pages
 * Template Name: Gallary Page
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
$header_heading = get_field('header_heading');
$header_content = get_field('header_content');
$background_image = get_field('background_image');
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php
			include('header-style.php');
			?>
			
		
		<section class="gallery-grid">
			<div class="container"><div id="postsLazyload">
		<?php
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				// $cat_id = get_query_var('cat');
				$cat_id = $_GET['gallery_cat'];
				if($cat_id != ''){
				
						$args = array(
					'posts_per_page' =>6,
					'order' => 'DESC',
					'order_by' => 'date',
					'post_type'=>'galleries',
					
					// 'post__not_in' => $exclude_posts,
					'paged' => $paged,				'tax_query' => array(
              array(
                'taxonomy' => 'gallery_cat',
                'field' => 'id',
                'terms' => $cat_id,
            )
        )
		
				);
				
				}else{
				$args = array(
				'post_type'=>'galleries',
					'posts_per_page' =>6,
					'order' => 'DESC',
					'order_by' => 'date',
					'cat' => $cat_id,
					// 'post__not_in' => $exclude_posts,
					'paged' => $paged,
				);}
				$posts_query = new WP_Query( $args );
				if ( $posts_query->have_posts() ): ?>
		
		<input type="hidden" id="WPTotalNumPages" value="<?php echo $posts_query->max_num_pages; ?>" />
					<input type="hidden" id="WPPerPage" value="<?php echo get_option('posts_per_page'); ?>" />
					<div class="post-filter-section-wrapper" style="padding-top: 0px;">
						<div class="post-filter-section">
							<input type="hidden" id="categoryId" value="" />
							<!--div class="filter-text">Filter By: </div-->
							<div class="filter-button-wrap">
								<a href="javascript:void(0);" class="brown-button filter-button" id=""><span class="filter-button-text text">All</span></a>
								<?php
								$terms = get_terms( 'gallery_cat', 'orderby=name' );
								foreach ( $terms as $term ) :
									echo '<a href="javascript:void(0);" class="brown-button filter-button" id="' . $term->term_id . '"><span class="filter-button-text text">' . $term->name . '</span></a>';
								endforeach
								?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="post_section" id="post_section<?php echo $paged; ?>">
					<div class="row">
						<input type="hidden" class="WPTotalNumPages2" value="<?php echo $posts_query->max_num_pages; ?>" />
						<?php $bi = 1; while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
				<div class="col-md-6">
				<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-listing-image');
								if($featured_img_url){
									$featured_img_style = 'background-image: url('.$featured_img_url.');';
								} ?>
		<div class="gallery-post">
		<div class="gallery-post-img" style="<?php echo $featured_img_style; ?>"></div>
		<div class="content">
		<h4><?php the_title(); ?></h4>
		<?php echo word_limit(get_the_excerpt(), 120); ?>
		</div>
		</div>
		</div>
		<?php echo (($bi%2)==0)?'<div class="clearfix"></div>':''; ?>
						<?php $bi++; endwhile; ?>
		</div></div>
		<?php endif; wp_reset_query(); ?></div>
		<div class="clearfix"></div>

	<?php if ( $posts_query->max_num_pages > 1 ) : ?>
		<div class="clearfix"></div>
		<div class="container">
			<div class="loadmoreanchor-wrap"><a href="javascript:void(0);" class="blue-button green-bg-btn more-btn btn" id="loadmoreanchor">Load More</a></div>
		</div>
	<?php endif; ?>
	<div class="clearfix" style="height: 70px;"></div>
		
		</div>
		</section>


		</main><!-- #main -->
	</div><!-- #primary -->
<script>
		var page = 2;
		var perPage = jQuery('#WPPerPage').val();

		// Ajax Pagination
		jQuery(function(){
			 jQuery('#loadmoreanchor').click(function(){
				jQuery('#loadmoreanchor .text').html('LOADING POSTS, PLEASE WAIT..');
				if((jQuery('#categoryId').val())){
					var load_url = '?paged=' + page + '&gallery_cat=' + jQuery('#categoryId').val();
					console.log(load_url);
				} else {
					var load_url = '?paged=' + page; console.log('load_url');console.log(load_url);
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
				var load_url = '?paged=' + page + '&gallery_cat=' + currentCategory;
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
<?php
get_footer();
