<?php
/**
*** User Information
***/
global $javo_tso;
$javo_this_user = wp_get_current_user();
$javo_this_user_avatar = wp_get_attachment_image( get_user_meta($javo_this_user->ID, 'avatar', true), 'thumbnail' );
$javo_this_user_avatar_html = sprintf("<img src='%s'>", $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
$javo_this_user_avatar = $javo_this_user_avatar ? $javo_this_user_avatar : $javo_this_user_avatar_html;
?>
<div class="container profile-and-image-container">
	<div class="col-xs-6 col-sm-2">
		<div class="row author-img">
			<div class="col-md-12">
				<?php echo $javo_this_user_avatar;?>
			</div><!-- 12 Columns -->
		</div>
		<div class="row author-names">
			<div class="col-md-12">
				<ul class="list-unstyled text-center">
					<li><?php printf('%s %s', $javo_this_user->first_name, $javo_this_user->last_name);?></li>
				</ul>
			</div><!-- 12 Columns -->
		</div>
	</div> <!-- col-xs-6 col-sm-3 -->
	<div class="col-xs-6 col-sm-10">
		&nbsp;
	</div> <!-- col-xs-12 col-sm-10 -->
</div> <!-- container -->