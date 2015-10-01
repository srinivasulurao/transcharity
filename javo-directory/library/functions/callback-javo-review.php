<?php
add_action('wp_ajax_get_single_review', 'javo_get_single_review_callback');
add_action('wp_ajax_nopriv_get_single_review', 'javo_get_single_review_callback');
add_filter('javo_single_review_filter', 'javo_single_review_filter_callback');

function javo_single_review_filter_callback($post_id){
	global $javo_tso
		,$javo_favorite;
	$post = get_post($post_id);
	ob_start();
	?>
	<div class="row">
		<div class="col-md-2 text-center review-thumb" style="height:100%;">
			<?php
			if( has_post_thumbnail($post->ID) ){
				echo get_the_post_thumbnail($post->ID, 'thumbnail', Array('class'=> 'img-responsive img-circle', 'style'=>'display:inline-block;'));
}			else{
				printf('<img src="%s" class="img-circle" style="width:100%%; Height:100px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));

			};?>
		</div><!-- col-md-2 -->
		<div class="col-md-10">
			<div class="row"><div class="col-md-12 review-title">
				<h3><?php echo $post->post_title;?></h3>
				<span class="javo-archive-sns-wrap social-wrap pull-right">
					<i class="sns-facebook" data-title="<?php the_title();?>" data-url="<?php get_permalink($post->ID); ?>">
						<a class="facebook javo-tooltip" title="<?php _e('Share Facebook', 'javo_fr');?>"></a>
					</i>
					<i class="sns-twitter" data-title="<?php the_title();?>" data-url="<?php get_permalink($post->ID); ?>">
						<a class="twitter javo-tooltip" title="<?php _e('Share Twitter', 'javo_fr');?>"></a>
					</i>
				</span>
			</div></div>
			<div class="row"><div class="col-md-12 review-con"><?php echo strip_tags($post->post_content);?></div></div>
		</div><!-- col-md-10 -->
	</div><!-- Close Row -->
	<hr/>
	<?php
	return ob_get_clean();
}
function javo_get_single_review_callback(){
	$javo_this_result				= Array();
	$javo_query						= new javo_ARRAY( $_POST );
	$javo_this_review_posts_args	= Array(
		'post_type'					=> 'review'
		, 'post_status'				=> 'publish'
		, 'posts_per_page'			=> $javo_query->get('count')
		, 'offset'					=> $javo_query->get('offset')
		, 'meta_query'				=> Array(
			Array(
				'key'				=> 'parent_post_id'
				, 'type'			=> 'NUMBERIC'
				, 'value'			=> $javo_query->get('post_id')
				, 'compare'			=> '='
			)
		)
	);
	$javo_this_review_posts			= new WP_Query($javo_this_review_posts_args);
	ob_start();
	if( $javo_this_review_posts->have_posts() ){
		while( $javo_this_review_posts->have_posts() ){
			$javo_this_review_posts->the_post();
			echo apply_filters('javo_single_review_filter', get_the_ID());
		}; // Emd While
	}else{

	}; // End If
	wp_reset_postdata();
	$javo_this_result['html']		= ob_get_clean();
	echo json_encode( $javo_this_result );
	exit;
}