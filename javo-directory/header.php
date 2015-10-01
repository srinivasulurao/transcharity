<?php
/*
 * The Header template for Javo Theme
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */
// Get Options
global $javo_tso;
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<!--<!
                               This site was Designed Exclusively For 

    888888 88""Yb    db    88b 88 .dP"Y8  dP""b8 88  88    db    88""Yb 88 888888 Yb  dP 
      88   88__dP   dPYb   88Yb88 `Ybo." dP   `" 88  88   dPYb   88__dP 88   88    YbdP  
      88   88"Yb   dP__Yb  88 Y88 o.`Y8b Yb      888888  dP__Yb  88"Yb  88   88     8P   
      88   88  Yb dP""""Yb 88  Y8 8bodP'  YboodP 88  88 dP""""Yb 88  Yb 88   88    dP 
      
                                         Transcharity.Org
                              Redefining How Charity is and can be done
<!-- 
<!--     
                            ∆   Thanks For Checking Out Our Code   ∆ 

<!-- We are always  looking for new talent, if interested in changing the world give us a call 210-802-1277

   dP""b8    db    88""Yb 888888 888888 88""Yb     8b    d8    db    8888b.  888888     88 888888 
  dP   `"   dPYb   88__dP   88   88__   88__dP     88b  d88   dPYb   8I  Yb  88__       88   88   
  Yb       dP__Yb  88"Yb    88   88""   88"Yb      88YbdP88  dP__Yb  8I  dY  88""       88   88   
   YboodP dP""""Yb 88  Yb   88   888888 88  Yb     88 YY 88 dP""""Yb 8888Y"  888888     88   88  

                       ∆  An evil multinational corporation has to start somewhere  ∆

-->  
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="icon" type="image/x-icon" href="<?php echo $javo_tso->get('favicon_url', '');?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $javo_tso->get('favicon_url', '');?>" />

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lte IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class();?>>
<?php do_action('javo_after_body_tag');?>
<?php if( defined('ICL_LANGUAGE_CODE') ){ ?>
	<input type="hidden" name="javo_cur_lang" value="<?php echo ICL_LANGUAGE_CODE;?>">
<?php }; ?>
<div class="right_menu_inner">
	<div class="navmenu navmenu-default navmenu-fixed-right offcanvas" style="" data-placement="right">
		<div class="navmenu-fixed-right-canvas">

			<?php
			if( is_active_sidebar('canvas-menu-widget') )
			{
				dynamic_sidebar("canvas-menu-widget");
			} ?>	

		</div><!--navmenu-fixed-right-canvas-->
    </div> <!-- navmenu -->
</div> <!-- right_menu_inner -->

<div id="page-style" class="canvas <?php echo $javo_tso->get('layout_style_boxed') == "active"? "boxed":""; ?>">
	<div class="loading-page<?php echo $javo_tso->get('preloader_hide') == 'use'? ' hidden': '';?>">
		<div id="status" style="background-image:url(<?php echo $javo_tso->get('logo_url', JAVO_IMG_DIR.'/javo-directory-logo-v1-3.png');?>);">
			<div class="spinner">
				<div class="dot1"></div>
				<div class="dot2"></div>
			</div><!-- /.spinner -->
		</div><!-- /.status -->
	</div><!-- /.loading-page -->


<?php
// Get Header File.
$file_name = JAVO_HDR_DIR . '/head-directory.php';
if( file_exists( $file_name ) )
{
	require_once $file_name;
}else{
	die( __("Not found the header file.", 'javo_fr') . $file_name );
}

if(is_singular()){
	get_template_part("library/header/post", "header");
};