<?php
/* Template Name: Map (Wide Style) */

global
	$javo_tso
	, $jv_str
	, $javo_tso_map;

$javo_this_tax_common_args = Array( 'hide_empty'=> 0, 'parent'=> 0);
$javo_this_filter_taxoomies = Array();

if( $javo_tso->get('map_wide_hide_category', 'on') != 'off' ){
	$javo_this_filter_taxoomies[] = 'item_category';
};
if( $javo_tso->get('map_wide_hide_location', 'on') != 'off' ){
	$javo_this_filter_taxoomies[] = 'item_location';
};

// Cluster Setup
{
	if( '' === ( $javo_this_map_opt = get_post_meta( get_the_ID(), 'javo_map_page_opt', true) ) )
	{
		$javo_this_map_opt = Array();
	}
	$javo_mopt = new javo_ARRAY( $javo_this_map_opt );
}

// Get Requests ( Mysql > 4.0 )
{
	$javo_get_query					= new javo_ARRAY( $_GET );
	$javo_post_query				= new javo_ARRAY( $_POST );
	$javo_current_cat				= $javo_get_query->get( 'category', $javo_post_query->get( 'category', 0 ) );
	$javo_current_loc				= $javo_get_query->get( 'location', $javo_post_query->get( 'location', 0 ) );
	$javo_current_key				= $javo_get_query->get( 'keyword', $javo_post_query->get( 'keyword', null ) );
	$javo_current_geo				= $javo_get_query->get( 'geolocation', $javo_post_query->get( 'geolocation', null ) );
	$javo_current_rad				= $javo_get_query->get( 'radius_key', $javo_post_query->get( 'radius_key', null ) );
}

// Filter Defaults
{
	$javo_filter_defaults = Array(
		'item_category'		=> $javo_current_cat
		, 'item_location'	=> $javo_current_loc
	);
}
$mail_alert_msg				= $jv_str['javo_email'];
// Get Item Tages
$javo_all_tags					= "";
foreach( get_tags( Array( 'fields' => 'names' ) ) as $tags )
{
	$javo_all_tags				.= "{$tags}|";
}
$javo_all_tags = substr( $javo_all_tags, 0, -1 );
// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_map_boxes_enq' );
	function javo_map_boxes_enq()
	{
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'Google-Map-Info-Bubble' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jquery-type-header' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-nouiSlider' );
		wp_enqueue_script( 'jQuery-javo-Emailer' );
	}
}

get_header(); ?>


<!-- Javo Map Options End -->

