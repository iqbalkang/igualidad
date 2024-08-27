<?php
get_header(); 
if (have_posts()) : 
    while (have_posts()) : 
        the_post(); ?>
        <section class="e-con-inner-custom">
			<div class="member-single-container">
				<div class="member-single-image-box">
					<?php the_post_thumbnail('full', ['class' => 'member-single-image']); ?>
				</div>
				<div class="member-single-content">
					<h1 class="member-single-title"><?php the_title(); ?></h1>
					<span class="member-single-position"><?php echo get_field('position'); ?></span>
					<div class="member-single-bio">
						<?php the_content(); ?>
					</div>
					<a href="/board-members">Back to the team</a>
				</div>
			</div>
		</section>
        
    <?php 
    endwhile; 
endif; 

get_sidebar();
get_footer();
?>

