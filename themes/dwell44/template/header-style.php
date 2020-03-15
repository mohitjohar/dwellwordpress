		<?php 

// check if the flexible content field has rows of data
if( have_rows('header_styles') ):

     // loop through the rows of data
    while ( have_rows('header_styles') ) : the_row();

        if( get_row_layout() == 'header_with_opening_fall' ):

        	$background_image = get_field('background_image');
$header_title = get_field('header_title');
$left_side_subtitle = get_field('left_side_subtitle');
$header_content = get_field('header_content');
$button_name = get_field('button_name');
$button_link = get_field('button_link');
$schedule_section_title = get_field('schedule_section_title');
$schedule_section_subtitle = get_field('schedule_section_subtitle');
$schedule_button_name = get_field('schedule_button_name');
$schedule_button_link = get_field('schedule_button_link');
//$header_title = get_field('header_title'); ?>
			<div class="top-sec" style="background-image:url(<?php echo $background_image['url'];?>);">
				<div class="banner">
				<div class="container position-relative">
				<?php if($left_side_subtitle){ ?>
					<div class="text-rotate"><span><?php echo $left_side_subtitle;?></span></div>
				<?php } ?>
					<div class="content">
						<div class="mobilogo d-block d-md-none">
							 <?php the_custom_logo(); ?>
						</div>
					<?php if($header_title){ ?>
					<h1><?php echo $header_title;?></h1>
					<?php } if($header_content){  ?>
					<h6><?php echo $header_content;?> </h6>
					<?php } if($button_name){ ?>
					<a href="<?php echo $button_link;?>" class="btn brown-btn"><?php echo $button_name;?></a>
					<?php } ?>
					</div>
					<?php if($schedule_section_subtitle){ ?>
					<div class="text-center"><div class="orange-bg-box"><?php echo $schedule_section_subtitle;?></div></div>
					<?php } ?>
					<div class="row gray-bg">
					<?php if($schedule_section_title){ ?>
					<div class="col-lg-7"><h4><?php echo $schedule_section_title;?></h4></div><?php } 
					if($schedule_button_name){
					?>
					<div class="col-lg-5 green-bg d-flex align-items-center justify-content-center"><a href="<?php echo $schedule_button_link;?>" class="btn white-br-btn"><?php echo $schedule_button_name;?></a></div>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="event-box">
				<span>NEXT event:</span> 7/12 - Beer & Bathroom Fixtures - <a href="#">REGISTER <img src="<?php bloginfo('template_url');?>/img/rightarrow.png" rel="nofollow"></a>
			</div>
			</div>
			<?php
endif;
      if( get_row_layout() == 'header_with_brands_section' ): 

        		$background_image = get_field('background_image');
$header_title = get_field('header_heading');
	$header_content = get_field('header_content');
		 $want_to_show = get_field('want_to_show');
	$intro_heading = get_field('intro_heading');
	$intro_content = get_field('intro_content');
	$intro_image = get_field('intro_image');
	$partner_section_heading = get_field('partner_section_heading');
	//$partner_logo_section = get_field('partner_logo_section');
	
	if($want_to_show == 'partnersection') { 
	?>
			<div class="top-sec" style="background-image:url(<?php echo $background_image['url'];?>);">
				<div class="banner">
				<div class="container">					
					<div class="content"><div class="mobilogo d-block d-md-none">
							 <?php the_custom_logo(); ?>
						</div>
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
			<?php
}
	if($want_to_show == 'page_intro') { 
		?>
					<div class="top-sec services-top" style="background-image:url(<?php echo $background_image['url'];?>);">
				<div class="banner">
				<div class="container">
					
					<div class="content"><div class="mobilogo">
							 <?php the_custom_logo(); ?>
						</div>
						<?php if($header_title){ ?>
					<h1><?php echo $header_title;?></h1>
						<?php } 
						if($header_content){
						?>
					<h6><?php echo $header_content;?> </h6>
						<?php } ?>
					</div>
					
		<div class="empty-space"></div>		
</div>
			</div>
			</div>
			  <section class="srvc_secnd_sec">
			<div class="container">
					<div class="row secnd_sec_bg"> 
					
					<div class="col-lg-6 sec_bgnd" style="background-image:url(<?php echo $intro_image['url'];?>);"></div>
					<div class="col-lg-6 text_sec"><?php if($intro_heading){ ?><h2><?php echo $intro_heading;?></h2><?php } 
						if($intro_content){
						?>
<p><?php echo $intro_content;?></p>
						<?php } ?>
						</div>
					</div></div>
			    </section>
			
			<?php
		
	}
endif;
      if( get_row_layout() == 'header_with_image_slider' ):
$header_heading = get_field('header_heading');
$header_content = get_field('header_content');
$background_image = get_field('background_image');
?>
	<div class="top-sec" style="display:nonve;background-image:url(<?php echo $background_image['url'];?>);">
				<div class="banner">
				<div class="container">
					<div class="content"><div class="mobilogo d-block d-md-none">
							 <?php the_custom_logo(); ?>
						</div>
						<?php if($header_heading){ ?>
					<h1><?php echo $header_heading;?></h1>
						<?php } 
						if($header_content){ 
						?>
					<h6><?php echo $header_content; ?></h6><?php } ?>
					</div>
					
					<div class="empty-space"></div>
				</div>
			</div>
			</div>
			
			<?php

// check if the repeater field has rows of data
if( have_rows('image_slider') ):

 	?>
			<div class="container">
			<div id="gallery-slider" class="carousel slide" data-ride="carousel">
 
  <div class="carousel-inner">
	  <?php
	  $i = 1;
	      while ( have_rows('image_slider') ) : the_row();

        // display a sub field value
       $add_image = get_sub_field('add_image');
	  $total_images = count(get_sub_field('add_image'));
	  ?>
	    <div class="carousel-item <?php if($i == 1){ echo 'active';}?>">
      <img class="d-block w-100 h-100" src="<?php echo $add_image['url'];?>" alt="<?php echo $add_image['alt'];?>">
    </div>
	  <?php
$i++;
	  
  
	  endwhile;	  
	  $cimg =  $i - 2;
	  
	  ?>
	
  </div>   <ol class="carousel-indicators">
	  <?php for ($x = 0; $x <= $cimg; $x++) {
   ?>
	  <li data-target="#gallery-slider" data-slide-to="<?php echo $x;?>" class="<?php if($x == 0){ echo 'active';}?>"></li>
	  <?php
} ?>
    
   
  </ol>
  <a class="carousel-control-prev" href="#gallery-slider" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#gallery-slider" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>
			<?php


else :

    // no rows found

endif;


endif;
      if( get_row_layout() == 'header_with_persons_info' ):

// check if the flexible content field has rows of data
if( have_rows('contact_page_sections') ):

     // loop through the rows of data
    while ( have_rows('contact_page_sections') ) : the_row();

        if( get_row_layout() == 'header_section' ):

        	$banner_image = get_sub_field('banner_image');
			$heading = get_sub_field('heading');
			$person_image = get_sub_field('person_image');
			$person_name = get_sub_field('person_name');
			$person_designation = get_sub_field('person_designation');
			$about_person = get_sub_field('about_person');

       ?>
	   <div class="top-sec" style="background-image:url(<?php echo $banner_image['url'];?>);">
				<div class="banner">
				<div class="container">					
					<div class="content"><div class="mobilogo d-block d-md-none">
							 <?php the_custom_logo(); ?>
						</div>
					<h1><?php echo $heading;?></h1>
					<div class="conheader">
					<?php if($person_image){
						
						?>
					<img src="<?php echo $person_image['url'];?>" class="autimg"><?php } ?>
					<div class="contright"><?php if($person_name){?><h4><?php echo $person_name;?></h4><?php }
					if($person_designation){
					?>
					<h5><?php echo $person_designation;?></h5>
					<?php } ?>
					</div></div>
				<?php if($about_person){?>	<h6><?php echo $about_person;?></h6><?php } ?>
					</div>
					
					<section class="contactinfosec gray-bg">
			<div class="row">
			<?php $phone_number = get_sub_field('phone_number');
			$phone_number_link = get_sub_field('phone_number_link');
			$email_address = get_sub_field('email_address');
			$address = get_sub_field('address');
			
			if($phone_number){
			?>
				<div class="col-sm-3">
				<div class="">
					<div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/fb.png"></div>
					<div class="iconcont"><span>Phone:</span> <a href="tel:<?php echo $phone_number_link;?>"><?php echo $phone_number;?></a></div>
</div>					
				</div>
			<?php } if($email_address){ ?>
				<div class="col-sm-4">
					<div class="">
					<div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/email.png"></div>
					<div class="iconcont"><span>Email:</span> <a href="mailto:<?php echo $email_address;?>"><?php echo $email_address;?></a></div>
					</div>	
				</div>
			<?php } if($address){ ?>
				<div class="col-sm-5">
					<div class="">
					<div class="iconspac"><img src="<?php bloginfo('template_url');?>/img/loaction.png"></div>
					<div class="iconcont"><span>Address:</span> <?php echo $address;?></div>
					</div>
				</div>
			<?php } ?>
			</div>
			</section>
				</div>
			</div>
			</div>
	   <?php
	    endif;

        
    endwhile;endif;endif;

    endwhile;

else :

    // no layouts found

endif;

