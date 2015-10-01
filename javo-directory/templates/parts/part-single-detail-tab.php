<?php
global $javo_custom_field
		, $post
		, $javo_tso
		, $javo_video_query
		, $javo_favorite;
$javo_this_author				= get_userdata($post->post_author);
$javo_this_author_avatar_id		= get_the_author_meta('avatar');
$javo_directory_query			= new javo_get_meta( get_the_ID() );
$javo_rating = new javo_Rating( get_the_ID() );


$javo_this_item_tab_slide_type = 'type2';
?>

<!-- slide -->
	<div class="row">
		<div class="col-md-12">
			<?php get_template_part('templates/parts/part', 'single-detail-tab-sliders');?>
		</div> <!-- col-md-12 -->
	</div> <!-- row -->

	<div class="single-sns-wrap-div <?php if($javo_tso->get('claim_use')=='use') echo 'before-claim';?>">
		<span class="javo-archive-sns-wrap social-wrap pull-right">
			<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" >
				<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
			</i>
			<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>">
				<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
			</i>
			<i class="sns-heart">
				<a class="javo-tooltip favorite javo_favorite<?php echo $javo_favorite->on( get_the_ID(), ' saved');?>"  data-post-id="<?php the_ID();?>" title="<?php _e('Add My Favorite', 'javo_fr');?>"></a>
			</i>
		</span>
	</div><!-- single-sns-wrap-div -->
	<!-- slide end -->
	<?php
	if(
		$javo_tso->get( 'claim_use' ) == 'use' &&
		get_post_meta( get_the_ID(), 'claimed', true ) != 'yes'
	): ?>
		<div class="claim_btn_wrap clearfix">
			<a href="#" data-toggle="modal" data-target="#jv-claim-reveal" class="btn btn-primary javo-tooltip pull-right" title="" data-original-title="<?php _e('Claim This Business','javo_fr'); ?>"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;<?php _e('Own This Business?', 'javo_fr'); ?></a>
		</div> <!-- claim_btn_wrap -->
	<?php endif; ?>
	<div class="row">
		<div class="col-md-12 description-part">
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Description', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Description', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
				<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
				<div class="inner-items">
					<?php the_content();?>
				</div> <!-- inner-items -->
			</div> <!-- item-single-details-box -->
		</div> <!-- col-md-12 -->
		<?php
		if( $javo_video_query->get('single_position', 'slide') == 'descript' ){
			?>

		<div class="col-md-12"> <!-- video start -->
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Video', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Video', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
				<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
				<div class="inner-items">
					<?php echo $javo_video_query->get('html'); ?>
				</div> <!-- inner-items -->
			</div> <!-- item-single-details-box -->
		</div> <!-- col-md-12 // video end -->


			<?php
		};?>

		<div class="col-md-12 contact-part">
			<div class="item-single-details-box">
				<h4 class="detail-titles"><?php _e('Contact', 'javo_fr'); ?></h4>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area admin-color-setting"><?php _e('Contact', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
					<div class="corner-wrap">
						<div class="corner admin-color-setting"></div>
						<div class="corner-background admin-color-setting"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->

				<div class="inner-items">
					<ul>
						<?php
						if( '' !== ( $tmp = get_post_meta( get_the_ID(), "jv_item_address", true ) ) ){
							printf('<li class="single-contact-address"><span>%s</span>%s</li>', __("Address", 'javo_fr'), $tmp );
						}

						if( '' !== ( $tmp = get_post_meta( get_the_ID(), "jv_item_phone", true ) ) ){
							printf('<li class="single-contact-address"><span>%s</span><a href="tel:%s" target="_self">%s</a></li>', __("Phone", 'javo_fr'), $tmp, $tmp );
						}

						if( '' !== ( $tmp = get_post_meta( get_the_ID(), "jv_item_email", true ) ) ){
							printf('<li class="single-contact-address"><span>%s</span><a href="mailto:%s" target="_self">%s</a></li>', __("E-mail", 'javo_fr'), $tmp, $tmp );
						}
						
						if( '' !== ( $tmp = get_post_meta( get_the_ID(), "jv_item_website", true ) ) ){
							printf('<li class="single-contact-address"><span>%s</span><a href="%s" target="_self">%s</a></li>', __("Website", 'javo_fr'), $tmp, $tmp );
						} ?>

						<li class="single-contact-category"><span><?php echo __('Category', 'javo_fr').'</span> '.$javo_directory_query->cat('item_category', __('No Category','javo_fr'),false,false);?></li>
						<li class="single-contact-location"><span><?php echo __('Location', 'javo_fr').'</span> '.$javo_directory_query->cat('item_location');?></li>
						<?php if( false != ( $javo_tags = $javo_directory_query->Tag('string') ) ): ?>
							<li class="single-contact-tag"><span><?php echo __('Tag', 'javo_fr').'</span> '.$javo_tags;?></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		if( $javo_video_query->get('single_position', 'slide') == 'contact' ){
			?>
			<div class="col-md-12"> <!-- video start -->
				<div class="item-single-details-box">
					<h4 class="detail-titles"><?php _e('Video', 'javo_fr'); ?></h4>
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area admin-color-setting"><?php _e('Video', 'javo_fr'); ?></div> <!-- javo-txt-meta-area -->
						<div class="corner-wrap">
							<div class="corner admin-color-setting"></div>
							<div class="corner-background admin-color-setting"></div>
						</div> <!-- corner-wrap -->
					</div> <!-- javo-left-overlay -->
					<!-- <div class="title-box"><?php _e('Description', 'javo_fr'); ?></div> -->
					<div class="inner-items">
						<?php echo $javo_video_query->get('html'); ?>
					</div> <!-- inner-items -->
				</div> <!-- item-single-details-box -->
			</div> <!-- col-md-12 // video end -->
			<?php }; ?>
			<div class="col-md-12 custom-part">
			<?php
			$javo_integer = 0;
			$javo_el_childrens = "";
			$javo_custom_field = javo_custom_field::gets();

			if( !empty( $javo_custom_field ) ){
				foreach($javo_custom_field as $field){
					$javo_marge_value = '';
					$javo_this_value = !empty( $field['value'] ) ? (Array) $field['value'] : Array();
					if(
						empty( $javo_this_value ) || $javo_this_value == '' &&
						( !empty( $field['type'] ) && $field['type'] != "group" )
					){
						continue;
					}

					$javo_integer++;
					foreach( $javo_this_value as $value)
					{
						$javo_marge_value .= $value . ', ';
					}

					$javo_marge_value = substr( trim( $javo_marge_value ), 0, -1 );
					if( !empty( $field['type'] ) && $field['type'] == "group" )
					{
						$javo_el_childrens .= "<li><h5>{$field['label']}</h5></li>";

					}else{
						$javo_el_childrens .= "<li><span>{$field['label']}</span>{$javo_marge_value}</li>";
					}

				} // End Foreach
			}
			if( (int)$javo_integer > 0 ){
				?>
				<div class="item-single-details-box">
					<h4 class="detail-titles"><?php echo $javo_tso->get('field_caption', __('Aditional Information', 'javo_fr'))?></h4>
					<div class="javo-left-overlay">
						<div class="javo-txt-meta-area admin-color-setting"><?php echo $javo_tso->get('field_caption', __('Aditional Information', 'javo_fr'))?></div> <!-- javo-txt-meta-area -->
						<div class="corner-wrap">
							<div class="corner admin-color-setting"></div>
							<div class="corner-background admin-color-setting"></div>
						</div> <!-- corner-wrap -->
					</div> <!-- javo-left-overlay -->
					<div class="inner-items">
						<ul><?php echo $javo_el_childrens;?></ul>
					</div>
				</div>
				<?php
			};// End If?>

		</div> <!-- col-md-12 -->
	</div> <!-- row -->