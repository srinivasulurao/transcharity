<?php
global
	$javo_custom_field
	, $jv_str
	, $javo_tso
	, $javo_tso_map
	, $javo_this_single_page_type
	, $javo_animation_fixed
	, $javo_video_query
	, $javo_map_strings;

$javo_animation_fixed = $javo_this_single_page_type == 'item-tab'? 'loaded': '';
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
     $large_image_url				= wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
}

// Enqueues
{
	add_action( 'wp_enqueue_scripts', 'javo_single_item_enq' );
	function javo_single_item_enq()
	{
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'Google-Map-Info-Bubble' );
		wp_enqueue_script( 'okVideo-Plugin' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jQuery-flex-Slider' );
		wp_enqueue_script( 'jquery-magnific-popup' );
		wp_enqueue_script( 'jQuery-Rating' );
	}
}
get_header();
if(have_posts()): the_post();
	$post_id = get_the_ID();
	$javo_sidebar_option			= get_post_meta($post_id, "javo_sidebar_type", true);
	$javo_this_author				= get_userdata($post->post_author);
	$javo_this_author_avatar_id		= get_the_author_meta('avatar');
	$javo_this_featured_image_id	= get_post_thumbnail_id($post_id);
	$javo_this_featured_image_meta	= wp_get_attachment_image_src($javo_this_featured_image_id, 'thumbnail');
	$javo_this_featured_image_src	= $javo_this_featured_image_meta[0];
	$javo_video_query				= new javo_ARRAY( (ARRAY)get_post_meta($post->ID, 'video', true) );
	$javo_video_allow				= false;
	if(
		$javo_video_query->get('single_position', '') == 'header' &&
		$javo_video_query->get('video_id', '') != '' &&
		(
			$javo_video_query->get('portal', '') == 'youtube' ||
			$javo_video_query->get('portal', '') == 'vimeo'
		)
	){
		$javo_video_allow			= true;
	};

	$javo_latLng	= Array(
		'lat'				=> get_post_meta( get_the_ID(), 'jv_item_lat', true )
		, 'lng'				=> get_post_meta( get_the_ID(), 'jv_item_lng', true )
		, 'street_lat'		=> get_post_meta( get_the_ID(), 'jv_item_street_lat', true )
		, 'street_lng'		=> get_post_meta( get_the_ID(), 'jv_item_street_lng', true )
		, 'street_heading'	=> get_post_meta( get_the_ID(), 'jv_item_street_heading', true )
		, 'street_pitch'	=> get_post_meta( get_the_ID(), 'jv_item_street_pitch', true )
		, 'street_zoom'		=> get_post_meta( get_the_ID(), 'jv_item_street_zoom', true )
	);

	$javo_this_latlng = $javo_latLng;
	$javo_latlng_meta = new javo_ARRAY( $javo_this_latlng );

	$javo_header_buttons = Array();

	/* Header Buttons */{
		if( $javo_tso->get('single_top_map_primary') != 'disabled' )
		{
			// Map & Thumbnail Toggle
			$javo_header_buttons['data-javo-single-map'] = Array(
				'container_class'		=> 'javo-single-itemp-tab-intro-switch'
				, 'viewport'			=> '.javo-single-item-tab-map-area'
				, 'before_image'		=> $javo_this_featured_image_src
				, 'after_image'			=> JAVO_THEME_DIR.'/assets/images/icon/icon-location-red.png'
			);
		}
		if( $javo_tso->get('single_top_map_street') != 'disabled' )
		{
			// StreetView On/Off Toggle
			$javo_header_buttons['data-javo-single-streetview'] = Array(
				'container_class'		=> 'hidden'
				, 'viewport'			=> '.javo-single-item-tab-map-street-area'
				, 'before_image'		=> JAVO_THEME_DIR.'/assets/images/icon/icon-streetview.png'
				, 'after_image'			=> JAVO_THEME_DIR.'/assets/images/icon/icon-streetview-off.png'
			);
		}
		if( false != get_post_meta( get_the_ID(), 'header_custom_frame', true ) )
		{
			// Custom Frame On/Off Toggle
			$javo_header_buttons['data-javo-single-customFrame'] = Array(
				'container_class'		=> ''
				, 'viewport'			=> '.javo-single-item-tab-custom-item'
				, 'before_image'		=> JAVO_THEME_DIR.'/assets/images/icon/3D-view-icon-on.png'
				, 'after_image'			=> JAVO_THEME_DIR.'/assets/images/icon/3D-view-icon-off.png'
			);
		}
		if( $javo_video_allow )
		{
			// Viedo On/Off Toggle
			$javo_header_buttons['data-javo-single-videoview'] = Array(
				'container_class'		=> ''
				, 'viewport'			=> '.javo-single-item-tab-video-area'
				, 'before_image'		=> JAVO_THEME_DIR.'/assets/images/icon/icon-btn-play.png'
				, 'after_image'			=> JAVO_THEME_DIR.'/assets/images/icon/icon-btn-play-off.png'
			);
		}

	}


	// Relative Item
	{
		$javo_relative_items	= Array();
		if( false !== (boolean)( $javo_this_terms = wp_get_post_terms( get_the_ID() , 'item_category', Array( 'fields' => 'ids' ) ) ) )
		{
			$javo_relative_posts_args = Array(
				'post_type'			=> 'item'
				, 'post_status'		=> 'publish'
				, 'posts_per_page'	=> 30
				, 'exclude'			=> Array( get_the_ID() )
				, 'tax_query'		=> Array(
					Array(
						'taxonomy'	=> 'item_category'
						, 'field'	=> 'term_id'
						, 'terms'	=> $javo_this_terms[0]
					)
				)
			);
			$javo_relative_posts	= get_posts( $javo_relative_posts_args );

			foreach( $javo_relative_posts as $post )
			{
				setup_postdata( $post );

				/* Get Location Terms */
				{
					$javo_this_location = $jv_str[ 'no_location' ];
					if( false !== (boolean)( $javo_this_locations = wp_get_post_terms( $post->ID , 'item_location', Array( 'fields' => 'names' ) ) ) )
					{
						$javo_this_location = $javo_this_locations[0];
					}
				}

				/* Marker Icon */
				{
					if(	'' === ( $javo_set_icon = get_option( "javo_item_category_{$javo_this_terms[0]}_marker", '') ) ){
						$javo_set_icon				= $javo_tso->get('map_marker', '');
					}
				}

				/* Post Thumbnail */
				{
					$javo_this_thumb				= '';
					if( '' !== ( $javo_this_thumb_id = get_post_thumbnail_id( $post->ID ) ) )
					{
						$javo_this_thumb_url = wp_get_attachment_image_src( $javo_this_thumb_id , 'javo-map-thumbnail' );

						if( isset( $javo_this_thumb_url ) )
						{
							$javo_this_thumb = $javo_this_thumb_url[0];
						}
					}

					// If not found this post a thaumbnail
					if( empty( $javo_this_thumb ) )
					{
						$javo_this_thumb		= $javo_tso->get( 'no_image', JAVO_IMG_DIR.'/no-image.png' );

					}
				}


				$javo_relative_items[] = Array(
					'lat'			=> get_post_meta( $post->ID, 'jv_item_lat', true )
					, 'lng'			=> get_post_meta( $post->ID, 'jv_item_lng', true )
					, 'icon'		=> $javo_set_icon
					, 'thumbnail'	=> $javo_this_thumb
					, 'post_title'	=> wp_trim_words( $post->post_title, 30)
					, 'permalink'	=> apply_filters( 'javo_wpml_link', $post->ID )
					, 'address'		=> javo_str_cut( get_post_meta( $post->ID, 'jv_item_address', true ), 25 )
					, 'mobile'		=> javo_str_cut( get_post_meta( $post->ID, 'jv_item_mobile', true ), 25 )
					, 'website'		=> javo_str_cut( get_post_meta( $post->ID, 'jv_item_website', true ), 25 )
					, 'email'		=> javo_str_cut( get_post_meta( $post->ID, 'jv_item_email', true ), 25 )
					, 'category'	=> javo_str_cut( get_term( $javo_this_terms[0], 'item_category' )->name, 25 )
					, 'location'	=> $javo_this_location
				);
			}	// End Foreach
			wp_reset_postdata();
		}	// End If
	}
