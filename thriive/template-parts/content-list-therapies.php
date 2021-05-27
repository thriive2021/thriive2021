<?php 
	//print_r( $therapy_term );
	$therapy_image = get_field('therapy_image', $therapy_term);
		$therapy_img = wp_get_attachment_image_src( $therapy_image, 'thumbnail' );
	?>
<div class="col-6 text-center">
    <div class="banner-logo">
        <a href="<?php echo get_term_link($therapy_term->term_id); ?>">
            <img title="<?php echo $therapy_term->name; ?>" src="<?php echo $therapy_img[0]; ?>" alt="<?php echo $therapy_term->name; ?>">
        </a>
    </div>
    <h5><a href="<?php echo get_term_link($therapy_term->term_id); ?>"><?php echo $therapy_term->name; ?></a></h5>
    <?php
			if(is_singular('therapist'))
			{
				$therapy_Exp = getTherapistExperience($therapy_Exp);
				
				echo "<p>$therapy_Exp Experience</p>";
				if($therapy_charge)
				{
					echo "<p>&#8377; $therapy_charge</p>";
				}
			}
		?>
    <a href="<?php echo get_term_link($therapy_term->term_id); ?>" class="btn btn-primary">KNOW MORE</a>
</div>