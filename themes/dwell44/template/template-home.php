<?php
/**
 * The template for displaying all pages
 * Template Name: Home Page
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
			//include
if( have_rows('page_sections') ):

     // loop through the rows of data
    while ( have_rows('page_sections') ) : the_row();

        if( get_row_layout() == 'logo_section' ):
		
		?>
		<section class="logo-slider-sec container d-none-sm">
		<h5 class="text-center">	<?php

        	the_sub_field('section_heading');
			?></h5>
			<?php
				if( have_rows('logos','options') ):

			 	echo '<div class="logo-slider text-center">';

			 	// loop through the rows of data
			    while ( have_rows('logos','options') ) : the_row();

					$image = get_sub_field('add_logo_image');

					echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';

				endwhile;

				echo '</div>';

			endif;
			?>
	
								
			</section>
			<?php
      endif;
      if( get_row_layout() == 'product_category_sec' ): 
	  ?>
	  <section class="post-grid container">
			<div class="row">
	
		
		
	  <?php

        	 $add_categories = get_sub_field('add_categories');
				foreach($add_categories as $procat){
					 $slug = $procat->slug;
					$terms = get_terms($slug);

					$woo_cat_id = $procat->term_id;
					$catlink = get_term_link( $woo_cat_id, 'product_cat' );
			
				
				$term = get_queried_object();


// vars
 $image = get_field('add_categy_image', 'product_cat_'.$woo_cat_id);
  $category_icon = get_field('category_icon', 'product_cat_'.$woo_cat_id);
				
				?>
					<div class="col-lg-4 col-md-6">
			<div class="post-sec">
			<div class="post-img"><a href="<?php echo $catlink;?>"><img src="<?php echo $image['url'];?>"/></a></div>
			<a href="<?php echo $catlink;?>"><h5 class="post-title"><img src="<?php echo $category_icon['url'];?>"/><?php echo $procat->name;?></h5></a>
			<div class="post-desc">
			<?php echo $procat->description;?>
			</div>
			<a href="<?php echo $catlink;?>" class="post-btn">Learn More <i class="fa fa-chevron-right circle"></i></a>
			</div>
			</div>
				<?php
				
				}
?>
	</div>
			</section>
<?php
 endif;
      if( get_row_layout() == 'images_section_with_summary' ): 
	   $section_heading = get_sub_field('section_heading');
	   $section_content = get_sub_field('section_content');
	   $button_name = get_sub_field('button_name');
	   $button_link = get_sub_field('button_link');
	  ?>
	  		<section class="left-two-images">
			<?php

// check if the repeater field has rows of data
if( have_rows('add_images') ):

 	?>
	<div class="two-images-banner">
			
	<?php
    while ( have_rows('add_images') ) : the_row();

        // display a sub field value
        $add_image = get_sub_field('add_image');
?>
<img src="<?php echo $add_image['url'];?>"/>
<?php
    endwhile;
	?>
	</div>
	<?php

else :

    // no rows found

endif;
$add_class = get_sub_field('add_class');
if($add_class == 'corner-top-left'){
	$aclass="corner-top-left";
}else{
	$aclass="corner-bottom-left";
}
?>
			
			<div class="container">
			<div class="<?php echo $aclass;?>">
			<div class="shape"></div>
			<?php if($section_heading){ ?>
			<h3><?php echo $section_heading;?></h3><?php } ?>
			<div class="text">
			<?php echo $section_content;?>
			</div>
			<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
			</div>
			</div>
		</section>
	  <?php
endif;
      if( get_row_layout() == 'right_image_with_left_content' ): 
		$right_side_image = get_sub_field('right_side_image');
	    $person_sign = get_sub_field('person_sign');
		$person_name_with_designation = get_sub_field('person_name_with_designation');
		$section_heading = get_sub_field('section_heading');
		$about_auther_content = get_sub_field('about_auther_content');
	    $button_name = get_sub_field('button_name');
		$button_link = get_sub_field('button_link');
		 
	  ?>
	  <section class="about-john">
		<div class="container">
		<div class="row align-items-center">
		<div class="col-lg-6">
		<div class="corner-bottom-right">
		<?php if($section_heading){ ?>
		<h3><?php echo $section_heading;?></h3><?php } 
		if($about_auther_content){
		?>
		<div class="text"><?php echo $about_auther_content;?></div>
		<?php } ?>
		<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
		</div></div>
		<div class="col-lg-6">
		<?php if($right_side_image){ ?>
		<img src="<?php echo $right_side_image['url'];?>" alt="<?php echo $right_side_image['alt'];?>" class="person">
		<?php } 
		if($person_sign){
		?>
		<span class="sign"><img src="<?php echo $person_sign['url'];?>" alt="<?php echo $person_sign['alt'];?>"><br><?php echo $person_name_with_designation;?></span>
		<?php } ?>
		</div>
		</div>
		</div>
		</section>
	  <?php
endif;
      if( get_row_layout() == 'person_introduction_section' ): 
	  
    $section_heading = get_sub_field('section_heading');
	$section_content = get_sub_field('section_content');
	$sign = get_sub_field('sign');
	$name_and_designation = get_sub_field('name_and_designation');
	$button_name = get_sub_field('button_name');
	$button_link = get_sub_field('button_link');
	$person_image = get_sub_field('person_image');
	
	
		?>
		<!-- About John -->
			<section class="about-john">
		<div class="container">
		<div class="row align-items-center">
		<div class="col-lg-6">
		<div class="corner-top-right">
		<?php 
		if($section_heading){
		?>
		<h3><?php echo $section_heading;?></h3>
		<?php } 
		if($section_content){
		?>
		<div class="text">
		<?php echo $section_content;?>
		<br/><br/>
		<?php if($sign){ ?>
		<img src="<?php echo $sign['url'];?>" alt="<?php echo $sign['alt'];?>">
		<?php } 
		if($name_and_designation){ ?>
		<h6><?php echo $name_and_designation;?></h6>
		<?php } ?>
		</div>
		<?php } 
		if($button_name){ ?>
		<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
		<?php } ?>
		</div></div>
		<?php if($person_image) { ?>
		<div class="col-lg-6">
		<img src="<?php echo $person_image['url'];?>" alt="<?php echo $person_image['alt'];?>" class="person">
		</div>
		<?php 
		 } ?>
		</div>
		</div>
		</section>
		<?php
    endif;
	  if( get_row_layout() == 'person_introduction_section' ): 
	  
	  $args = array( 'post_type' => 'founders', 'posts_per_page' => -1, 'order' => 'ASC' );
$the_query = new WP_Query( $args ); 
?>
<?php if ( $the_query->have_posts() ) : ?>
	
		<!-- Members -->
		<section class="members-grid">
		<div class="container">
		<div class="row">
	
		
	
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); 
 $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
?>
<div class="col-lg-3 col-md-6">
		<div class="member-sec">
		<div class="member-img" style="background-image:url(<?php echo $featured_img_url;?>);"></div>
		<h5><?php the_title(); ?></h5>
		<div class="author-position"><?php the_content(); ?> </div>
		</div>
		</div>


<?php  
endwhile;
wp_reset_postdata(); ?>
<?php else:  ?>
<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
</div>
		</div>
		</section>
<?php endif;
	  
 endif;
	  if( get_row_layout() == 'our_principles_section' ): 
	 
	 
	 ?>
	 <section class="principles-sec">
		<div class="container">
		<h2 class="text-center">Our Principles</h2>
		
		<?php

// check if the repeater field has rows of data
if( have_rows('principles_section') ):
?>
<div class="row">
<?php
 	// loop through the rows of data
    while ( have_rows('principles_section') ) : the_row();
  $principle_image = get_sub_field('principle_image');
  $principle_name = get_sub_field('principle_name');
  $principle_content = get_sub_field('principle_content');

       ?>
	   <div class="col-md-6">
		<div class="d-flex">
		<?php if($principle_image){ ?>
		<div class="icon"><img src="<?php echo $principle_image['url'];?>"/></div><?php 
		} ?>
		<div class="content">
		<?php if($principle_name){ ?>
		<h4><?php echo $principle_name;?></h4>
		<?php } 
		if($principle_content){ 
		echo $principle_content; } ?>
		</div>
		</div>
		</div>
	   <?php
      
    endwhile;
?>
</div>
<?php
else :

    // no rows found

endif;

?>
		

		
		</div>
		</section>
	 <?php
	endif;
			  if( get_row_layout() == 'services_section' ): 
			
					if( have_rows('add_ser_section') ):
						while ( have_rows('add_ser_section') ) : the_row();

							if( get_row_layout() == 'right_image_left_text' ):

								$add_heading = get_sub_field('add_heading');
								$add_content = get_sub_field('add_content');
								$add_image = get_sub_field('add_image');
								$button_name = get_sub_field('button_name');
								$button_link = get_sub_field('button_link');
			?>
						<section class="right-two-images sec-mr-top">
							<div class="two-images-banner"><img src="<?php echo $add_image['url'];?>" alt="<?php echo $add_image['alt'];?>">
							</div>
							<div class="container">
							<div class="corner-top-right">
								<?php if($add_heading){ ?>
							<h3><?php echo $add_heading;?></h3>
								<?php } 
								if($add_content){
								?>
							<div class="text">
							<?php echo $add_content;?>
							</div>
								<?php } 
								if($button_name){
								?>
							<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
								<?php } ?>
							</div>
							</div>
						</section>
			<?php
			
							endif;
							
			
							if( get_row_layout() == 'add_logo_section' ): 
				$section_heading = get_sub_field('section_heading');
			?>
			
			<section class="logo-slider-sec container">
				<?php if($section_heading){ ?>
				<h5 class="text-center"><?php echo $section_heading;?></h5><?php } 
				if( have_rows('logo_sectionss') ):

 	?>	<div class="logo-slider text-center">
				
				
				
				
				<?php
    while ( have_rows('logo_sectionss') ) : the_row();

        // display a sub field value
        $add_logo_image = get_sub_field('add_logo_image');
				?>
				<img src="<?php echo $add_logo_image['url'];?>" alt="<?php echo $add_logo_image['alt'];?>"/>
				<?php

    endwhile;
				?>
				</div>
				<?php

else :

    // no rows found

endif;
				
				?>
			
			</section>
			<?php
endif;
							
			
							if( get_row_layout() == 'left_image_right_text' ): 
								$add_heading = get_sub_field('add_heading');
								$add_content = get_sub_field('add_content');
								$add_image = get_sub_field('add_images');
								$button_name = get_sub_field('button_name');
								$button_link = get_sub_field('button_link');
								//$add_heading = get_sub_field('add_heading');
								?>
			
					<section class="left-two-images">
			<div class="two-images-banner"><img src="<?php echo $add_image['url'];?>" alt="<?php echo $add_image['alt'];?>">
			</div>
			<div class="container">
			<div class="corner-bottom-left bg-light-brown">
				<?php if($add_heading){ ?>
			<h3><?php echo $add_heading;?></h3>
				<?php }
				if($add_content){
				?>
			<div class="text">
			<?php echo $add_content;?>
			</div>
				<?php } 
				if($button_name){
				?>
			<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
				<?php } ?>
			</div>
			</div>
		</section>
			<?php
								

							endif;

						endwhile;

					else :

						// no layouts found

					endif;

			
			
			?>
	

			
	
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