?>
<fieldset>
	<input type="hidden" value="<?php echo $javo_tso->get('javo_single_map_style');?>" data-javo-map-none-style>
	<input type="hidden" value="<?php echo $javo_tso->get('single_map_marker', null);?>" data-javo-map-single-marker>
	<input type="hidden" value="<?php echo $javo_map_strings->get('single_cannot_search_address', __('Not found this address for direction', 'javo_fr'));?>" name="javo_cannot_search_address">
	<input type="hidden" value="<?php echo $javo_map_strings->get('single_cannot_search_direction', __('The direction is too far or not provided by Google API', 'javo_fr'));?>" name="javo_cannot_search_direction">
	<input type="hidden" value="<?php echo (int) $javo_tso->get('javo_detail_item_map_max_bound', 0);?>" data-javo-map-bound-max-level>
	<input type="hidden" name="javo_google_map_poi" value="<?php echo $javo_tso_map->get('poi', 'on');?>">
	<input type="hidden" name="javo_location_click_on_action" value="<?php echo $javo_tso->get( 'tab_location_click_trigger', '');?>">
	<input type="hidden" name="javo-this-term-posts-latlng" value="<?php echo htmlspecialchars( json_encode( $javo_relative_items ) ); ?>">

	<!-- Street View -->
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_visible', 1);?>" data-street-visible>
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_lat', null);?>" data-street-lat>
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_lng', null);?>" data-street-lng>
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_heading', 0);?>" data-street-pov-heading>
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_pitch', 0);?>" data-street-pov-pitch>
	<input type="hidden" value="<?php echo $javo_latlng_meta->get('street_zoom', 0.5);?>" data-street-pov-zoom>
