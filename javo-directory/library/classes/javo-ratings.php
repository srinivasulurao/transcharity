<?php
class javo_rating{
	var $post
	, $parent_post
	, $parent_post_id
	, $author
	, $rating_fields
	, $need_login
	, $alert_strings
	, $rating_id
	, $total_score
	, $total_average
	, $average_score
	, $parent_rating_total
	, $parent_rating_average
	, $parent_rating_average_star
	, $parent_rating_count
	, $user_total_score
	, $user_average_score;

	public function __construct($post_id=0){
		// Ajax Actions
		add_action('wp_ajax_nopriv_set_rating'		, Array($this, 'ajax_ratings_callback'));
		add_action('wp_ajax_set_rating'				, Array($this, 'ajax_ratings_callback'));

		// Filters
		add_filter('javo_rating_score_display'		, Array('javo_rating', 'javo_rating_score_display_callback'), 10, 3);
		add_filter('javo_rating_alert_strings'		, Array('javo_rating', 'javo_rating_alert_strings_callback'), 10, 3);

		// Timeline More Content
		add_action('wp_ajax_nopriv_get_timeline'	, Array($this, 'get_timeline_callback'));
		add_action('wp_ajax_get_timeline'			, Array($this, 'get_timeline_callback'));

		add_action('wp_enqueue_scripts'				, Array($this, 'wp_enqueue_scripts_callback'));

		// Post Action
		add_action('before_delete_post'				, Array($this, 'update_score'), 10, 2);
		add_action('save_post'						, Array($this, 'update_score'), 10, 2);

		// Change Status
		add_action( 'wp_insert_post'				, Array( $this, 'update_score_trigger'), 10, 3);

		if( (int)$post_id <= 0 ){ return; };
		global $javo_tso;




		// Initialize Variables.
		$this->post							= get_post( $post_id );
		$this->author						= get_userdata($this->post->post_author);
		$this->rating_fields				= $javo_tso->get('rating_field');
		$this->need_login					= $javo_tso->get('rating_logged_enable', NULL);
		$this->alert_strings				= apply_filters('javo_rating_alert_strings', null);
		$this->parent_rating_total			= (float)get_post_meta( $this->post->ID, 'rating_total'	, true );
		$this->parent_rating_average		= (float)get_post_meta( $this->post->ID, 'rating_average'	, true );
		$this->parent_rating_count			= (float)get_post_meta( $this->post->ID, 'rating_count'	, true );
		$this->parent_rating_average_star	= Array(
			'fa fa-star-o'
			, 'fa fa-star-o'
			, 'fa fa-star-o'
			, 'fa fa-star-o'
			, 'fa fa-star-o'

		);
		for($i=0; $i < round( (float)$this->parent_rating_average ); $i++){
			$this->parent_rating_average_star[$i] = 'fa fa-star';
		};

	}
	public function update_score_trigger( $post_id, $post, $is_update )
	{
		if( $post->post_type !== "ratings" ){ return; }
		$this->update_score( $post_id );
	}
	public function wp_enqueue_scripts_callback(){
		javo_get_style( "timeline.css", "javo-rating-timeline-css", "1.0.0");
	}
	public function get_timeline_callback(){
		global $javo_tso;
		$javo_query = new javo_ARRAY( $_POST );
		$javo_this_args = Array(
			"post_type"=> "ratings"
			, "post_status"=> "publish"
			, 'offset'=> (int)$javo_query->get('offset', 0)
			, 'posts_per_page'=> (int)$javo_query->get('ppp', 3)
			, "meta_query"=> Array(
				Array(
					"key"=> "rating_parent_post_id"
					, "compare"=> "="
					, "type"=> "NUMBERIC"
					, "value"=> (int)$javo_query->get('parent_post_id')
				)
			)
		);
		$javo_rating_posts = new wp_query($javo_this_args);
		ob_start();
		if( $javo_rating_posts->have_posts() ){
			$i = (int)$javo_query->get('offset', 0);
			while( $javo_rating_posts->have_posts() ){
				$i++;
				$javo_rating_posts->the_post();
				$javo_this_user_avatar_id = get_the_author_meta('avatar');

				switch( $i % 5){
					case 0: $javo_badge = "warning"; break;
					case 1: $javo_badge = "danger"; break;
					case 2: $javo_badge = "primary"; break;
					case 3: $javo_badge = "info"; break;
					case 4: $javo_badge = "success"; break;
					default: $javo_badge = "";
				};

				switch( $javo_query->get('type', 'default') ){
				case 'tab':
					?>
					<li>
						<div class="row rating-wrap">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-5">
										<div class="row">
											<div class="col-md-4 col-sm-5 col-xs-5">
												<div class="rating-author">
													<?php
													if( (int)$javo_this_user_avatar_id > 0 ){
														echo wp_get_attachment_image( $javo_this_user_avatar_id, 'javo-tiny', true, Array('class'=>'img-circle'));
													}else{
														printf('<img src="%s" class="img-circle" style="width:80px; height:80px;;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));

													};?>
													<div class="rating-total"><?php printf('%.1f', get_post_meta( get_the_ID(), 'rating_average', true ));?></div> <!-- rating-total -->
												</div> <!-- rating-author -->
											</div>
											<div class="col-md-8 col-sm-7 col-xs-7 padding-right-none">
												<div class="rating-each-details">
													<h4 class="text-right javo-accent bold-string color-9c9c9c margin-4-6 border-bottom-dashed-1px-9c9c9c padding-b2">
														<?php printf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));?>
													</h4>
													<?php echo apply_filters('javo_rating_score_display', get_the_ID(), true);?>
													<!-- javo-rating-registed-score -->
												</div> <!-- rating-each-details -->
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class="rating-comments pull-left">
											<?php echo esc_html( get_the_content() ); ?>
										</div> <!-- rating-comments -->
									</div>
								</div>

								<div class="clearfix"></div>

							</div> <!-- col-md-12 -->
						</div>
					</li>
					<?php
				break;
				case 'default':
				default:
					?>
					<li<?php echo $i % 2 == 0? ' class="jv_timeline-inverted"':'';?>>
						<div class="jv_timeline-badge <?php echo $javo_badge;?>"><i class="icon-star"></i></div>
						<div class="jv_timeline-panel">
							<div class="jv_timeline-heading">
								<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo get_the_date();?></small></p>
							</div>
							<div class="jv_timeline-body">
								<div class="row">
									<div class="col-md-2 text-center">
										<div><?php echo wp_get_attachment_image( get_the_author_meta('avatar'), 'javo-tiny', 1, Array('class'=> 'img-circle') ); ?> </div>

									</div>
									<div class="col-md-5">
										<?php echo apply_filters('javo_rating_score_display', get_the_ID());?>

									</div>
									<div class="col-md-5">
										<span><?php the_content();?></span>

									</div>
								</div><!-- Close Row -->
							</div><!-- Timeline Body-->
						</div>
					</li>
					<?php
				}; // End Switch
			}; // End While
		}; // End IF
		$javo_output = ob_get_clean();
		wp_reset_query();
		echo json_encode(Array(
			'output'		=> $javo_output
		));
		exit;



	}
	public function part_score($number, $outputType='number'){
		if( empty( $this->post ) ){ return; };
		$javo_this_args = Array(
			'post_type'=> 'ratings'
			, 'post_status'=> 'publish'
			, 'posts_per_page'=>-1
			, 'meta_query'=> Array(
				'relation'=> 'AND'
				, Array(
					'key'=> 'rating_parent_post_id'
					, 'compare'=> '='
					, 'type'=> 'NUMBERIC'
					, 'value'=> $this->post->ID
				), Array(
					'key'=> 'rating_round'
					, 'compare'=> '='
					, 'type'=> 'NUMBERIC'
					, 'value'=> $number
				)
			)
		);
		$javo_this_posts = new WP_Query($javo_this_args);

		switch( strtoupper($outputType) ){
			case 'PERCENTAGE':
				if( (int)$javo_this_posts->found_posts > 0 && $this->parent_rating_count > 0){
					$javo_part_result = ( (int) $javo_this_posts->found_posts / (int)$this->parent_rating_count ) * 100;
				}else{
					$javo_part_result = 0;
				}
				$javo_part_result = sprintf('%d%%', $javo_part_result);
			break;
			case 'NUMBER':
			default:
				$javo_part_result = (int)$javo_this_posts->found_posts;
		};
		wp_reset_postdata();
		return $javo_part_result;
	}

	public function part_key_score($parent_post_id){

		$javo_part_scores		= Array();
		$javo_this_result		= Array();
		$javo_this_args			= Array(
			'post_type'			=> 'ratings'
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> -1
			, 'meta_query'		=> Array(
				Array(
					'key'		=> 'rating_parent_post_id'
					, 'compare'	=> '='
					, 'type'	=> 'NUMBERIC'
					, 'value'	=> $parent_post_id
				)
			)
		);
		$javo_this_posts = new WP_Query( $javo_this_args );
		if( $javo_this_posts->have_posts() ){
			while( $javo_this_posts->have_posts() ){
				$javo_this_posts->the_post();
				$javo_this_scores = @unserialize(get_post_meta( get_the_ID(), 'rating_scores', true ));
				if( !empty($javo_this_scores) ){
					foreach( $javo_this_scores as $key => $value ){

						if( array_key_exists( $value['label'], $javo_part_scores ) ){
							$javo_part_scores[ $value['label'] ] += $value['score'];

						}else{
							$javo_part_scores[ $value['label'] ] = $value['score'];
						}
					}	// End Foreach
				};  // End I
			}		// End While

			foreach($javo_part_scores as $key => $item){
				$javo_this_result[$key] = $item  / $javo_this_posts->found_posts;
			}


		} // End If
		wp_reset_query();
		return $javo_this_result;

	}
	public function update_score($parent_post_id)
	{		

		if( (int)$parent_post_id <= 0 ){ return; };

		$curent_rating_id = $parent_post_id;
		$parent_post_id = get_post_type($parent_post_id) == 'ratings' ?
			get_post_meta($parent_post_id, 'rating_parent_post_id', true) :
			$parent_post_id;

		$javo_this_args = Array(
			'post_type'			=> 'ratings'
			, 'post_status'		=> 'publish'
			, 'posts_per_page'	=> -1
			, 'meta_query'		=> Array(
				Array(
					'key'		=> 'rating_parent_post_id'
					, 'compare'	=> '='
					, 'type'	=> 'NUMBERIC'
					, 'value'	=> $parent_post_id
				)
			)
		);

		$total_average = 0;
		if( null !== ( $javo_rating_posts = get_posts( $javo_this_args ) ) ){
			foreach( $javo_rating_posts as $post )
			{
				$total_average	+= (float) get_post_meta( $post->ID, 'rating_average', true);
			}		
			$average_score = (float) sprintf( '%.1f', ( $total_average / (int) max( 1, count( $javo_rating_posts ) ) ) );
			update_post_meta( $parent_post_id	, 'rating_average'	, $average_score );
			update_post_meta( $parent_post_id	, 'rating_count'	, count( $javo_rating_posts ) );
		}
	}
	public function ajax_ratings_callback(){

		// Get Variables
		$javo_query = new javo_ARRAY($_POST);
		$javo_form_meta = new javo_ARRAY($_POST['javo_rating']);

		// Default Informations
		$javo_rating_meta = Array(
			'count'			=> count( (Array)$javo_query->get( 'javo_rats', Array() ) )
			, 'ratings'		=> @serialize( $javo_query->get( 'javo_rats' ) )
			, 'summary'		=> 0
			, 'parent_post'	=> $javo_query->get('parent_post_id')
			, 'user_ip'		=> $_SERVER['REMOTE_ADDR']
		);

		// Summary Ratings Scores
		if( $javo_query->get( 'javo_rats', null ) != null ){
			foreach( $javo_query->get( 'javo_rats' ) as $index => $value ){
				$javo_rating_meta['summary'] += (float) $value['score'];
			}; // End Foreach
		}; //  End if
		$javo_rating_meta_query = new javo_ARRAY( $javo_rating_meta );

		$javo_rating_meta['average'] = (float) $javo_rating_meta_query->get('summary', 0) > 0 ?
			(float)( $javo_rating_meta_query->get('summary', 0) / $javo_rating_meta['count']) :
			0;
		$javo_rating_meta['round'] = round( (float)$javo_rating_meta['average'] );



		// Create Rating Post
		$javo_create_rating_args = Array(
			'post_type'			=> 'ratings'
			, 'post_title'		=> sprintf('[ %s: %d ][ %s: %.1f ] %s'
				, __('Target ID', 'javo_fr')
				, (int)$javo_rating_meta['parent_post']
				, __('Rating', 'javo_fr')
				, (int)$javo_rating_meta['average']
				, $javo_form_meta->get('name', 'Noname' )
			), 'post_author'		=> get_current_user_id()
			, 'post_content'	=> $javo_form_meta->get('content', 'None' )
			, 'post_status'		=> 'publish'
		);
		$this->rating_id = wp_insert_post($javo_create_rating_args);

		if( (int)$this->rating_id > 0 ){

			$this->insert('rating_scores'			, $javo_rating_meta['ratings']);
			$this->insert('rating_parent_post_id'	, $javo_rating_meta['parent_post']);
			$this->insert('rating_current_user_ip'	, $javo_rating_meta['user_ip']);
			$this->insert('rating_total'			, $javo_rating_meta['summary']);
			$this->insert('rating_average'			, $javo_rating_meta['average']);
			$this->insert('rating_round'			, $javo_rating_meta['round']);
			$javo_this_state = 1;

			// Update Score
			$this->update_score( $javo_rating_meta[ 'parent_post' ] );

		}else{
			$javo_this_state = 0;
		};
		echo json_encode( Array(
			'state'=> $javo_this_state
			, 'count'=> $javo_rating_meta['ratings']
		) );
		exit;
	}
	public function insert($key, $value=''){
		if( (int)$this->rating_id <= 0 ){ return; };
		update_post_meta( $this->rating_id, $key, $value);
	}
	static function javo_rating_alert_strings_callback(){
		return Array(
			'no_login'=> __('Please Login', 'javo_fr')
		);
	}
	static function javo_rating_score_display_callback( $p_id, $show_title=FALSE, $total=FALSE ){
		$javo_rating_score = @unserialize(get_post_meta( $p_id, 'rating_scores', true));
		ob_start();

		if( $total ){
			?>
			<div class="row">
				<div class="col-md-6 text-right">
					<?php _e('Total', 'javo_fr');?>
				</div>
				<div class="col-md-6"><?php echo get_post_meta( $p_id, 'rating_average', true);?></div>
			</div> <!-- row -->
			<?php
		};
		if( !empty( $javo_rating_score ) ){
			foreach($javo_rating_score as $key => $value ){
				?>
				<div class="row">
					<?php if( $show_title ){ ?>
					<div class="col-md-6 col-sm-6 col-xs-6 text-right">
						<?php _e($value['label'], 'javo_fr');?>
					</div>
					<?php }; ?>
					<div class="<?php echo $show_title? 'col-md-6 col-sm-6 col-xs-6':'col-md-12 col-sm-12 col-xs-12';?> javo-tooltip" title="<?php _e($value['label'], 'javo_fr');?>" data-direction="left"	>
						<div class="javo-rating-registed-score" data-score="<?php echo (float)$value['score'];?>"></div>
					</div>
				</div> <!-- row -->
				<?php
			}; // End Foreach
		}; // End If ?>

		<?php
		return ob_get_clean();
	}
	public function form($edit=0){
		global
			$javo_tso
			, $javo_custom_item_label;

		$javo_this_current_user = wp_get_current_user();
		ob_start(); ?>

		<div class="rating-add-form">
			<form class="form-horizontal" role="form" id="javo_rating_form">
				<input name="action" value="set_rating" type="hidden">
				<input name="parent_post_id" value="<?php echo (int)$edit > 0? $edit : $this->post->ID;?>" type="hidden">
				<div class="row">
					<div class="col-md-6 col-sm-6 rating-stars">
						<ul class="list-group">
							<?php
							if( !empty($this->rating_fields) ){
								foreach( $this->rating_fields as $index=> $label){
									?>
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6 col-sm-6">
												<span class="javo-raintg-form-field-label"><?php _e($label, 'javo_fr');?></span>
											</div>
											<div class="col-md-6 col-sm-6">
												<span class="javo_rat_star" data-score="0" data-input-name="javo_rats[<?php echo $index;?>][score]"></span>
												<input type="hidden" name="javo_rats[<?php echo $index;?>][label]" value="<?php echo $label;?>">
											</div>
										</div><!-- /.row -->
									</li>
									<?php
								};
							};?>
						</ul>
					</div>
					<div class="col-md-6 col-sm-6 rating-input-box">
						<?php
						if( is_user_logged_in() ){
							// User logged
							printf('<input name="javo_rating[name]" type="hidden" value="%s %s">', $javo_this_current_user->first_name, $javo_this_current_user->last_name);
							printf('<input name="javo_rating[email]" type="hidden" value="%s">', $javo_this_current_user->user_email);
						}else{
							printf('<input name="javo_rating[logged]" type="hidden" value="%s">', 'no');
						}; ?>
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<textarea name="javo_rating[content]" id="javo_rating_content" class="form-control" <?php echo !is_user_logged_in() ? 'readonly': '';?>><?php echo !is_user_logged_in()? $this->alert_strings["no_login"]:'';?></textarea>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<input id="javo_rating_submit" class="btn btn-dark col-md-12 admin-color-setting" value="<?php printf( __('Submit %s', 'javo_fr'), $javo_custom_item_label->get('ratings', __('Ratings','javo_fr')));?>" type="button">
								</div>
							</div>
						</div>

					</div>
				</div><!-- /. row -->
				<div class="row">
					<div class="col-md-offset-10 col-md-2	submit-btn-wrap">
						<div class="form-group">

						</div>
					</div><!-- 12 Columns Close -->
				</div><!-- Row Close -->
			</form><!-- Close Form -->
		</div><!-- rating-add-form -->
		<fieldset>
			<input type="hidden" name="javo_direct_rating" value="<?php echo $javo_tso->get('direct_rating', '');?>">
		</fieldset>


		<script type="text/javascript">
		jQuery(document).ready(function($){
			"use strict";
			var is_logged = "<?php echo is_user_logged_in();?>";

			// Set Rating Fields.
			$('.javo_rat_star').each(function(k, v){
				$(this).raty({
					starOff: '<?php echo JAVO_IMG_DIR?>/star-off.png'
					, starOn: '<?php echo JAVO_IMG_DIR?>/star-on.png'
					, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half.png'
					, half: true
					, width:150
					, scoreName: $(this).data('input-name')
					, score: function() {
						return $(this).attr('data-score');
					}
				});
			});

			$("#javo_rating_content").on('focus', function(){
				if(!is_logged){
					$('#login_panel').modal();
					return false;
				};
			});

			// Save Ratings
			$("#javo_rating_submit").on("click", function(e){

				var is_rat_block = "<?php echo $this->need_login;?>";
				var _this = $(this);
				var options = {
					url:'<?php echo admin_url("admin-ajax.php");?>'
					, type:'post'
					, dataType:'json'
				};

				if(!is_logged){
					$('#login_panel').modal();
					return false;
				};
				if( $('#javo_rating_content').val().length <= 0 ){
					/* Empty Comment ? */
					$('#javo_rating_content').addClass('isNull');
					return false;
				}
				options.data = $("form#javo_rating_form").serialize();
				options.error	= function(e){
					console.log( e.responseText );
				};
				options.success = function(d){
					var is_pending = $('[name="javo_direct_rating"]').val() == 'no';

					if( d.state ){
						if( is_pending )
						{
							$.javo_msg({
								content:"<?php _e('Your rating has been successfully submitted. <br>Your rating is waiting for approval.', 'javo_fr');?>"
								, delay:5000
							}, function(){
								document.location.reload(true);
							});

						}else{
							document.location.reload(true);
						}
					}else{
						$.jav_msg({ content:"<?php _e('Failed to charge a Rating. ', 'javo_fr');?>" , delay:5000 });
					};
					_this.button('reset');
				};
				_this.button('loading');
				$.ajax(options);
			});
		});
		</script>

		<?php
		$javo_this_content = ob_get_clean();
		return $javo_this_content;
	}
	public function time_line( $view_type='timeline' ){
		global $post, $javo_custom_item_label;
		ob_start();?>

		<div class="row single-rating-list">
			<div class="col-md-12">
				<ul class="<?php echo $view_type=='timeline'? 'jv_timeline':'';?> javo-rating-timeline-content"></ul>
				<div class="javo-rating-timeline-nofound hidden">
					<div class="alert alert-light-gray text-center">
						<strong><?php _e('Loaded All', 'javo_fr');?></strong>
						<p><?php printf(__('Not found any %s', 'javo_fr'), $javo_custom_item_label->get('ratings', 'Ratings'));?>
					</div>
				</div>
			</div>
		</div>
		<div class="row rating-load-more">
			<div class="col-md-12 text-center">
				<a class="btn btn-primary javo-rating-timeline-more admin-color-setting"><?php _e('Load More...', 'javo_fr');?></a>
			</div>
		</div>
		<script type="text/javascript">
		jQuery( function($){

			var javo_rating_more_content = {
				ajaxurl: "<?php echo admin_url('admin-ajax.php');?>"
				, attr:{a:'a'}
				, init:function(){
					this.attr.url					= this.ajaxurl;
					this.attr.type					= 'post';
					this.attr.dataType				= 'json';
					this.attr.data					= {};
					this.attr.data.action			= 'get_timeline';
					this.attr.data.ppp				= 3;
					this.attr.data.offset			= 0;
					this.attr.data.type				= '<?php echo $view_type;?>';
					this.attr.data.parent_post_id	= '<?php echo $post->ID;?>';
					this.attr.error					= this.error;
					this.attr.success				= this.success;
					this.attr.complete				= this.complete;
					$(document).on('click', '.javo-rating-timeline-more', this.run);
					$('.javo-rating-timeline-more').trigger('click');

				}
				, error:function(e){

				}
				, success: function(d){
					if( d.output ){
						$('ul.javo-rating-timeline-content').append( d.output );
					}else{
						$('.javo-rating-timeline-nofound').removeClass('hidden');
					};
					javo_rating_more_content.attr.data.offset += javo_rating_more_content.attr.data.ppp;
					$('.javo-rating-registed-score').each( javo_rating_more_content.apply_rating);
					$('.javo-rating-timeline-more').button('reset');
				}
				, complete: function(){
					$(window).trigger('resize');
					jQuery('ul.javo-rating-timeline-content .javo-tooltip').each( function(i, v){
						var options = {};
						if( typeof( $(this).data('direction') ) != 'undefined' ){
							options.placement = $(this).data('direction');
						};
						$(this).tooltip(options);
					});
				}
				, apply_rating:function(k, v){
					$(this).raty({
						starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
						, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
						, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
						, half: true
						, readOnly: true
						, score: $(this).data('score')
					}).css('width', '');
				}
				, run: function(e){
					e.preventDefault();
					$(this).button('loading');
					$('.javo-rating-timeline-nofound').addClass('hidden');
					$.ajax( javo_rating_more_content.attr );
				}
			}
			javo_rating_more_content.init();
		} );
		</script>

		<?php
		return ob_get_clean();
	}
	public function ratings($all=FALSE, $count=-1, $view_type='normal', $length=0){
		global
			$javo_tso
			, $javo_custom_item_label;


		$javo_this_args = Array(
			"post_type"=> "ratings"
			, "post_status"=> "publish"
			, 'posts_per_page'=> $count
			, "meta_query"=> Array(
				Array(
					"key"=> "rating_parent_post_id"
					, "compare"=> "="
					, "type"=> "NUMBERIC"
					, "value"=> $this->post->ID
				)
			)
		);
		if($all){ unset($javo_this_args['meta_query']); };
		$javo_rating_posts = new wp_query($javo_this_args);
		ob_start(); ?>
		<div class="javo-rat-area row">
			<div class="col-md-12">
			<?php
			if( $javo_rating_posts->have_posts() ){
				while( $javo_rating_posts->have_posts() ){
					$javo_rating_posts->the_post();
					$this->user_average_score = get_post_meta( get_the_ID(), 'rating_average', true);
					$this->parent_post_id = get_post_meta( get_the_ID(), 'rating_parent_post_id', true);
					$this->parent_post = get_post( $this->parent_post_id );
					switch($view_type){

					// Shortcode Display Type
					case 'tab':
						?>
						<div class="row rating-wrap">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-5">
										<div class="rating-author pull-left">
											<?php
											if(get_the_author_meta('avatar')){
												echo wp_get_attachment_image( get_the_author_meta('avatar'), 'javo-tiny', 1, Array('class'=> 'img-circle') );
											}else{
												printf('<img src="%s" class="img-responsive wp-post-image img-circle" style="width:80px; height:80px;">', $javo_tso->get('no_image', JAVO_IMG_DIR.'/no-image.png'));
											}?>

											<div class="rating-total"><?php printf('%.1f', get_post_meta( get_the_ID(), 'rating_average', true ));?></div> <!-- rating-total -->
										</div> <!-- rating-author -->

										<div class="rating-each-details pull-left">
											<?php echo apply_filters('javo_rating_score_display', get_the_ID(), false);?>
											<!-- javo-rating-registed-score -->
										</div> <!-- rating-each-details -->
									</div>
									<div class="rating-comments pull-left">
										<a href="<?php echo get_permalink($this->parent_post_id);?>#item-ratings"><span><?php printf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));?> : </span>
											<?php
											if( (int)$length > 0 ){
												echo javo_str_cut( strip_tags( get_the_content() ), $length);
											}else{
												echo strip_tags( get_the_content() );
											};?>
										</a>
									</div> <!-- rating-comments -->
								</div>
								<div class="clearfix"></div>

							</div> <!-- col-md-12 -->
						</div>

						<?php
					break;
					case 'detail':
						?>
						<ul class="list-group">
							<li class="list-group-item">
								<div class="row">
									<div class="col-md-2 text-center">
										<div><?php echo wp_get_attachment_image( get_the_author_meta('avatar'), 'javo-tiny', 1, Array('class'=> 'img-circle') ); ?> </div>
									</div>
									<div class="col-md-5">
										<?php echo apply_filters('javo_rating_score_display', get_the_ID());?>
									</div>
									<div class="col-md-5">
										<a href="<?php echo get_permalink( $this->parent_post->ID);?>#item-ratings">
											<h3><?php echo $this->parent_post->post_title;?></h3>
											<p><?php the_content();?></p>
										</a>
									</div>
								</div><!-- Close Row -->
							</li><!-- Panel Body -->
						</ul><!-- Close Panel -->
						<?php
					break;

					// Single Item Page Type
					case 'normal':
					default:
						?>
						<ul class="list-group">
							<li class="list-group-item">
								<div class="row">
									<div class="col-md-2 text-center">
										<div><?php echo wp_get_attachment_image( get_the_author_meta('avatar'), 'javo-tiny', 1, Array('class'=> 'img-circle') ); ?> </div>
									</div>
									<div class="col-md-2">
										<?php echo apply_filters('javo_rating_score_display', get_the_ID());?>
									</div>
									<div class="col-md-8">
										<span><?php the_content();?></span>
									</div>
								</div><!-- Close Row -->
							</li><!-- Panel Body -->
						</ul><!-- Close Panel -->
					<?php
					}; // End Switch
				}// End While;
			}else{
				_e('NO FOUND '.$javo_custom_item_label->get('ratings', 'Ratings'), 'javo_fr');
			}// End If
			?>
			</div><!-- 12 Columns Close -->
		</div><!-- Rating Area Clse -->
		<script type="text/javascript">
		jQuery(document).ready(function($){
			"use strict";
			$('.javo-rating-registed-score').each(function(k,v){
				$(this).raty({
					starOff: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
					, starOn: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
					, starHalf: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
					, half: true
					, readOnly: true
					, score: $(this).data('score')
				});
			});

		});
		</script>
		<?php
		wp_reset_postdata();
		$javo_this_content = ob_get_clean();
		return $javo_this_content;
	}
};
new javo_rating();