<div id="javo-map-wide-wrapper">
	<div class="javo_somw_panel row mobile-hidden-panel <?php echo $javo_tso_map->get('map_wide_content_overflow', null) == 'overflow'? 'no-scroll':'';?>">
		<div class="col-md-12">

			<?php if($javo_tso->get('map_wide_multitab', null) != 'off'): ?>
				<div class="row map-top-btns">
					<div class="col-md-12">
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<a class="btn btn-dark active" data-javo-map-mode="list"><?php _e('Filter', 'javo_fr');?></a>
							<!-- <a class="btn btn-dark" data-javo-map-mode="featured"><?php _e('Featured', 'javo_fr');?></a>
							<a class="btn btn-dark" data-javo-map-mode="favorite"><?php _e('Favorites', 'javo_fr');?></a> -->
						</div><!-- /.btn-Grouop -->
					</div><!-- /.col-md-12 -->
				</div> <!-- map-top-btns -->
			<?php endif; ?>

			<div class="row category-btns-wrap">
				<form role="form" onsubmit="return false">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<?php

								// Filter Element Type
								echo apply_filters('javo_wide_map_control_filter', $javo_this_filter_taxoomies, $javo_tso_map->get('map_wide_filter_type', 'button'), $javo_filter_defaults);
								?>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->

						<?php
						// Use Keyword Input
						if( $javo_tso->get('map_keyword', null) != 'off'): ?>
							<div class="row">
								<div class="col-md-12">
									<h4 class="title"><?php _e('Keyword', 'javo_fr');?></h4>
									<input id="javo_keyword" type="text" class="form-control input-md" value="<?php echo $javo_current_key; ?>">
								</div><!-- /.col-md-12 -->
							</div><!-- /.row -->
						<?php endif;?>



						<div class="row my-location">
							<div class="col-md-12">
								<h4 class="title"><?php _e('My Location', 'javo_fr'); ?></h4>
								<div class="pull-left my-location-btn">
									<button type="button" class="btn-map-panel active javo-tooltip javo-map-wide-goto-my-position" title="<?php _e('Please accept to access your location.', 'javo_fr');?>"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php _e('My Location', 'javo_fr'); ?></button>
								</div> <!-- col-md-6 -->
								<div class="pull-left distance">
									<div class="javo-geoloc-slider"></div>
									<input type="hidden" javo-wide-map-round>
								</div> <!-- col-md-6 -->
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->

					</div> <!-- col-md-12 -->
				</form>
			</div><!-- /.category-btns-wrap -->

			<section class="newrow">
				<article class="javo_somw_list_content"></article>
				<button type="button" class="btn btn-default btn-block" data-javo-map-load-more>
					<i class="fa fa-refresh"></i>
					<?php _e("Load More", 'javo_fr');?></button>
				</button>
			</section>

		</div> <!-- col-md-12 -->

	</div> <!-- javo_somw_panel row -->
	<div class="javo-wide-map-container">
		<div class="map_cover"></div>
		<div class="map_area"></div> <!-- map_area : it shows map part -->
		<a class="btn btn-default active wide-map" data-map-move-allow><i class="fa fa-unlock"></i></a>
	</div>
	<div class="javo_somw_panel row mobile-display-panel">
		<div class="col-md-12">

			<?php if($javo_tso->get('map_wide_multitab', null) != 'off'): ?>
				<div class="row map-top-btns">
					<div class="col-md-12">
						<div class="btn-group btn-group-justified" data-toggle="buttons">
							<a class="btn btn-dark active" data-javo-map-mode="list"><?php _e('Total', 'javo_fr');?></a>
							<a class="btn btn-dark" data-javo-map-mode="featured"><?php _e('Features', 'javo_fr');?></a>
							<a class="btn btn-dark" data-javo-map-mode="favorite"><?php _e('Favorite', 'javo_fr');?></a>
						</div><!-- /.btn-Grouop -->
					</div><!-- /.col-md-12 -->
				</div> <!-- map-top-btns -->
			<?php endif; ?>

			<div class="row category-btns-wrap">
				<form role="form" onsubmit="return false">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<?php
								// Filter Element Type
								echo apply_filters('javo_wide_map_control_filter', $javo_this_filter_taxoomies, $javo_tso_map->get('map_wide_filter_type', 'button'), $javo_filter_defaults);
								?>
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->

						<?php
						// Use Keyword Input
						if( $javo_tso->get('map_keyword', null) != 'off'): ?>
							<div class="row">
								<div class="col-md-12">
									<h4 class="title"><?php _e('Keyword', 'javo_fr');?></h4>
									<input id="javo_keyword" type="text" class="form-control input-md">
								</div><!-- /.col-md-12 -->
							</div><!-- /.row -->
						<?php endif;?>

						<input type="hidden" id="javo-map-box-location-ac" value="<?php echo $javo_current_rad;?>">

						<div class="row my-location">
							<div class="col-md-12">
								<h4 class="title"><?php _e('My Location', 'javo_fr'); ?></h4>
								<div class="pull-left my-location-btn">
									<button type="button" class="btn-map-panel active javo-tooltip javo-map-wide-goto-my-position" title="<?php _e('Please accept to access your location.', 'javo_fr');?>"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php _e('My Location', 'javo_fr'); ?></button>
								</div> <!-- col-md-6 -->
								<div class="pull-left distance">
									<div class="javo-geoloc-slider"></div>
									<input type="hidden" javo-wide-map-round>
								</div> <!-- col-md-6 -->
							</div><!-- /.col-md-12 -->
						</div><!-- /.row -->

					</div> <!-- col-md-12 -->
				</form>
			</div><!-- /.category-btns-wrap -->

			<section class="newrow">
				<article class="javo_somw_list_content"></article>
			</section>

		</div> <!-- col-md-12 -->
	</div> <!-- javo_somw_panel row -->
	<div class="mobile-map">
		<a class="go-under-map"><?php _e('Move to search form', 'javo_fr');?></a>
	</div> <!-- mobile-map-->
</div><!-- Gmap -->


<!-- Javo Map Options -->
<fieldset class="hidden">
	<?php
	$upload_folder	= wp_upload_dir();
	$blog_id		= get_current_blog_id();
	$lang			= defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';

	if( 'use' !== $javo_tso->get( 'cross_doamin', '' ) ) {
		$json_file		= "{$upload_folder['baseurl']}/javo_all_items_{$blog_id}_{$lang}.json";
	}else{
		$json_file		= "javo_all_items_{$blog_id}_{$lang}.json";

	} ?>
	<input type="hidden" javo-map-distance-unit value="<?php echo $javo_tso_map->get('distance_unit', __('km', 'javo_fr'));?>">
	<input type="hidden" javo-map-distance-max value="<?php echo (float)$javo_tso_map->get('distance_max', '500');?>">
	<input type="hidden" javo-map-read-more value="<?php echo $javo_tso_map->get('map_wide_read_more', 'pagination');?>">
	<input type="hidden" javo-cross-domain value="<?php echo $javo_tso->get( 'cross_doamin', '');?>">
	<input type="hidden" javo-map-all-items value="<?php echo $json_file; ?>">
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get('poi', 'on');?>">
	<input type="hidden" javo-cluster-multiple value="<?php _e("This place contains multiple places. please select one.", 'javo_fr');?>">
	<input type="hidden" javo-marker-trigger-zoom value="<?php echo $javo_tso_map->get('trigger_zoom', 18);?>">
	<input type="hidden" javo-ajax-url value="<?php echo admin_url( 'admin-ajax.php' );?>">
	<input type="hidden" javo-map-all-tags value="<?php echo $javo_all_tags; ?>">
	<input type="hidden" javo-map-item-not-found value="<?php echo $jv_str['not_found_item'];?>">
	<input type="hidden" javo-server-error value="<?php echo $jv_str['server_error'];?>">
	<input type="hidden" javo-marker-trigger-zoom value="<?php echo $javo_tso_map->get('trigger_zoom', 18);?>">
	<input type="hidden" javo-cluster-onoff value="<?php echo $javo_mopt->get('cluster', null);?>">
	<input type="hidden" javo-cluster-level value="<?php echo $javo_mopt->get('cluster_level', null);?>">
	<input type="hidden" javo-initial-panel value="<?php echo $javo_mopt->get('wide_panel_state', null);?>">
	<input type="hidden" javo-is-geoloc value="<?php echo $javo_current_geo?>">