</fieldset>



<div class="single-item-tab">
	<?php if($javo_tso->get('top_featured_and_map')!='disabled'){ ?>
	<div class="single-item-tab-feature-bg" style="background:url('<?php echo  $large_image_url[0]; ?>') no-repeat center center fixed;  -webkit-background-size: cover;  -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-attachment: fixed; min-height:<?php if($javo_tso->get('topmap_height')) echo $javo_tso->get('topmap_height'); ?>px;">
		<div class="javo-single-item-tab-map-area hidden" data-javo-single-item-header-panel></div>
		<div class="javo-single-item-tab-map-street-area hidden" data-javo-single-item-header-panel></div>

		<div class="javo-single-item-tab-custom-item hidden" data-javo-single-item-header-panel><?php echo get_post_meta( get_the_ID(), 'header_custom_frame', true ); ?></div>
		<?php if($javo_video_allow): ?>
			<div class="javo-single-item-tab-video-area hidden" style="overflow:hidden;" data-javo-single-item-header-panel data-javo-video-id="<?php echo $javo_video_query->get('video_id', '');?>"></div>
		<?php endif;?>
		<div class="single-item-tab-bg">
			<div class="container captions">
				<div class="header-inner">
					<div class="item-bg-left pull-left text-left">
						<h1 class="uppercase"><?php the_title();?></h1>
					</div>
					<?php if( !empty( $javo_header_buttons ) ) : ?>
						<div class="item-bg-right pull-right text-center">
							<?php
							foreach( $javo_header_buttons as $id => $button )
							{
								$str = "\n<div class=\"author-info {$button['container_class']}\" ";
								$str .= "data-javo-swap-button data-javo-swap-butotn-tar=\"{$button['viewport']}\" ";
								$str .= "data-original=\"{$button['before_image']}\" ";
								$str .= "data-after=\"{$button['after_image']}\" {$id}>\n";
									$str .= "\t<img src=\"{$button['after_image']}\" class=\"img-circle\" style=\"cursor:pointer;\">";
								$str .= "\n</div>";
								echo $str;
							} ?>
						</div>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div> <!-- header-inner -->
			</div> <!-- container -->
		</div> <!-- single-item-tab-bg -->
		<div class="bg-dot-black"></div> <!-- bg-dot-black -->
	</div> <!-- single-item-tab-feature-bg -->
	
	<?php }else{ ?>
	<style>
	.single-item header{position:relative !important;}
	</style>
	<?php } ?>
	<script type="text/html" id="javo-detail-item-header-map-info-template">
		<div class="javo_somw_info panel">
			<div class="des">
				<h5>
					<a href="{permalink}">{post_title}</a>
				</h5>
				<ul class="list-unstyled">
					<li>{address}</li>
					<li>{phone}</li>
					<li>{website}</li>
					<li>{email}</li>
				</ul>
			</div> <!-- des -->

			<div class="pics">
				<div class="thumb">
					<a href="{permalink}">
						<div class="javo-thb" style="background-image:url({thumbnail});"></div>
					</a>
				</div> <!-- thumb -->
				<div class="img-in-text">{category}</div>
				<div class="javo-left-overlay">
					<div class="javo-txt-meta-area">{location}</div>
					<div class="corner-wrap">
						<div class="corner"></div>
						<div class="corner-background"></div>
					</div> <!-- corner-wrap -->
				</div> <!-- javo-left-overlay -->
			</div> <!-- pic -->
		</div> <!-- javo_somw_info -->
	</script>

	<div class="container">
		<?php
		switch($javo_sidebar_option){
		case "left":
			?>
			<div class="row">
				<?php get_sidebar();?>
				<div class="col-md-9 pp-single-content">
					<?php get_template_part('templates/parts/single', 'item-tab-inner'); ?>
				</div>
			</div>
			<?php
		break;
		case "full":
			?>
			<div class="row">
				<div class="col-md-12">
					<?php get_template_part('templates/parts/single', 'item-tab-inner'); ?>
				</div>
			</div>
			<?php
		break;
		case "right":
		default:
			?>
			<div class="row">
				<div class="col-md-9 pp-single-content">
					<?php get_template_part('templates/parts/single', 'item-tab-inner'); ?>
				</div>
				<?php get_sidebar();?>
			</div>
			<?php
		};?>
	</div> <!-- container -->
