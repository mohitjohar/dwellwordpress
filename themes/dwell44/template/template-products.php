<?php
/**
 * The template for displaying all pages
 * Template Name: Products Page
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
	?>
		
		
		<section class="product-grid"><div class="container">
		<div class="category-tiltle-a-desc">
			<?php $heading_under_header = get_field('heading_under_header');
			$content_section = get_field('content_section');
			if($heading_under_header) {
			?>
		<h2><?php echo $heading_under_header;?></h2>
			<?php } 
			
			if($content_section) { ?>
		<div class="text"><?php echo $content_section;?></div>
			<?php } ?>
		</div>	
		
		</div>
				<div class="product-section-wrapper">
		<div class="container">
			<div id="productLazyload" class="profiltersss">
				<?php
				//$cat_id = '';

				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				// $cat_id = get_query_var('cat');
				$cat_id = $_GET['category'];
				if($cat_id != ''){
					$args = array(
					'posts_per_page' => get_option('posts_per_page'),
					'order' => 'DESC',
					'order_by' => 'date',
					'post_type'=>'productpost',
					
					// 'post__not_in' => $exclude_posts,
					'paged' => $paged,				'tax_query' => array(
              array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $cat_id,
            )
        )
		
				);
				} else{
//echo 'else';
				$args = array(
					'posts_per_page' => get_option('posts_per_page'),
					'order' => 'DESC',
					'order_by' => 'date',
					'post_type'=>'productpost',
					//'cat' => $cat_id,
					// 'post__not_in' => $exclude_posts,
					'paged' => $paged,
				);
				}
				$posts_query = new WP_Query( $args );
				if ( $posts_query->have_posts() ): ?>
					<input type="hidden" id="WPTotalNumPages" value="<?php echo $posts_query->max_num_pages; ?>" />
					<input type="hidden" id="WPPerPage" value="<?php echo get_option('posts_per_page'); ?>" />
					<div class="product-filter-section-wrapper">
						<div class="product-filter-section">
							<input type="hidden" id="categoryId" value="" />
							<!--div class="filter-text">Filter By: </div-->
							<div class="filter-button-wrap">								
								<?php
								$terms = get_terms( 'product_cat', 'orderby=name' );
								foreach ( $terms as $term ) :
									echo '<a href="javascript:void(0);" class="brown-button filter-button" id="' . $term->term_id . '"><span class="filter-button-text text">' . $term->name . '</span></a>';
								endforeach
								?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="product_section" id="product_section<?php echo $paged; ?>"><div class="row">
						<input type="hidden" class="WPTotalNumPages2" value="<?php echo $posts_query->max_num_pages; ?>" />
						<?php $bi = 1; while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
							<div class="col-lg-4 col-md-6">
								<?php $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'blog-listing-image');
								if($featured_img_url){
									$featured_img_style = '<img src="'.$featured_img_url.'" class="product-img"/>';
								} ?>								
									<div class="product-post">
										<?php echo $featured_img_style; ?>
										<h6 class="product-title"><img src="/wp-content/uploads/2019/07/productimg.png"/><?php the_title();?></h6>
										<div class="product-expt"><?php echo word_limit(get_the_excerpt(), 220); ?></div>
									</div>
							</div>
							<?php echo (($bi%2)==0)?'<div class="clearfix"></div>':''; ?>
						<?php $bi++; endwhile; ?>
					</div>
					</div>
				<?php endif; wp_reset_query(); ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<?php if ( $posts_query->max_num_pages > 1 ) : ?>
		<div class="clearfix"></div>
		<div class="container">
			<div class="loadmoreanchor-wrap"><a href="javascript:void(0);" class="blue-button green-bg-btn btn" id="loadmoreanchor">LOAD MORE PRODUCTS </a></div>
		</div>
	<?php endif; ?>
	<div class="clearfix" style="height: 70px;"></div>

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
					var load_url = '?paged=' + page + '&category=' + jQuery('#categoryId').val();
				} else {
					var load_url = '?paged=' + page;
				}
		 		jQuery('#productLazyload').append(jQuery('<div class="product_section" id="product_section' + page + '">').load( load_url + ' .product_section > *', function() {
					jQuery('#loadmoreanchor .text').html('VIEW MORE PORDUCTS');
					if(jQuery('#product_section' + page + ' .WPTotalNumPages2').val() <= page) {
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
				var load_url = '?paged=' + page + '&category=' + currentCategory;
				jQuery('.product-filter-section a').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('#categoryId').val(currentCategory);
				jQuery( ".product_section" ).remove();
				jQuery('#productLazyload').append('<div class="category-posts-loading">Loading... Please wait.</div>');
				jQuery('#productLazyload').append(jQuery('<div class="product_section" id="product_section' + page + '">').load( load_url + ' .product_section > *', function() {
					jQuery('.category-posts-loading').remove();
					if(jQuery('#product_section' + page + ' .WPTotalNumPages2').val() <= page) {
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
