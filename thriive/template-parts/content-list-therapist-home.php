<?php 
	if(is_mobile()) {
		$therapist_image = get_the_post_thumbnail($id = null, 'featured_post_mobile', array('alt' => get_the_title(), 'title'=>get_the_title()));
	} else {
		$therapist_image = get_the_post_thumbnail($id = null, 'thumbnail', array('alt' => get_the_title(), 'title'=>get_the_title())); 
	}
?>
<div class="col-12 p-0">
	<div class="healer-circle">
		<div class="inner-healer-circle">
		<?php //echo $therapist_image; ?>
		<?php if(is_tax('therapy'))
			{ 
				?>
				<a href="<?php echo $link; ?>?utm_source=homepage&utm_medium=<?php echo (is_mobile()) ? 'mobile' : 'desktop'; ?>&utm_campaign=<?php echo $widget_name;?>&utm_term=<?php echo (is_tax('therapy')) ? $title : get_the_title(); ?>&utm_content=<?php echo $position;?>">
					<img title="<?php echo $title; ?>" src="<?php echo $image; ?>" alt="<?php echo $title; ?>" >
				</a>
				<?php
			}
			else
			{ ?>
				<a href="<?php echo get_the_permalink(); ?>?utm_source=homepage&utm_medium=<?php echo (is_mobile()) ? 'mobile' : 'desktop'; ?>&utm_campaign=<?php echo $widget_name;?>&utm_term=<?php echo (is_tax('therapy')) ? $title : get_the_title(); ?>&utm_content=<?php echo $position;?>">
					<?php echo $therapist_image; ?>
				</a>
				<?php
			}
		?>
		</div>
	</div>
	<div class="txt-wrap mt-2">
<!-- 			<h5><?php echo (is_tax('therapy')) ? $title : get_the_title(); ?></h5> -->
			<h5>
				<?php if(is_tax('therapy')) { ?>
					<a href="<?php echo $link; ?>"><?php echo $title; ?></a>
				<?php } else {
					?>
					<a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a>
					<?php
				} 
				?>
			</h5>
<!-- 			<p class="localtion-wrapper m-0"><?php echo (is_tax('therapy')) ? $location : getTherapistLocation(get_the_id()); ?></p> -->
			<p class="text-highlight sub_title mb-3"><?php echo (is_tax('therapy')) ? $therapist_title : $sub_title; ?></p>
<!-- 			<a href="<?php echo (is_tax('therapy')) ? $link : get_the_permalink(); ?>" class="btn btn-primary">KNOW MORE</a> -->
	</div>
</div>