</div> <!-- single-item-tab -->
<?php endif; ?>

<script type="text/javascript">
jQuery(function($){
	"use strict";

	// stm : Single Template Map
	var javo_stm = {
		latLng: new google.maps.LatLng("<?php echo $javo_latlng_meta->get('lat', 0);?>", "<?php echo $javo_latlng_meta->get('lng', 0);?>")
		, post_id: "<?php the_ID();?>"
		, map_style: null
		, infoBubble: null
		, options:{
			config:{
				use_map_style: $('[data-javo-map-none-style]').val()
				, boundMaxLevel: $('[data-javo-map-bound-max-level]').val()
				, poi: $('[name="javo_google_map_poi"]').val()
				, location:{
					map_height: 500
				}
				, header:{
					map_height: $('.single-item-tab-feature-bg').outerHeight()
				}
			}
			, map_container:{
				header:				$('.javo-single-item-tab-map-area')
				, location:			$('.javo-single-map-area')
			}
			, map_init:{
				map:{
					options:{
						zoom:15,
						mapTypeIds: [ google.maps.MapTypeId.ROADMAP, 'map_style' ],
						mapTypeControl: false,
						navigationControl: true,
						scrollwheel: false,
						streetViewControl: false
					}
				}
				, marker:{options:{}, events:{}}
			}
			, streetview:{
				streetviewpanorama:{
					options:{
						container: null
						, opts:{
							position: null ,pov: {}
						}
					}
				}
			}
			, info:{
				minWidth:362
				, minHeight:180
				, height: 180
				, overflow:true
				, shadowStyle: 1
				, padding: 5
				, borderRadius: 10
				, arrowSize: 20
				, borderWidth: 1
				, disableAutoPan: false
				, hideCloseButton: false
				, arrowPosition: 50
				, arrowStyle: 0
			}
			, map_color:[
				{
					stylers: [
						{ hue: "<?php echo $javo_tso->get('total_button_color', '#f00');?>" }
						, { saturation: -20 }
					]
				}, {
					featureType: "road",
					elementType: "geometry",
					stylers: [
						{ lightness: 100 },
						{ visibility: "simplified" }
					]
				},{
					featureType: "road",
					elementType: "labels",
					stylers: [
						{ visibility: "off" }
					]
				}
			] // End Map Color
			, poi_off:[
				{
					featureType: "poi",
					elementType: "labels",
					stylers: [
							{ visibility: "off" }
					]
				}
			]// End POI OFF
		}

		, init:function()
		{
			var pano = new google.maps.StreetViewService;

			var map_add_options = new Array();

			this.pano_latlng = this.latLng;

			if( $('[data-street-lat]').val() != "" && $('[data-street-lat]').val() != "" ){
				this.pano_latlng = new google.maps.LatLng( $('[data-street-lat]').val(), $('[data-street-lng]').val() );
			}

			// param1: Position, param2: Round, param3: callback
			pano.getPanoramaByLocation( this.pano_latlng, 50, function(e){

				if( e != null ){
					$('[data-javo-single-streetview]').removeClass('hidden');
				}
			});

			if( this.options.config.use_map_style != 'default' )
			{
				map_add_options = this.options.map_color;
			}

			if( this.options.config.poi == 'off')
			{
				map_add_options.push( this.options.poi_off[0] );
			}

			this.map_style = new google.maps.StyledMapType( map_add_options, {name:'Javo Single Item Map'});
			this.infoBubble = new InfoBubble( this.options.info );

			this.location.init();
			this.header.init();
		}
		/**
		***		Single Item Tab
		***		Content Location MAP
		***
		**/
		, location:{
			map			: null
			, el		: null
			, init		: function(){

				var elements = $( "#item-location.tab-pane" );
				if( ! elements.length ) {
					// Block Location Script
					return false;
				}

				this.el		= javo_stm.options.map_container.location;

				javo_stm.options.map_init.map.options.center	= javo_stm.latLng;
				javo_stm.options.map_init.marker.latLng			= javo_stm.latLng;
				javo_stm.options.map_init.marker.options		= { icon:$('[data-javo-map-single-marker]').val() }
				this.el
					.css('minHeight', javo_stm.options.config.location.map_height)
					.gmap3( javo_stm.options.map_init );
				this.map	= this.el.gmap3('get');

				this.map.setOptions({
					zoomControlOptions: {
						position: google.maps.ControlPosition.LEFT_CENTER
						, style: google.maps.ZoomControlStyle.BIG
					}
					, panControlOptions: {
						position: google.maps.ControlPosition.LEFT_CENTER
					}
				});

				this.map.mapTypes.set('single_location_style', javo_stm.map_style);
				this.map.setMapTypeId('single_location_style')

				this.events();
				this.autoCompleted();
			}
			, events:function(){
				var $this = this;
				$(document)
					.on('click', 'a[href="#item-location"]', function(){
						var _cur_location_tab_action = $('[name="javo_location_click_on_action"]').val();

						$this.resize();
						$this.map.setCenter( javo_stm.latLng );

						switch( _cur_location_tab_action )
						{
							case 'map': $('.javo-single-itemp-tab-intro-switch').trigger('click'); break;
							case 'streetview': $('[data-javo-single-streetview]').trigger('click'); break;

						}
					}).on('keypress', '[data-javo-direction-start-text]', function(e){
						if( e.keyCode == 13 ){
							$('[data-javo-direction-start]').trigger('click');
						}
					}).on('click', '[data-javo-direction-start]', function(){
						var $address = $(this).closest('.input-group').find('input[type="text"]');
						var $travle;
						switch( $('[data-javo-direction-travel]').val() ){
							case 'bicycling'	: $travle = google.maps.DirectionsTravelMode.BICYCLING; break;
							case 'transit'		: $travle = google.maps.DirectionsTravelMode.TRANSIT; break;
							case 'walking'		: $travle = google.maps.DirectionsTravelMode.WALKING; break;
							case 'driving'		:
							default				: $travle = google.maps.DirectionsTravelMode.DRIVING;
						}
						$this.el.gmap3({
							getlatlng:{
								address			: $address.val()
								, callback		: function(results){
									if ( !results ){
										// Todo : Not Found Address Position code here.
										$.javo_msg({content:$('[name="javo_cannot_search_address"]').val(), delary:5000});
										return;
									};

									$(".javo-route-detail").empty();

									$(this).gmap3({
										getroute:{
											options:{
											origin:results[0].geometry.location
											, destination: javo_stm.latLng
											, travelMode: $travle
											},
											callback: function(results){
												if (!results){
													$.javo_msg({content:$('[name="javo_cannot_search_direction"]').val(), delary:5000});
													return;
												};

												$(this).gmap3({ clear:{ name:[ 'marker', 'directionsrenderer' ] } });
												$(this).gmap3({
													map:{
														options:{
															zoom: 13,
															center: [-33.879, 151.235]
														}
													},
													directionsrenderer:{
														container: $(document.createElement('div')).addClass('javo-route-detail').insertAfter( $('.get-direction') )
														, options:{
															directions:results
														}
													}
												});
											}
										}
									}); // Direction Close
								}
							}
						});	// Address Search Close
					});
			}
			, autoCompleted: function(){
				// Set Object
				var $object = javo_stm.location;
				new google.maps.places.Autocomplete( $('[data-javo-direction-start-text]').get(0) );
			}
			, resize:function(){
				javo_stm.options.map_container.location.gmap3({ trigger:'resize' });
			}
		}
		/**
		***		Single Item Tab
		***		Header MAP
		***
		**/
		, header:{
			map			: null
			, markers	: JSON.parse( $('input[name="javo-this-term-posts-latlng"]').val() )
			, el		: null
			, st_el		: $('.javo-single-item-tab-map-street-area')
			, vi_el		: $('.javo-single-item-tab-video-area')
			, cf_el		: $('.javo-single-item-tab-custom-item')
			, st_init	: null
			, init		: function()
			{				
				var marker_values = new Array();
				var origin_map_init = javo_stm.options.map_init;

				$.each(this.markers, function(i, k){
					if( k.lat == "" || k.lng == "" ){ return; };
					marker_values
						.push({
							data: k
							, latLng:[ k.lat, k.lng]
							, options:{ icon: k.icon }
						});
					console.log( k.icon );
				});

				marker_values.push({
					id			: '#javo_map_tmp_' + javo_stm.post_id
					, latLng	: javo_stm.latLng
					, options	: { 
						zIndex	: 9999
						, icon	: $('[data-javo-map-single-marker]').val()
					}
				});

				javo_stm.options.map_init.map.options.center		= javo_stm.latLng;
				javo_stm.options.map_init.map.events				= {};
				javo_stm.options.map_init.map.events.bounds_changed	= function(map){
					var bml = parseInt( javo_stm.options.config.boundMaxLevel );
					if( bml <= 0 ){ return false; }
					if( map.getZoom() > bml ){ map.setZoom( bml ); };
				};
				javo_stm.options.map_init.marker.cluster			= {
					radius:100
					, 0:{ content:'<div class="javo-map-cluster admin-color-setting">CLUSTER_COUNT</div>', width:52, height:52 }
					, events:{
						click:function(c, e, d){
							c.main.map.setZoom( c.main.map.getZoom() + 2 );
							c.main.map.panTo( d.data.latLng );

						}
					}
				};
				javo_stm.options.map_init.marker.values			= marker_values;
				javo_stm.options.map_init.marker.events.click	= function(m, e, c){

					var $map		= $( this ).gmap3( 'get' );
					var str			= $( "#javo-detail-item-header-map-info-template" ).html();

					str	= str.replace( /{post_title}/		, c.data.post_title || '' );
					str	= str.replace( /{address}/			, c.data.address || '' );
					str	= str.replace( /{phone}/			, c.data.phone || '' );
					str	= str.replace( /{website}/			, c.data.website || '' );
					str	= str.replace( /{email}/			, c.data.email || '' );
					str	= str.replace( /{thumbnail}/		, c.data.thumbnail || '' );
					str	= str.replace( /{permalink}/		, c.data.permalink || '' );
					str	= str.replace( /{category}/			, c.data.category || '' );
					str	= str.replace( /{location}/			, c.data.location || '' );
					

					javo_stm.infoBubble.setContent( str );
					javo_stm.infoBubble.open($map, m);
					$this_map.setCenter( m.getPosition() );

				}

				javo_stm.options.streetview.streetviewpanorama.options.container = $('.javo-single-item-tab-map-street-area');
				javo_stm.options.streetview.streetviewpanorama.options.opts.position	=  javo_stm.pano_latlng;
				javo_stm.options.streetview.streetviewpanorama.options.opts.pov			= {
					heading		: parseFloat( $('[data-street-pov-heading]').val() )
					, pitch		: parseFloat( $('[data-street-pov-pitch]').val() )
					, zoom		: parseFloat( $('[data-street-pov-zoom]').val() )
				};

				var header_init = $.extend( true, {}, javo_stm.options.map_init, javo_stm.options.streetview);

				header_init.map.options.panControlOptions = {
					position: google.maps.ControlPosition.LEFT_CENTER
				}

				header_init.map.options.zoomControlOptions = {
					position: google.maps.ControlPosition.LEFT_CENTER
					, style: google.maps.ZoomControlStyle.BIG
				}

				javo_stm.options.map_container.header.gmap3( header_init );

				this.el		= javo_stm.options.map_container.header;

				if( this.el.length <= 0 ){ return false; }

				this.map	= this.el.gmap3('get');

				this.map.mapTypes.set('single_header_style', javo_stm.map_style);
				this.map.setMapTypeId('single_header_style');

				this.events();

				if( this.vi_el.data('javo-video-id') != ""){
					this.vi_el
						.okvideo({
							source:			this.vi_el.data('javo-video-id')
							, loop:			true
							, hd:			true
							, adproof:		true
							, annotations:	false
							, volume:		70
							, autoplay : false
						});
				};

			}
			, panel_close: function(){
				$('[data-javo-single-item-header-panel]').each(function(){
					$(this)
						.animate({ left: -( $(window).width() ) + 'px' }, 500)
						.removeClass('active');
					$( '#header-one-line' ).children( '.navbar' ).css( 'background-color', '');

				});
			}
			, events: function(){
				var $this		= this;
				$(document)
					.on('click', '.javo-single-itemp-tab-intro-switch', function(){
						var _this = $(this);

						if(
							$this.vi_el.hasClass('active') ||
							$this.st_el.hasClass('active') ||
							$this.cf_el.hasClass('active')
						){
							$this.panel_close();
						};

						if( $this.el.hasClass('active') ){
							$this.panel_close();

						}else{
							$this.el
								.clearQueue()
								.animate({ left: 0 + 'px'}, 500)
								.addClass('active');
								$this.el.gmap3({trigger:'resize'});
							$('#header-one-line').children('.navbar').css( 'background-color', 'rgba(45, 45, 45, .2)');
						};
					}).on('click', '[data-javo-single-videoview]', function(){
						var _this = $(this);

						if(
							$this.el.hasClass('active') ||
							$this.st_el.hasClass('active') ||
							$this.cf_el.hasClass('active')

						){
							$this.panel_close();
						};

						if( $this.vi_el.hasClass('active') ){
							$this.panel_close();
						}else{
							$this.vi_el
								.clearQueue()
								.animate({ left: 0 + 'px'}, 500)
								.addClass('active');
							$('#header-one-line').children('.navbar').css( 'background-color', 'rgba(45, 45, 45, .2)');
						};
					}).on('click', '[data-javo-single-streetview]', function(){
						var _this = $(this);

						if(
							$this.el.hasClass('active') ||
							$this.vi_el.hasClass('active') ||
							$this.cf_el.hasClass('active')
						){
							$this.panel_close();
						}

						if( $this.st_el.hasClass('active') ){
							$this.panel_close();
						}else{
							$this.st_el
								.clearQueue()
								.animate({ left: 0 + 'px'}, 500)
								.addClass('active');
							$('#header-one-line').children('.navbar').css( 'background-color', 'rgba(45, 45, 45, .2)');
						}

					}).on('click', '[data-javo-single-customFrame]', function(){
						var _this = $(this);

						if(
							$this.el.hasClass('active') ||
							$this.vi_el.hasClass('active') ||
							$this.st_el.hasClass('active')
						){
							$this.panel_close();
						}

						if( $this.cf_el.hasClass('active') ){
							$this.panel_close();
						}else{
							$this.cf_el
								.clearQueue()
								.animate({ left: 0 + 'px'}, 500)
								.addClass('active');
							$('#header-one-line').children('.navbar').css( 'background-color', 'rgba(45, 45, 45, .2)');
						}
					}).on('click', '[data-javo-swap-button]', function(){
						$('[data-javo-swap-button]').each( function(){

							if( $( $(this).data('javo-swap-butotn-tar') ).hasClass('active') ){
								$(this).find('img').prop('src', $(this).data('original') );
							}else{
								$(this).find('img').prop('src', $(this).data('after') );
							};
						} );
					});
				$(window)
					.on('resize', function(){

						$this.el.removeClass('hidden').css('height', javo_stm.options.config.header.map_height);

						$this.st_el.removeClass('hidden').css({
							position	: 'absolute'
							, height	: javo_stm.options.config.header.map_height
							, width		: '100%'
							, top		: 0
							, bottom	: 0
							, zIndex	: 2
						});

						$this.cf_el.removeClass('hidden').css({
							position	: 'absolute'
							, height	: javo_stm.options.config.header.map_height
							, width		: '100%'
							, top		: 0
							, bottom	: 0
							, zIndex	: 2
							, background: '#aaa'
							, overflow	: 'hidden'
						});

						$this.vi_el.removeClass('hidden').css({
							position	: 'absolute'
							, height	: javo_stm.options.config.header.map_height
							, width		: '100%'
							, top		: 0
							, bottom	: 0
							, zIndex	: 2
							, background: '#fff'
						});

						$('[data-javo-single-item-header-panel]').each(function(){
							if( $(this).hasClass('active') ){
								$(this).css('left', 0 + 'px');
							}else{
								$(this).css('left', -($(this).width()) + 'px');
							};
						});

					}).on('scroll', function(){

						if( $this.el.hasClass('active') ){
							if( $('#header-one-line').children('.navbar').hasClass('affix') ){
								$('#header-one-line').children('.navbar').css( 'background-color', '');
							}else{
								$('#header-one-line').children('.navbar').css( 'background-color', 'rgba(45, 45, 45, .2)');
							};
						}else{
							$('#header-one-line').children('.navbar').css( 'background-color', '');
						};
					});
			}
		}
	};
	javo_stm.init();
	$(window).trigger('resize');
});
</script>

<?php
get_footer();