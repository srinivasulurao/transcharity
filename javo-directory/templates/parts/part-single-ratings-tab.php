<?php
global $javo_this_single_page_type
	, $javo_tso
	, $javo_animation_fixed
	, $javo_custom_item_label;

$javo_rating = new javo_Rating( get_the_ID() );
echo apply_filters('javo_shortcode_title', __($javo_custom_item_label->get('ratings', 'Ratings'), 'javo_fr'), get_the_title() );?>

<!-- total rating result start -->
<div class="total-rating">
	<div class="row total-rating-top-wrap">
		<div class="col-xs-12 col-md-6 col-sm-6 javo-animation javo-left-to-right-999 <?php echo $javo_animation_fixed;?>">
			<div class="well well-sm">
				<div class="total-rating-title">
					<h3><?php printf( __('Total %s', 'javo_fr'), $javo_custom_item_label->get('ratings', __('Ratings', 'javo_fr')));?></h3>
				</div> <!-- total-rating-title -->
				<div class="row">
					<div class="col-xs-4 col-md-5 col-sm-5 text-center">
						<h1 class="rating-num"><?php echo $javo_rating->parent_rating_average;?></h1>
						<div class="rating">
							<span class="<?php echo $javo_rating->parent_rating_average_star[0];?>"></span>
							<span class="<?php echo $javo_rating->parent_rating_average_star[1];?>"></span>
							<span class="<?php echo $javo_rating->parent_rating_average_star[2];?>"></span>
							<span class="<?php echo $javo_rating->parent_rating_average_star[3];?>"></span>
							<span class="<?php echo $javo_rating->parent_rating_average_star[4];?>"></span>
						</div> <!-- rating -->
						<div>
							<span class="glyphicon glyphicon-user"></span><?php echo $javo_rating->parent_rating_count.' '; _e('Total', 'javo_fr');?>
						</div>
					</div> <!-- col-md-5 -->

					<div class="col-xs-8 col-md-7 col-sm-7">
						<div class="row rating-desc">
						<?php							
							$javo_this_part_ratings	= $javo_rating->part_key_score( get_the_ID() );
							if( !empty( $javo_this_part_ratings ) ){
								foreach( $javo_this_part_ratings as $key => $value )
								{
									$javo_max_score			= 5;
									$javo_cur_percentage	= sprintf( "%0.1f%%", ( ( $value / (int)$javo_max_score ) * 100 ) );
									?>
									<div class="">
										<?php //echo $key.'=>'.round($value);?>
									</div>
									<div class="progress">
										<div class="progress-bar progress-bar-blue progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="<?php echo"width:{$javo_cur_percentage};"?>">
											<span class="sr-only" style="width:100%;"></span>
										</div>
									</div>
									<?php
								};
							};?>
						</div> <!-- rating-desc -->
					</div> <!-- col-md-7 -->
				</div> <!-- row -->
			</div> <!-- well -->
		</div> <!-- col-md-6 -->
		<div class="col-xs-12 col-md-6 col-sm-6 javo-animation javo-right-to-left-999 <?php echo $javo_animation_fixed;?>">
			<div class="well well-sm">
				<div class="total-rating-title">
					<h3><?php _e($javo_tso->get('rating_alert_header'), 'javo_fr');?></h3>
				</div> <!-- total-rating-title -->
				<div>
					<?php _e($javo_tso->get('rating_alert_content'), 'javo_fr');?>
				</div>
			</div>
		</div>

		<div class="row rating-form-wrap">
			<div class="col-md-12">
				<?php echo $javo_rating->form();?>
			</div> <!-- col-md-12 -->
		</div> <!-- rating-form-wrap -->


	</div> <!-- row total-rating-top-wrap -->
</div>
<!-- total rating result end -->
<div class="row rating-list-wrap">
	<div class="col-md-12">
		<?php echo $javo_rating->time_line('tab'); ?>
	</div>
</div>