<?php
/**
***	My Ratings Page
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-page-rating">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->

	<div class="container secont-container-content">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/sidebar', 'menu');?>
			<div class="col-xs-12 col-sm-10 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap">
							<div class="panel-heading">
								<p class="pull-left visible-xs">
									<button class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php _e('My Page Menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
										<?php printf(__('My %s List','javo_fr') , $javo_custom_item_label->get('ratings', 'Ratings'));?>
									</div> <!-- my-page-title -->

									<div class="col-md-1">
										<p class="text-center"><a href="#full-mode" class="toggle-full-mode"><i class="fa fa-arrows-alt"></i></a></p>
										<script type="text/javascript">
										(function($){
											"use strict";
											$('body').on('click', '.toggle-full-mode', function(){
												$('body').toggleClass('content-full-mode');
											});
										})(jQuery);
										</script>
									</div> <!-- my-page-title -->
								</div> <!-- row -->
							</div> <!-- panel-heading -->

							<div class="panel-body">
							<!-- Starting Content -->

								<?php
								$javo_this_rating_args = Array(
									'post_type'=> 'ratings'
									, 'posts_per_page'=> 10
									, 'post_status'=> 'publish'
									, 'author'=> get_current_user_id()
									, 'paged'=>max( 1, get_query_var('paged') )
								);
								$javo_this_ratings = new WP_Query($javo_this_rating_args);
								if( $javo_this_ratings->have_posts() ){
									while( $javo_this_ratings->have_posts() ){
										$javo_this_ratings->the_post();
										$javo_rating = new javo_RATING( get_the_ID() );
										$javo_this_parent_id = get_post_meta(get_the_ID(), 'rating_parent_post_id', true);
										$javo_this_parent = get_post($javo_this_parent_id);
										?>
										<div class="row content-panel-wrap-row<?php echo $javo_this_parent? '':' javo-rating-deleted-item';?>">
											<div class="col-md-2 content-panel-date">
												<h1><?php echo round($javo_rating->parent_rating_average, 1);?></h1>
												<?php echo esc_attr(get_the_date('d. M Y'));?>
											</div> <!-- col-md-2 -->
											<div class="col-md-10">
												<div class="row">
													<div class="col-md-6 pull-left my-item-titles">
														<a href="<?php echo get_the_permalink($javo_this_parent_id);?>/#item-ratings">
															<h3><?php echo $javo_this_parent ? $javo_this_parent->post_title:__('Deleted Post', 'javo_fr');?></h3>
														</a>
													</div> <!-- col-md-6 -->

													<div class="col-md-6 pull-right">
														<div class="row content-panel-button-list" align="right">
															<a class="btn btn-danger btn-circle javo_this_trash mypage-tooltips" data-post="<?php the_ID();?>" title="<?php _e('Delete Item', 'javo_fr'); ?>"><i class="glyphicon glyphicon-trash"></i></a>
														</div>
													</div> <!-- col-md-6 -->
												</div> <!-- row -->
												<div class="text-in-content">
													<a href="<?php echo get_the_permalink($javo_this_parent_id);?>/#item-ratings">
														<span><?php the_excerpt();?></span>
													</a>
												</div><!-- text-in-content -->
											</div> <!-- col-md-10 -->
										</div> <!-- row-->
										<?php
									};
								};
								wp_reset_query();?>
								<div class="javo_pagination">
									<?php
									$big = 999999999; // need an unlikely integer
									echo paginate_links( array(
										'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )
										, 'format'		=> '?paged=%#%'
										, 'current'		=> max( 1, get_query_var('paged') )
										, 'total'		=> $javo_this_ratings->max_num_pages
									) );
									?>
								</div><!-- javo_pagination -->

							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<div class="modal fade" id="javo-edit-rating" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php _e('Edit '.$javo_custom_item_label->get('ratings', 'Ratings'), 'javo_fr');?></h4>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<img src="<?php echo JAVO_THEME_DIR.'/assets/images/loading_6.gif';?>" width="20%">
				</div>
			</div><!-- modal body -->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Cancel', 'javo_fr');?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
(function($){
	"use strict";
	$('body').on('click', '.javo-this-rating-edit', function(){
		var $this = $(this);
		var $modal = $('#javo-edit-rating');
		var $modal_inner = $modal.find('.modal-body');
		$modal.modal();
		$.ajax({
			url:'<?php echo admin_url("admin-ajax.php");?>'
			, type:'post'
			, dataType:'json'
			, data:{ action:'edit_rating', rating_id:$this.data('post')}
			, success:function(d){
				$modal_inner.empty().html( d.html );
			}
		});
	});
})(jQuery);
</script>
<?php
get_template_part('library/dashboard/mypage', 'common-script');
get_footer();