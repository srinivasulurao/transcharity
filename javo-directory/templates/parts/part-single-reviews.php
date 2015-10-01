<?php

global $javo_tso_db;

echo apply_filters('javo_shortcode_title', __('Reviews', 'javo_fr'), get_the_title() ); ?>

<div class="row review-wrap">
	<div class="col-md-12">
		<div class="javo-single-review-more-content">
			<div class="text-right">
				<?php
				if( is_user_logged_in() ){
					if( $javo_tso_db->get( JAVO_ADDREVIEW_SLUG, '' ) != 'disabled' ){ ?>
					<form method="post" action="<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login . '/' . JAVO_ADDREVIEW_SLUG );?>">
						<p class="text-right">
							<input type="hidden" name="parent" value="<?php the_ID();?>">
							<input type="submit" class="btn btn-primary admin-color-setting" value="<?php _e('Write Review', 'javo_fr');?>">
						</p>
					</form>
				<?php
					}
				}else{ ?>
					<p class="text-right">
						<a class="btn btn-primary" data-toggle="modal" data-target="#login_panel"><?php _e('Write Review', 'javo_fr');?></a>
					</p>
				<?php }; ?>
			</div>
		</div>
		<div class="javo-single-review-not-found-more-content hidden alert alert-light-gray text-center">
			<strong><?php _e('Loaded All', 'javo_fr');?></strong>
			<p><?php _e('Not Found Reviews.', 'javo_fr');?>	</p>
		</div>

		<div class="load-more-btn text-center"><button class="btn btn-dark javo-single-review-more admin-color-setting" data-post-id="<?php the_ID();?>"><?php _e('Load More', 'javo_fr');?></button></div>
	</div><!-- 12 Columns Close -->
</div> <!-- row -->

<script type="text/javascript">
jQuery(function($){
	"use strict";
	var javo_single_review = {
		ajax:{}
		, run: function(){
			var $object = this;
			$('.javo-single-review-not-found-more-content').addClass('hidden');

			this.ajax.complete = function(){ $(window).trigger('resize'); };
			this.ajax.success = function(d){
				if( d.html != "" ){
					$('.javo-single-review-more-content').append( d.html );
					$object.ajax.data.offset += $object.ajax.data.count;
				}else{
					$('.javo-single-review-not-found-more-content').removeClass('hidden');
				};
				$('.javo-single-review-more').button('reset');
			};
			$.ajax( this.ajax );
		}
		, events: function(){
			var $object = this;
			$('body')
				.on('click' , '.javo-single-review-more', function(){
					$(this).button('loading');
					$object.ajax.data.post_id = $(this).data('post-id');
					$object.run();
				});
		}
		, init:function(){
			this.ajax.url			= "<?php echo admin_url('admin-ajax.php');?>";
			this.ajax.type			= "post";
			this.ajax.dataType		= "json";
			this.ajax.data			= {};
			this.ajax.data.action	= "get_single_review";
			this.ajax.data.count	= 3;
			this.ajax.data.offset	= 0;
			this.events();
			$('.javo-single-review-more').trigger('click');
		}




	};
	javo_single_review.init();
});

</script>