</fieldset>


<script type="text/html" id="javo-map-loading-template">
	<div class="text-center" id="javo-map-info-w-content">
		<img src="<?php echo JAVO_IMG_DIR;?>/loading.gif" width="50" height="50">
	</div>
</script>
<script type="text/template" id="javo-map-wide-content-loading">
<div class="text-center">
	<img src="<?php echo JAVO_THEME_DIR;?>/assets/images/loading.gif" width="64">
	<span><?php _e('Loading', 'javo_fr');?></span>
</div><!-- /.text-center -->
</script>
<script type="text/template" id="javo-map-wide-content-not-found">
<div class="text-center">
	<h2><?php _e('Not Found Items.', 'javo_fr');?></h2>
</div><!-- /.text-center -->
</script>

<script type="text/html" id="javo-map-wide-panel-favorite-nologin">

	<div class="row">
		<div class="col-md-12 margin-100-0 text-center color-fff">
			<?php _e( "Please login to display your favorite items" , 'javo_fr' ); ?>
			<butotn type="button" class='btn btn-primary' data-toggle="modal" data-target="#login_panel">
				<i class="fa fa-lock"></i>
				<?php _e("Login", 'javo_fr');?>
			</button>
		</div>
	</div>

</script>

<script type="text/template" id="javo-map-wide-panel-content">
<div class='row javo_somw_list_inner'>
	<div class='col-sm-3 col-xs-2'><img src="{thumbnail_url}" width="50" height="50"></div><!-- col-md-3 thumb-wrap -->
	<div class='col-sm-9 col-xs-10 meta-wrap'>
		<div class='javo_somw_list'>
			<a href='javascript:' class='javo-hmap-marker-trigger' data-id="mid_{post_id}" data-post-id="{post_id}">{post_title}</a>
		</div>
		<div class='javo_somw_list'>{category} / {location}</div>
	</div><!-- col-md-9 meta-wrap -->
</div><!-- row -->
</script>
<script type="text/template" id="javo-map-wide-infobx-content">

	<div class="javo_somw_info panel" style="min-height:220px;">
		<div class="des">
			<ul class="list-unstyled">
				<li><div class="prp-meta"><h4><strong>{post_title}</h4></strong></div></li>
				<li><div class="prp-meta">{phone}</div></li>
				<li><div class="prp-meta">{mobile}</div></li>
				<li><div class="prp-meta">{website}</div></li>
				<li>
					<div class="prp-meta">{address}
						<a href="{permalink}#item-location" class="btn btn-primary btn-get-direction btn-sm"><?php _e("Get directions", "javo_fr"); ?></a>
					</div>
				</li>
			</ul>
		</div> <!-- des -->

		<div class="pics">
			<div class="thumb">
				<a href="{permalink}" target="_blank">{thumbnail}</a>
			</div> <!-- thumb -->
			<div class="img-in-text">{category}</div>
			<div class="javo-left-overlay">
				<div class="javo-txt-meta-area custom-bg-color-setting">{location}</div> <!-- javo-txt-meta-area -->

				<div class="corner-wrap">
					<div class="corner admin-color-setting"></div>
					<div class="corner-background admin-color-setting"></div>
				</div> <!-- corner-wrap -->
			</div> <!-- javo-left-overlay -->
		</div> <!-- pic -->

		<div class="row">
			<div class="col-md-12">
				<div class="btn-group btn-group-justified pull-right">
					<a class="btn btn-primary btn-sm" onclick="javo_map_wide_func.brief_run(this);" data-id="{post_id}">
						<i class="fa fa-user"></i> <?php _e("Brief", "javo_fr"); ?>
					</a>
					<a href="{permalink}" class="btn btn-primary btn-sm">
						<i class="fa fa-group"></i> <?php _e("Detail", "javo_fr"); ?>
					</a>
					<a href="javascript:" class="btn btn-primary btn-sm" onclick="javo_map_wide_func.contact_run(this)" data-to="{email}" data-username="{author_name}" data-itemname="{post_title}">
						<i class="fa fa-envelope"></i> <?php _e("Contact", "javo_fr"); ?>
					</a>
				 </div><!-- btn-group -->
			</div> <!-- col-md-12 -->
		</div> <!-- row -->
	</div> <!-- javo_somw_info -->
</script>

