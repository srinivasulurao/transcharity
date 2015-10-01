<?php
global $javo_tso;
$javo_rating = new javo_Rating( get_the_ID() );
echo apply_filters('javo_shortcode_title', __('Ratings', 'javo_fr'), get_the_title() );?>

<!-- total rating result start -->
<div class="total-rating">
	<div class="row total-rating-top-wrap">
		<div class="col-xs-12 col-md-6 javo-animation x2 javo-left-to-right-999">
			<div class="well well-sm">
				<div class="total-rating-title">
					<h3><?php _e('Total Ratings', 'javo_fr');?></h3>
				</div> <!-- total-rating-title -->
				<div class="row">
					<div class="col-xs-12 col-md-5 text-center">
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

					<div class="col-xs-12 col-md-7">
						<div class="row rating-desc">
						<?php
							$javo_this_part_ratings = $javo_rating->part_key_score( get_the_ID() );
							if( !empty( $javo_this_part_ratings ) ){
								foreach( $javo_this_part_ratings as $key => $value ){
									?>
										<div class="">
											<?php //echo $key.'=>'.round($value);?>
										</div>
										<div class="progress">
											<div class="progress-bar progress-bar-dark progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (round($value) / 5) * 100;?>%;">
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
		<div class="col-xs-12 col-md-6 javo-animation x2 javo-right-to-left-999">
			<div class="well well-sm">
				<div class="total-rating-title">
					<h3><?php echo $javo_tso->get('rating_alert_header');?></h3>
				</div> <!-- total-rating-title -->
				<div>
					<?php echo $javo_tso->get('rating_alert_content');?>
				</div>
			</div>
		</div>

		<div class="row rating-form-wrap">
			<div class="col-md-12">
				<?php echo $javo_rating->form();?>
			</div>
		</div>

	</div> <!-- row total-rating-top-wrap -->
</div>
<!-- total rating result end -->

<div class="row rating-list-wrap">
	<div class="col-md-12">
		<?php echo $javo_rating->time_line(); ?>
	</div>
</div>