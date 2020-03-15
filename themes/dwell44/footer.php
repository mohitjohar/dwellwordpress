<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dwell44
 */

?>
	<?php
	$show_latest_articles = get_field('show_latest_articles');
if($show_latest_articles == 'yes'){
	
$args = array(  
       'post_type' => 'post',
       'post_status' => 'publish',
       'posts_per_page' => 3,
    'order' => 'DESC',
   );

   $loop = new WP_Query( $args );
     ?>
	 	<section class="post-latest-grid container">
				<h2>Latest Articles</h2>
			<div class="row latestarticle">
	
		
	 <?php
   while ( $loop->have_posts() ) : $loop->the_post();
   $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
   ?>
   	<div class="col-lg-4 col-md-6">
			<div class="post-sec">
			<div class="latest-img"><a href="<?php the_permalink();?>"><img src="<?php echo $featured_img_url;?>"/></a></div>
			<a href="<?php the_permalink();?>"><h5 class="latest-title "><?php  the_title();?></h5></a>
			<div class="post-prgrph-desc">
			<?php the_excerpt();?>
			</div>
			<a href="<?php the_permalink();?>" class="post-btn">READ MORE <i class="fa fa-chevron-right circle"></i></a>
			</div>
			</div>
			
   <?php
       
       
   endwhile;
?>
</div>
			</section>
<?php
   wp_reset_postdata();
?>
	<script>

jQuery(document).ready(function(){

    // Select and loop the container element of the elements you want to equalise
    jQuery('.latestarticle').each(function(){  
      
      // Cache the highest
      var highestBox = 0;
      
      // Select and loop the elements you want to equalise
      jQuery('h5.latest-title', this).each(function(){
        
        // If this box is higher than the cached highest then store it
        if(jQuery(this).height() > highestBox) {
          highestBox = jQuery(this).height(); 
        }
      
      });  
            
      // Set the height of all those children to whichever was highest 
      jQuery('h5.latest-title',this).height(highestBox);
                    
    }); 

});   
</script>	
	<?php } ?>
	</div><!-- #content -->
<?php 

$map_image = get_field('map_image' ,'options');
$map_heading = get_field('map_heading' ,'options');
$map_button_name = get_field('map_button_name' ,'options');
$map_button_link = get_field('map_button_link' ,'options');

?>
	<div class="mapsection" style="background-image:url(<?php echo $map_image['url'];?>);">
	<div class="container">
			<div class="row">
			<div class="col-sm-12">
			<div class="contactfo ml-auto">
				<?php if($map_heading){ ?>
			<h4><?php echo $map_heading; ?></h4><?php } 
				if($map_button_name){
				?>
				<a href="<?php echo $map_button_link;?>" class="btn white-br-btn"><?php echo $map_button_name;?></a>
				<?php } ?>
			</div>
			</div>
			</div>
			</div>
	
	</div>
	<div class="footertopconta">
		<div class="container">
			<div class="row">
				<?php $number = get_field('number','options');
				if($number){
				?>
				<div class="col-md-6 col-lg-2">
				<div class="">
				<a href="tel:<?php the_field('number_link','options');?>">	<div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/fb.png"></div>
					<div class="iconcont"><span>Phone:</span> <?php echo $number;?></div></a>
					<a href="tel:<?php the_field('number_link','options');?>" class="btn orange-bg-btn d-none-lg"><?php the_field('call_us_button_name','options');?></a>
</div>					
				</div>
				<?php } 
				$add_email = get_field('add_email','options');
				if($add_email ){ 
				?>
				<div class="col-md-6 col-lg-3">
					<div class="">
					<a href="mailto:<?php echo $add_email;?>"><div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/email.png"></div>
						<div class="iconcont"><span>Email:</span> <?php echo $add_email;?></div></a>
					<a href="mailto:<?php echo $add_email;?>" class="btn orange-bg-btn d-none-lg">Email us</a>
					</div>	
				</div>
				<?php } 
				$add_location = get_field('add_location','options');
				if($add_location){
				?>
				<div class="col-md-6 col-lg-4">
					<div class="">
					<div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/loaction.png"></div>
					<div class="iconcont"><span>Address:</span>  <?php echo $add_location;?></div>
					</div>
				</div>
				<?php } ?>
				<div class="col-md-6 col-lg-3">
					  <div class="socialmedia float-right">
	<a href="<?php the_field('facebook_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/facebook.png"></a>	
	<a href="<?php the_field('instagram_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/intagram.png"></a>	
	<a href="<?php the_field('houzz_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/houzz.png"></a>
	<a href="<?php the_field('google_link', 'option'); ?>" target="_blank"><img src="<?php bloginfo('template_url');?>/img/google.png"></a>
		</div>
				</div>
			</div>
		</div>
	</div>
	<footer id="colophon" class="site-footer">
		<div class="container">
		<div class="site-info">
			Copyright &copy; <?php echo date('Y'); ?> Dwell44. All Rights Reserved. 
			<a href="https://hookagency.com/" target="_blank" class="float-right"><img src="<?php bloginfo('template_url');?>/img/agenclogo.png"></a>
		</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="/wp-content/themes/dwell44/js/slick.js"></script>
<?php wp_footer(); ?>
<style>
	@media (min-width: 1240px){
.container {
    max-width:1180px;
		}}

	.contactfo
	{
		background-color:#81893f;
		padding:40px;
		max-width:516px;
		color:#fff;
	}
	.contactfo h4 span{font-size:12px;padding-left:10px; font-family: 'Gravitybook';}
</style>
<script>
window.onscroll = function() {myFunction();myFunction1()};

var header = document.getElementById("masthead");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

  jQuery(document).ready(function(){
      jQuery(".logo-slider").slick({
        dots: false,
        slidesToShow: 6,
        slidesToScroll: 1,
		 responsive: [
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4
      }
    }
  ]
      });
	  });
</script>

</body>
</html>