<script type="text/javascript">
jQuery(function($){
	"use strict";

	window.javo_map_wide_func = {
		init: function(){

			var obj = this;

			this.ajaxurl		= $("[javo-ajax-url]").val();
			this.tags			= $('[javo-map-all-tags]').val().toLowerCase().split( '|' );

			// Initialize Map
			this.map_options	= {
				map:{
					options: {
						mapTypeId: google.maps.MapTypeId.ROADMAP
						, mapTypeControl: false
						, panControl: false
						, scrollwheel: true
						, streetViewControl: true
						, zoomControl: true
						, zoomControlOptions: {
							position: google.maps.ControlPosition.RIGHT_BOTTOM
							, style: google.maps.ZoomControlStyle.BIG
						}
					}
				}
				,panel:{
					options:{
						content:'<span class="javo_somw_opener active"><?php _e('Hide', 'javo_fr');?></span>'
						, top: '50%'
						, left: 0
					}
				}
			};

			this.el					= $('.map_area');
			this.panel_el			= $(".javo_somw_panel");
			this.el.gmap3( this.map_options );
			this.map				= this.el.gmap3( 'get' );
			this.infoWindo			= new InfoBubble({
				minWidth			: 362
				, minHeight			: 225
				, overflow			: true
				, shadowStyle		: 1
				, padding			: 5
				, borderRadius		: 10
				, arrowSize			: 20
				, borderWidth		: 1
				, disableAutoPan	: false
				, hideCloseButton	: false
				, arrowPosition		: 50
				, arrowStyle		: 0
			});

			// Set Items
			this.items				= {};

			var is_cross_domain		= $( "[javo-cross-domain]" ).val();
			var json_ajax_url		= $( "[javo-map-all-items]").val();
			var parse_json_url		= json_ajax_url;

			if( is_cross_domain )
			{

				parse_json_url = this.ajaxurl;
				parse_json_url += "?action=javo_get_json";
				parse_json_url += "&fn=" + json_ajax_url;
				parse_json_url += "&callback=?";
			}

			$.getJSON( parse_json_url, function( response ){

				obj.items			= response;
				$.each( response, function( index, key ){
					obj.tags.push( key.post_title );
				} );

				obj.setKeywordAutoComplete();

				if( $( "#javo-map-box-location-ac" ).val() ) {
					obj.setGetLocationKeyword( { keyCode:13, preventDefault: function(){} } );
				}else{
					obj.filter();
				}



				if( $( "[javo-is-geoloc]" ).val() )
				{
					obj.getGeoLocation( { preventDefault:function(){}} );
				}

			});

			this.distance_unit		= $('[javo-map-distance-unit]').val();
			this.distance			= $('[javo-map-distance-unit]').val() == 'mile' ? 1609.344 : 1000;
			this.distance_max		= $('[javo-map-distance-max]').val();
			this.panel_type			= "list";


			// Contact Form
			this.contact_form		= $( "#author_contact" ).find( 'form' );
			this.contact_form_smt	= this.contact_form.find( '#contact_submit' );

			// Events
			; $( document )
				.on( 'click'		, "button[data-filter]"				, this.filter_button )
				.on( 'change'		, "select[name^=filter]"			, this.filter_select )
				.on( 'keydown'		, "#javo_keyword"					, this.keyword_ )
				.on( 'click'		, '[data-javo-map-load-more]'		, this.load_more )
				.on( 'click'		, '.javo-hmap-marker-trigger'		, this.trigger_marker )
				.on( 'click'		, '.javo-map-wide-goto-my-position' , this.getGeoLocation )
				.on( 'click'		, 'a[data-javo-map-mode]'			, this.switchMode )
				.on( 'click'		, '.javo_somw_opener'				, this.panel_opener )
				.on( 'click'		, '[data-map-move-allow]'			, this.map_locker )
				.on( 'click'		, this.contact_form_smt.selector	, this.submit_contact )

			; $( window )
				.on( 'resize'		, this.resize );

			this.distance_slide = {
				start: [300]
				, step: 1
				, range:{ min:[1], max:[ parseInt( this.distance_max ) ] }
				, serialization:{
					lower:[
						$.Link({
							target: $('[javo-wide-map-round]')
							, format:{ decimals:0 }
						})
						, $.Link({
							target: '-tooltip-<div class="javo-slider-tooltip"></div>'
							, method: function(v){
								$(this).html('<span>' + v + '&nbsp;' + obj.distance_unit + '</span>');
							}, format:{ decimals:0, thousand:',' }
						})
					]
				}
			};
			$(".javo-geoloc-slider").noUiSlider( this.distance_slide );
			$('select[name^="filter"]').chosen({ width:"100%" });

			this.resize();
			if( $( "[javo-initial-panel]" ).val() )
			{
				$( ".javo_somw_opener" ).trigger( 'click' );
			}
		}

		, resize:function()
		{
			var obj		= window.javo_map_wide_func;
			var offY	= 0;
			var panel	= $('.javo_somw_panel.row.mobile-hidden-panel');

			offY += $('#wpadminbar').outerHeight();
			offY += $('#header-one-line').outerHeight();
			offY += $('.footer-bottom').outerHeight();
			offY = $(window).height() - offY;
			obj.el.height( offY );
			panel.height( offY );

			if( $( ".javo_somw_opener" ).hasClass( 'active' ) )
			{
				obj.panel_el
					.removeClass('hidden')
					.css({
						marginLeft: ( -$('.javo_mhome_sidebar').outerWidth()) + 'px'
					});

				$( ".map_area" ).css( 'margin-left', parseInt( $( '.javo_somw_panel' ).outerWidth() ) );

			}

			$(".javo_somw_opener").css({
				marginTop: -( $(".javo_somw_opener").outerHeight() / 2 ) + 'px'
			});
		}

		, switchMode: function( e )
		{
			e.preventDefault();

			var obj = window.javo_map_wide_func;

			$('.category-btns-wrap').addClass('hidden');

			obj.panel_type =  $(this).data('javo-map-mode');

			if( $(this).data('javo-map-mode') == 'list' ){
				$('.category-btns-wrap').removeClass('hidden');
				$('[name^="filter"]').val('').trigger('chosen:updated');

			}else if( $(this).data('javo-map-mode') == 'favorite' ){
				if( !$('body').hasClass('logged-in') ){
					$("#login_panel").modal();
				}
			};

			$(this).parent().find('a').removeClass('active');


			$( "button[data-filter]" ).removeClass('active' );
			$( "button[data-filter]" ).closest('.newrow').find( 'button:first' ).addClass('active' );

			obj.filter();
		}

		, toggleSwitchButton: function( e )
		{
			var button = $( "a[data-javo-map-mode]" );

			if( e ) {
				button
					.prop( 'disabled', false )
					.removeClass( 'disabled');
			}else{
				button
					.prop( 'disabled', true )
					.addClass( 'disabled');
			}
		}

		, panel_opener: function( e )
		{
			e.preventDefault();

			var _panel = $(".javo_somw_panel");
			if( $(this).hasClass("active") ){
				//$(this).animate({marginLeft:-(parseInt(_panel.outerWidth())) + "px" }, 500);
				_panel.animate({marginLeft:-(parseInt(_panel.outerWidth())) + "px"}, 500);
				$(".map_area").animate({marginLeft:0}, 500, function(){
					$(".map_area").gmap3({ trigger:"resize" });
				});
				$(this).text("<?php _e('Show','javo_fr'); ?>").removeClass('active');
			}else{
				//$(this).animate({marginLeft:0}, 500);
				_panel.animate({marginLeft:0}, 500);
				$(".map_area").animate({marginLeft:parseInt(_panel.outerWidth(	)) + "px"}, 500, function(){
					$(".map_area").gmap3({ trigger:"resize" });
				});
				$(this).text("<?php _e('Hide','javo_fr'); ?>").addClass('active');
			};
		}

		, map_locker: function( e )
		{
			e.preventDefault();

			var obj			= window.javo_map_wide_func;

			$( this ).toggleClass('active');
			if( $( this ).hasClass('active') )
			{
				// Allow
				obj.map.setOptions({ draggable: true, scrollwheel: true });
				$( this ).find('i').removeClass('fa fa-lock').addClass('fa fa-unlock');
			}else{
				// Not Allowed
				obj.map.setOptions({ draggable:false, scrollwheel: false });
				$( this ).find('i').removeClass('fa fa-unlock').addClass('fa fa-lock');
			}
		}

		, map_clear: function( marker_with )
		{
			//
			var elements = new Array( 'circle', 'rectangle' );

			if( marker_with )
			{
				elements.push( 'marker' );
			}

			this.el.gmap3({ clear:{ name:elements } });
			this.iw_close();
		}

		, iw_close: function()
		{
			if( typeof this.infoWindo != "undefined" )
			{
				this.infoWindo.close();
			}
		}

		, brief_run: function(e)
		{
			var brief_option		= {};
			brief_option.type		= "post";
			brief_option.dataType	= "json";
			brief_option.url		= $( "[javo-ajax-url]" ).val();
			brief_option.data		= { "post_id" : $(e).data('id'), "action" : "javo_map_brief"};
			brief_option.success	= function(db){
				$(".javo_map_breif_modal_content").html(db.html);
				$("#map_breif").modal("show");
				$(e).button('reset');
			};
			$(e).button('loading');
			$.ajax(brief_option);
		}

		, contact_run: function( e )
		{
			var obj				= window.javo_map_wide_func;
			var frm				= obj.contact_form;

			frm.find( '.javo-contact-user-name').html( $(e).data('username') );
			frm.find( 'input[name="contact_item_name"]').val( $(e).data('itemname') );
			frm.find( 'input[name="contact_this_from"]').val( $(e).data('to') );

			$("#author_contact").modal('show');
		}

		, setGetLocationKeyword: function( e )
		{
			var obj		= window.javo_map_wide_func;
			var data	= obj.items;
			var el		= $("input#javo-map-box-location-ac");

			if( e.keyCode == 13 ){

				if( el.val() != "" )
				{

					obj.el.gmap3({
						getlatlng:{
							address: el.val()
							, callback: function( response )
							{
								var sanitize_result;

								if( ! response ) {

									$.javo_msg({ content: $("[javo-bad-location]").val(), delay:1000, close:false });
									return false;
								}

								if( typeof response[0].geometry.bounds != "undefined" )
								{
									var xx = response[0].geometry.bounds.getSouthWest().lat();
									var xy = response[0].geometry.bounds.getNorthEast().lat();
									var yx = response[0].geometry.bounds.getSouthWest().lng();
									var yy = response[0].geometry.bounds.getNorthEast().lng();
									sanitize_result = obj.latlng_calc( xx, xy, yx, yy, data );
								}else{
									$( this ).gmap3('get').setCenter( response[0].geometry.location );
									sanitize_result = data;
								}
								obj.filter( sanitize_result );
							}
						}
					});
				}else{
					obj.filter( data );
				}
				e.preventDefault();
			}
		}

		, latlng_calc: function( s, e, n, w, item ){

			var result = [];

			$.each( item, function( i, k ){

				if(
					( s <= parseFloat( k.lat) && e >= parseFloat(k.lat ) ) &&
					( n <= parseFloat( k.lng) && w >= parseFloat(k.lng ) )
				){
					result.push( item[i] );
				}
			} );
			return result;
		}

		, submit_contact: function( e )
		{
			e.preventDefault();

			var obj				= window.javo_map_wide_func;
			var el				= $( this );
			var frm				= el.closest( 'form' );


			var options_		= {
				subject				: $("input[name='contact_name']")
				, url				: $( "[javo-ajax-url]" ).val()
				, from				: $("input[name='contact_email']")
				, content			: $("textarea[name='contact_content']")
				, to				: frm.find('input[name="contact_this_from"]').val()
				, item_name			: frm.find('input[name="contact_item_name"]').val()
				, to_null_msg		: "<?php echo $mail_alert_msg['to_null_msg'];?>"
				, from_null_msg		: "<?php echo $mail_alert_msg['from_null_msg'];?>"
				, subject_null_msg	: "<?php echo $mail_alert_msg['subject_null_msg'];?>"
				, content_null_msg	: "<?php echo $mail_alert_msg['content_null_msg'];?>"
				, successMsg		: "<?php echo $mail_alert_msg['successMsg'];?>"
				, failMsg			: "<?php echo $mail_alert_msg['failMsg'];?>"
				, confirmMsg		: "<?php echo $mail_alert_msg['confirmMsg'];?>"
			};

			$.javo_mail( options_, function(){
				el.button('loading');
			}, function(){
				$('#author_contact').modal('hide');
				el.button('reset');
			});
		}

		, filter_button: function( e )
		{
			e.preventDefault();
			var obj			= window.javo_map_wide_func;

			$( document )
				.find( "button[data-filter='" + $( this ).data( 'filter' ) + "']" )
				.each( function( index, object ){
					$(this).removeClass( 'active' );
				});
			$( this ).addClass( 'active' );

			obj.filter();
		}

		, filter_select: function( e )
		{
			e.preventDefault();
			var obj			= window.javo_map_wide_func;
			obj.filter();
		}

		, keyword_ : function( e )
		{
			var obj			= window.javo_map_wide_func;
			if( e.keyCode == 13 )
			{
				obj.filter();
			}
		}

		, filter: function( data )
		{
			var obj			= window.javo_map_wide_func;
			var items		= {};

			obj.toggleSwitchButton( false );

			switch( obj.panel_type )
			{
				case "featured": obj.other_filter( obj.panel_type ); break;
				case "favorite":
					if( $( "body" ).hasClass( 'logged-in' ) ) {
						obj.other_filter( obj.panel_type );
					}else{
						var template = $( "#javo-map-wide-panel-favorite-nologin" ).html();
						$('.javo_somw_list_content').html( template );
						obj.toggleSwitchButton( true );
						obj.apply_item = [];
						obj.append_list_item( 0 );
						return false;						
					}
				break;
				case "list": default: items	= data || obj.items;
			}

			items			= obj.apply_filter( $("button[data-filter='item_category'].active" ).data( 'value' ) , items, 'category' );
			items			= obj.apply_filter( $("button[data-filter='item_location'].active" ).data( 'value' ) , items, 'location' );
			items			= obj.apply_filter( $("select[name='filter[item_category]']").val() , items, 'category' );
			items			= obj.apply_filter( $("select[name='filter[item_location]']").val() , items, 'location' );
			items			= obj.apply_keyword( items );

			obj.setMarkers( items );

			$( '.javo_somw_list_content' ).empty();
			obj.apply_item = items;
			obj.append_list_item( 0 );
		}

		, other_filter: function( type )
		{
			var obj			= window.javo_map_wide_func;

			$.post(
				obj.ajaxurl
				, {
					action	: 'javo_map_get_'
					, panel	: type
					, lang	: $( "input[name='javo_cur_lang']" ).val()
				}
				, function( response ){

					$('.javo_somw_list_content').empty();
					obj.apply_item = response;
					obj.append_list_item( 0 );
					obj.setMarkers( response );
				}
				, "json"
			)
			.fail( function( response ){
				console.log( response.responseText );
				$.javo_msg({ content: "Server Error", delay:1000 });
			});
		}

		, keywordMatchesCallback: function( tags )
		{
			return function keywordFindMatches( q, cb )
			{
				var matches, substrRegex;

				substrRegex		= new RegExp( q, 'i');
				matches			= [];

				$.each( tags, function( i, tag ){
					if( substrRegex.test( tag ) ){
						matches.push({ value : tag });
					}
				});
				cb( matches );
			}
		}

		, setKeywordAutoComplete: function()
		{
			this.el_keyword = $( 'input#javo_keyword' );

			this.el_keyword.typeahead({
				hint			: false
				, highlight		: true
				, minLength		: 1
			}, {
				name			: 'tags'
				, displayKey	: 'value'
				, source		: this.keywordMatchesCallback( this.tags )
			}).closest('span').css({ width: '100%' });
		}

		, apply_filter: function( cur_id, data, term )
		{
			var result = {};

			if( cur_id != "" && typeof cur_id != "undefined" )
			{
				$.each( data , function( i, k ){
					var term_id = term == 'category' ? k.cat_term : k.loc_term;
					if(  term_id.indexOf( cur_id.toString() ) > -1 )
					{
						result[i] = k;
					}
				});
			}else{
				result = data;
			}
			return result;
		}

		, apply_keyword: function( data )
		{
			var obj = window.javo_map_wide_func;
			var keyword		= $("input#javo_keyword").val();
			var result		= {}
			var _f			= true;

			if( typeof keyword != "undefined" )
			{
				if( keyword != "" )
				{
					keyword = keyword.toLowerCase();
					$.each( data , function( i, k ){
						if(
							obj.tag_matche( k.tags, keyword ) ||
							k.post_title.toLowerCase().indexOf( keyword ) > -1
						){
							result[i] = k;
						}
					});
				}else{
					_f = false;
				}
			}else{
				_f = false;
			}

			if( !_f){
				result = data;
			}
			return result;
		}

		, tag_matche: function( str, keyword )
		{
			var i = 0;
			if( str != "" )
			{
				for( i in str )
				{
					// In Tags ?
					if( str[i].match( keyword ) )
					{
						return true;
					}
				}
			}
			return false;
		}

		, setMarkers: function( response )
		{
			var item_markers	= new Array();
			var obj				= window.javo_map_wide_func;

			obj.map_clear( true );

			$.each( response, function( i, item ){

				if( item.lat != "" && item.lng != "" )
				{
					item_markers.push( {
						//latLng		: new google.maps.LatLng( item.lat, item.lng )
						lat			: item.lat
						, lng		: item.lng
						, options	: { icon: item.icon }
						, id		: "mid_" + item.post_id
						, data		: item
					} );
				}
			});

			if( item_markers.length > 0 )
			{
				var _opt = {
					marker:{
						values:item_markers
						, events:{
							click: function( m, e, c ){

								var map = $(this).gmap3( 'get' );
								obj.infoWindo.setContent( $( "#javo-map-loading-template" ).html() );
								obj.infoWindo.open( map, m);
								map.setCenter( m.getPosition() );

								$.post(
									obj.ajaxurl
									, {
										action		: "javo_map_infoW"
										, post_id	: c.data.post_id
									}
									, function( response )
									{
										var str = '', nstr = '';

										if( response.state == "success" )
										{
											str = $('#javo-map-wide-infobx-content').html();
											str = str.replace( /{post_id}/g		, response.post_id );
											str = str.replace( /{post_title}/g	, response.post_title );
											str = str.replace( /{permalink}/g	, response.permalink );
											str = str.replace( /{thumbnail}/g	, response.thumbnail );
											str = str.replace( /{category}/g	, response.category );
											str = str.replace( /{location}/g	, response.location );
											str = str.replace( /{phone}/g		, response.phone || nstr );
											str = str.replace( /{mobile}/g		, response.mobile || nstr );
											str = str.replace( /{website}/g		, response.website || nstr );
											str = str.replace( /{email}/g		, response.email || nstr );
											str = str.replace( /{address}/g		, response.address || nstr );
											str = str.replace( /{author_name}/g	, response.author_name || nstr );

										}else{
											str = "error";
										}

										$( "#javo-map-info-w-content" ).html( str );

									}
									, "json"
								)
								.fail( function( response )
								{
									$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000 });
									console.log( response.responseText );

								} );
							} // End Click
						} // End Event
					} // End Marker
				} // End _opt

				if( $("[javo-cluster-onoff]").val() != "disable" )
				{
					_opt.marker.cluster = {
						radius: parseInt( $("[javo-cluster-level]").val() ) || 100
						, 0:{ content:'<div class="javo-map-cluster admin-color-setting">CLUSTER_COUNT</div>', width:52, height:52 }
						, events:{
							click: function( c, e, d )
							{
								var $map = $(this).gmap3('get');
								var maxZoom = new google.maps.MaxZoomService();
								var c_bound = new google.maps.LatLngBounds();

								// IF Cluster Max Zoom ?
								maxZoom.getMaxZoomAtLatLng( d.data.latLng , function( response ){
									if( response.zoom <= $map.getZoom() && d.data.markers.length > 0 )
									{
										var str = '';

										str += "<ul class='list-group'>";

										str += "<li class='list-group-item disabled text-center'>";
											str += "<strong>";
												str += $("[javo-cluster-multiple]").val();
											str += "</strong>";
										str += "</li>";

										$.each( d.data.markers, function( i, k ){
											str += "<a onclick=\"window.javo_map_wide_func.marker_trigger('" + k.id +"');\" ";
												str += "class='list-group-item'>";
												str += "Post " + k.data.post_title;
											str += "</a>";
										});

										str += "</ul>";
										obj.infoWindo.setContent( str );
										obj.infoWindo.setPosition( c.main.getPosition() );
										obj.infoWindo.open( $map );

									}else{
										$map.setCenter( c.main.getPosition() );
										$map.setZoom( $map.getZoom() + 2 );
									}
								} );// End Get Max Zoom
							} // End Click
						} // End Event
					} // End Cluster
				} // End IF
				this.el.gmap3( _opt, "autofit");
			}else{
				if( obj.panel_type == "list" )
				{
					$.javo_msg({ content: $("[javo-map-item-not-found]").val(), delay: 1000, close:false });
				}
			}
		}

		, load_more: function( e )
		{
			e.preventDefault();

			var obj			= window.javo_map_wide_func;
			obj.append_list_item( obj.loaded_ );
		}

		, append_list_item: function( offset )
		{
			var obj			= window.javo_map_wide_func;
			var btn			= $( '[data-javo-map-load-more]' );
			var limit		= 9;
			var data		=  obj.apply_item;
			var jv_integer	= 0;
			this.loaded_	= limit + offset;
			var ids			= new Array();

			$.each( data, function( i, k ){
				jv_integer++;

				if( jv_integer > obj.loaded_ ){ return false; }
				if( jv_integer > offset ){
					ids.push( k.post_id );
				}
			});

			btn.prop( 'disabled', true ).find('i').addClass('fa-spin');

			$.post(
				obj.ajaxurl
				, {
					action		: "javo_map_list"
					, post_ids	: ids
				}
				, function( response )
				{
					var buf			= "";

					if( response.length > 0 )
					{
						$.each( response, function( index, data ){
							var str = "";
							str = $('#javo-map-wide-panel-content').html();
							str = str.replace(/{post_id}/g			, data.post_id);
							str = str.replace(/{post_title}/g		, data.post_title || '');
							str = str.replace(/{thumbnail_url}/g	, data.thumbnail_url || '');
							str = str.replace(/{category}/g			, data.category || '');
							str = str.replace(/{location}/g			, data.location || '');
							buf += str;
						});

						$('.javo_somw_list_content').append( buf );

						// Apply Rating
						$('.javo-rating-registed-score').each(function(k,v){
							$(this).raty({
								starOff		: '<?php echo JAVO_IMG_DIR?>/star-off-s.png'
								, starOn	: '<?php echo JAVO_IMG_DIR?>/star-on-s.png'
								, starHalf	: '<?php echo JAVO_IMG_DIR?>/star-half-s.png'
								, half		: true
								, readOnly	: true
								, score		: $(this).data('score')
							}).css('width', '');
						});
					}else{
						if( obj.panel_type == "list" )
						{
							$('.javo_somw_list_content').append( $("[javo-map-item-not-found]").val() );
						}
					}
				}
				, "json"
			)
			.always( function( xhr )
			{				
				btn.prop( 'disabled', false ).find('i').removeClass('fa-spin');
				obj.toggleSwitchButton( true );
			})
			.fail( function( response )
			{
				$.javo_msg({ content: $( "[javo-server-error]" ).val(), delay: 10000 });
				console.log( response.responseText );

			} ); // Fail
		}

		, trigger_marker: function( e )
		{
			var obj = window.javo_map_wide_func;

			obj.el.gmap3({
					map:{ options:{ zoom: parseInt( $("[javo-marker-trigger-zoom]").val() ) } }
				},{
				get:{
					name:"marker"
					,		id: $( this ).data('id')
					, callback: function(m){
						google.maps.event.trigger(m, 'click');
					}
				}
			});
		}

		, marker_trigger: function( mid )
		{
			var obj = window.javo_map_wide_func;

			obj.el.gmap3({
					map:{ options:{ zoom: parseInt( $("[javo-marker-trigger-zoom]").val() ) } }
				},{
				get:{
					name:"marker"
					,		id: mid
					, callback: function(m){
						google.maps.event.trigger(m, 'click');
					}
				}
			});

		}

		, getGeoLocation: function( e )
		{
			e.preventDefault();

			var obj = window.javo_map_wide_func;
			var $radius = $('[javo-wide-map-round]').val();

			obj.el.gmap3({
				getgeoloc:{
					callback : function(latLng){
						if( !latLng ) return false;
						$(this).gmap3({ clear:'circle' });
						$(this).gmap3({
							map:{
								options:{ center:latLng, zoom:12 }
							}, circle:{
								options:{
									center:latLng
									, radius:$radius * parseFloat( obj.distance )
									, fillColor:'#464646'
									, strockColor:'#000000'
								}
							}
						});
						$(this).gmap3({
							get:{
								name: 'circle'
								, callback: function(c){

									$(this).gmap3('get').fitBounds( c.getBounds() );
								}
							}
						});
					}
				}
			});

		}
	}
	window.javo_map_wide_func.init();

});
</script>
<?php

if($javo_tso_map->get('map_wide_visible_footer', null) == 'hidden'){
	get_footer('no-widget');
}else{
	get_footer();
}