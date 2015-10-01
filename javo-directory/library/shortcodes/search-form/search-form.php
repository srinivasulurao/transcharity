<?php
class javo_search_form
{
	static $load_script = false;
	public function __construct(){
		add_shortcode(	'javo_search_form', Array( __CLASS__ ,'javo_search_form_callback' ) );
		add_action(	'wp_footer', Array( __CLASS__ ,'load_script_func' ) );
	}

	public static function load_script_func()
	{
		if( self::$load_script )
		{
			wp_enqueue_script( 'jQuery-chosen-autocomplete' );
			wp_enqueue_script( 'google-map' );
		}
	}

	public static function javo_search_form_callback( $atts, $content='' )
	{

		global
			$javo_tso
			, $javo_tso_map;

		self::$load_script = true;

		extract( shortcode_atts(Array(

			/*	Describe :		Action
			*	Type :			String( Empty / 'map' )
			*/
			'action'			=> ''

			/*	Describe :		Hidden Field
			*	Type :			Array
			*/
			, 'hide_field'		=> Array()
		), $atts));

		$errors				= new wp_error();
		$javo_query			= new javo_ARRAY( $_GET );
		$javo_redirect		= home_url();

		if(
			(int) $action > 0 &&
			! is_archive() &&
			! is_search()
		){
			$javo_redirect = apply_filters( 'javo_wpml_link', $action );
		}

		$hide_field		= @explode( ',', $hide_field );

		if( !empty( $hide_field ) ){
			foreach( $hide_field as $idx => $item ){ $hide_field[$item] = 'hide'; unset($hide_field[$idx]); }
		}

		$javo_setVisibleLocation	= Array();

		$javo_this_onoff = new javo_ARRAY( $hide_field );

		$javo_getCurrentPageArchive		= is_archive() || is_search();
		$javo_getVisibleLocationType	= $javo_tso_map->get('tab_location_field', '') !=  'select' ? 'gg_ac' : 'term';

		if( ! $javo_getCurrentPageArchive )
		{
			if( $javo_getVisibleLocationType == 'gg_ac' )
			{
				$javo_setVisibleLocation['term']	= " hidden";
				$javo_setVisibleLocation['gg_ac']	= "";
			}else{
				$javo_setVisibleLocation['term']	= "";
				$javo_setVisibleLocation['gg_ac']	= " hidden";
			}
		}else{
			$javo_setVisibleLocation['term']	= "";
			$javo_setVisibleLocation['gg_ac']	= " hidden";
		}

		if( $javo_this_onoff->get('location', null) == 'hide' )
		{
			$javo_setVisibleLocation['term']		= " hidden";
		}


		if( $errors->get_error_code() != "" )
		{
			ob_start();
			echo "<div class='container'><div class='alert alert-warning' role='alert'>{$errors->get_error_message()}</div></div>";
			return ob_get_clean();
		}

		ob_start();
			?>
			<div class="container search-type-a-wrap">


			<form role="form" data-javo-search-form>

				<div class="search-type-a-inner">

					<div class="search-box-inline<?php echo $javo_this_onoff->get('keyword', null) == 'hide'? ' hidden':'';?>">
						<input type="text" class="search-a-items form-control" name="s" placeholder="<?php _e('Keyword', 'javo_fr');?>" value="<?php echo $javo_query->get('s', null);?>">
					</div><!-- /.search-box-inline -->

					<div class="search-box-inline<?php echo $javo_this_onoff->get('category', null) == 'hide'? ' hidden':'';?>">
						<select name="filter[item_category]" class="form-control">
							<option value=""><?php _e('Category', 'javo_fr');?></option>
							<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_category', null, 'select', $javo_query->get('category', 0), 0, 0 );?>
						</select>
					</div><!-- /.search-box-inline -->

					<div class="search-box-inline<?php echo $javo_setVisibleLocation['term'];?>">
						<select name="filter[item_location]" class="form-control">
							<option value=""><?php _e('Location', 'javo_fr');?></option>
							<?php echo apply_filters('javo_get_selbox_child_term_lists', 'item_location', null, 'select', $javo_query->get('location', 0), 0, 0 );?>
						</select>
					</div><!-- /.search-box-inline -->

					<div class="search-box-inline<?php echo $javo_setVisibleLocation['gg_ac'];?>">
						<input type="text" name="filter[location]" class="form-control">
					</div><!-- /.col-md-2 -->



					<div class="search-box-inline">
						<input type="submit" class="btn btn-block btn-primary admin-color-setting" value="<?php _e('Search', 'javo_fr');?>">
					</div><!-- /.col-md-2 -->

				</div> <!-- search-type-a-inner -->

			</form>

			<fieldset>
				<input type="hidden" value="<?php echo (int)$action > 0 ? apply_filters( 'javo_wpml_link', $action):null;?>" data-javo-search-action-template-url>
				<input type="hidden" value="<?php echo (int)$action > 0 ? 'data-javo-patch-form-for-template' : 'data-javo-patch-form-for-result';?>" data-javo-search-form-action-type>
			</fieldset>

			<!-- Search Result Page -->
			<form action="<?php echo home_url('/');?>" method="get" data-javo-patch-form-for-result>
				<input type="hidden" name="post_type" value="item">
				<input type="hidden" name="category" value="<?php echo $javo_query->get('category');?>" data-javo-sf-category>
				<input type="hidden" name="location" value="<?php echo $javo_query->get('location');?>" data-javo-sf-location>
				<input type="hidden" name="s" data-javo-sf-keyword>
			</form><!-- /data-javo-patch-form-for-result : Go to Archive Page -->

			<!-- Javo Map Template -->
			<form action="<?php echo $javo_redirect;?>" method="post" data-javo-patch-form-for-template>
				<input type="hidden" name="category" value="<?php echo $javo_query->get('category');?>" data-javo-sf-category>
				<input type="hidden" name="location" value="<?php echo $javo_query->get('location');?>" data-javo-sf-location>
				<input type="hidden" name="radius_key">
				<input type="hidden" name="keyword" data-javo-sf-keyword>
			</form><!-- /data-javo-patch-form-for-template : Go to Map -->

			</div> <!-- container search-type-a-wrap -->

			<script type="text/javascript">
			jQuery(function($){

				var javo_search_bar_script = {
					el:{
						origin		: '[data-javo-search-form]'
						, result	: '[data-javo-patch-form-for-result]'
						, template	: '[data-javo-patch-form-for-template]'
						, type		: '[data-javo-search-form-action-type]'
					}
					, template_url: $('[data-javo-search-action-template-url]').val()
					, msg: function(str){ $.javo_msg({content:str, delay:10000}); }
					, init: function(){
						var $cat = this.setAutoComplete('[name="filter[item_category]"]');
						var $loc = this.setAutoComplete('[name="filter[item_location]"]');
						var eloc = $( this.el.origin ).find( '[name="filter[location]"]' );
						
						
						var javo_ac = new google.maps.places.Autocomplete( eloc[0] );




						$(document)
							.on('submit', this.el.origin, this.submit)
					}
					, setAutoComplete: function(el){
						$(this.el.origin).find(el).chosen();
						return $(this.el.origin).find(el);
					}
					, submit: function(e){
						e.preventDefault();
						var o = javo_search_bar_script;
						var r = $( o.el.origin );
						var t = $('[' + $(o.el.type).val() + ']');
						t.find('[data-javo-sf-category]')	.val( r.find('[name="filter[item_category]"]').val() );
						t.find('[data-javo-sf-location]')	.val( r.find('[name="filter[item_location]"]').val() );
						t.find('[name="radius_key"]')		.val( r.find('[name="filter[location]"]').val() );
						t.find('[data-javo-sf-keyword]')	.val( r.find('input[name="s"]').val() );
						t.submit();
					}
				}
				javo_search_bar_script.init();
			});
			</script>
			<?php
		return ob_get_clean();
	}
}
new javo_search_form();