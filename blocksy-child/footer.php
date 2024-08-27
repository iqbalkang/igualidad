<footer class="footer">
	<div>
		<?php
    $image_id = 4594;
    echo wp_get_attachment_image( $image_id, 'full', false, array( 'alt' => 'Your Image Description' ) );
    ?>
		
	</div>

	<ul class="footer-menu">
        <li><a href="<?php echo site_url('/about'); ?>">About Us</a></li>
        <li><a href="<?php echo site_url('/board-members'); ?>">Board Members</a></li>
        <li><a href="<?php echo site_url('/resources'); ?>">Resources</a></li>
        <li><a href="<?php echo site_url('/contact'); ?>">Contact Us</a></li>
        <li><a href="<?php echo site_url('/donate'); ?>">Donate</a></li>
	</ul>

	<p class="footer-address"> 
	161 Lower Westfield Road, <br /> Holyoke, MA  01040
	</p>

	<div class="phone-fax">
		<div class="phone">
			<p class="phone-heading">Phone</p>
			<p>413-536-3201</p>
		</div>
		<div class="fax">
			<p class="fax-heading">Fax</p>
			<p>413-536-3206</p>
		</div>
	</div>

	<div class="socials">
		<p class="socials-heading">Connect with us</p>
		<div class="social-links">
			<i class="fa-brands fa-facebook-f"></i>
			<i class="fa-brands fa-linkedin-in"></i>
			<i class="fa-brands fa-instagram"></i>
		</div>
	</div>

	<a href="#" class="footer-btn">Donate</a>

	<div class="footer-divider-box">
		<?php
			$image_id = 4601;
			echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'footer-graduation-image', 'alt' => 'Image Description' ) );
			?>

		<div class="footer-divider"></div>
	</div>

	<p>&copy; <?php echo date('Y'); ?> Igualidad. Website designed by <a href="https://www.linkedin.com/in/iqbalkang/">Iqbal S Kang</a>. All rights reserved.</p>


</footer>