<?php
/*
* Add-on Name: Creatve Link for Visual Composer
* Add-on URI: http://dev.brainstormforce.com
*/
if(!class_exists('AIO_creative_link')) 
{
	class AIO_creative_link

	{
		function __construct()
		{
			add_shortcode('ult_createlink',array($this,'ult_createlink_shortcode'));
			add_action('admin_init',array($this,'ultimate_createlink'));
			add_action( 'wp_enqueue_scripts', array( $this, 'dualbutton_admin_scripts') );
		}

		//enque script
		function dualbutton_admin_scripts(){
			wp_register_style( 'ult_cllink', plugins_url('../assets/min-css/creative-link.min.css', __FILE__) );
			wp_register_script("jquery.ult_cllink",plugins_url("../assets/min-js/creative-link.min.js",__FILE__),array('jquery'),ULTIMATE_VERSION);
			//wp_enqueue_script("jquery.ult_cllink");
		
		}


		// Shortcode handler function for stats Icon
		function ult_createlink_shortcode($atts)
		{
			

	extract(shortcode_atts( array(	

				'btn_link' => '',
				'text_color' => '',
				'text_hovercolor' => '',
				'background_color' => '',
				'bghovercolor' => '',				
				'font_family' => '',
				'heading_style' => '',
				'title_font_size' => '',
				'title_line_ht' => '',
				'link_hover_style'=>'',
				'border_style' => '',
				'border_color' => '',			
				'border_hovercolor' => '',
				'border_size' => '',
				'el_class' => '',
				'dot_color' =>'',
				
			),$atts));
 		
 		$href=$target=$text=$url= "";
		if($btn_link !== ''){
				 $href = vc_build_link($btn_link);
				$target = (isset($href['target'])) ? "target='".$href['target']."'" : '';
				$text=$href['title'];
				$url=$href['url'];
				if($url==''){
					$url="javascript:void(0);";
				}
			}

/*--- text typography----*/

$title_style='';$secondtitle_style='';


if (function_exists('get_ultimate_font_family')) {
		$mhfont_family = get_ultimate_font_family($font_family);  		//for font family
		$title_style .= 'font-family:'.$mhfont_family.';';
		$secondtitle_style .='font-family:'.$mhfont_family.';';
	}
	if (function_exists('get_ultimate_font_style')) {                	//for font style
		$title_style .= get_ultimate_font_style($heading_style);
		$secondtitle_style .=get_ultimate_font_style($heading_style);
	}
	$title_style .= 'font-size:'.$title_font_size.'px;'; 				//font-size
	$title_style .= 'color:'.$text_color.';';//color
	if($link_hover_style!='Style_2'){
	$title_style .= 'line-height:'.$title_line_ht.'px;';				//font-line-height
    }
    else{
    	$title_style .= 'line-height:'.$title_line_ht.'px;';			//font-line-height
    }
	$secondtitle_style .= 'font-size:'.$title_font_size.'px;';			//font-size for backend title
	$secondtitle_style .= 'line-height:'.$title_line_ht.'px;';			

	if($link_hover_style=='Style_2'){
	$span_style = 'background:'.$background_color.';';     //background-color
	}

/*--- hover effect for link-----*/

$data_link='';
 if($link_hover_style==''){  
		$data_link .='data-textcolor="'.$text_color.'"';
		$data_link .='data-texthover="'.$text_color.'"';
	}
	else{
		$data_link .='data-textcolor="'.$text_color.'"';
		$data_link .='data-texthover="'.$text_hovercolor.'"';
	}

if($link_hover_style=='Style_2'){
	
	if($text_hovercolor==''||$bghovercolor==''){

		$data_link .='data-bgcolor="'.$background_color.'"';
		$data_link .='data-bghover="'.$background_color.'"';
		//$data_link .='data-texthover="'.$text_color.'"';
	}
	else{

		$data_link .='data-bgcolor="'.$background_color.'"';
		$data_link .='data-bghover="'.$bghovercolor.'"';
	}
	//echo$bghovercolor;
}
$data_link .='data-style="'.$link_hover_style.'"';

/*--- border style---*/

$data_border='';
if($border_style!=''){
 $data_border .='border-color:'.$border_color.';';
 $data_border .='border-width:'.$border_size.'px;';
 $data_border .='border-style:'.$border_style.';';


}

$main_span=$before=$borderhover='';
$after='';$style=$class=$id=$colorstyle='';

/*-- hover style---*/



if($link_hover_style=='Style_1'){               //style1
$class .='cl-effect-1';
$id .='cl-effect-1';
$colorstyle .='color:'.$text_color.';'; //text color for bracket
$colorstyle .='font-size:'.$title_font_size.'px;';


}
else if($link_hover_style=='Style_2'){              //style2
$class .='cl-effect-2';
$id .='cl-effect-2';

}
else if($link_hover_style=='Style_3'){               //style3
$class .='cl-effect-3';
$id .='cl-effect-3';
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .=$data_border; //text color for btm border
$after .='<span class="link_btm3 " style="'.$borderstyle.'"></span>';

}
else if($link_hover_style=='Style_4'){               //style4
$class .='cl-effect-4';
$id .='cl-effect-4';
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .=$data_border; //text color for btm border
$after .='<span class="link_btm4 " style="'.$borderstyle.'"></span>';
}
else if($link_hover_style=='Style_6'){               //style6
$class .='cl-effect-6';
$id .='cl-effect-6';//
$colorstyle .='color:'.$text_hovercolor.';'; 
$colorstyle .='font-size:'.$title_font_size.'px;';
$after .='<span class="btn6_link_top " data-color="'.$dot_color.'">.</span>';
}
else if($link_hover_style=='Style_5'){               //style5
$class .='cl-effect-5';
$id .='cl-effect-5';//
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .=$data_border; //text color for btm border
$before='<span class="link_top" style="'.$borderstyle.'"></span>';
$after .='<span class="link_btm  " style="'.$borderstyle.'"></span>';
}

else if($link_hover_style=='Style_7'){               //style7
$class .='cl-effect-7';
$id .='cl-effect-7';//
//$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .='background:'.$border_color.';';
$borderstyle .='height:'.$border_size.'px;';

$before='<span class="link_top btn7_link_top " style="'.$borderstyle.'"></span>';
$after .='<span class="link_btm  btn7_link_btm" style="'.$borderstyle.'"></span>';
}

else if($link_hover_style=='Style_8'){               //style8
$class .='cl-effect-8';
$id .='cl-effect-8';//
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .='outline-color:'.$border_color.';';
$borderstyle .='outline-width:'.$border_size.'px;';
$borderstyle .='outline-style:'.$border_style.';'; //text color for btm border

$borderhover .='outline-color:'.$border_hovercolor.';';
$borderhover .='outline-width:'.$border_size.'px;';
$borderhover .='outline-style:'.$border_style.';'; //text color for btm border

$before='<span class="link_top btn8_link_top " style="'.$borderstyle.'"></span>';
$after .='<span class="link_btm  btn8_link_btm" style="'.$borderhover.'"></span>';
}
else if($link_hover_style=='Style_9'){               //style9
$class .='cl-effect-9';
$id .='cl-effect-9';//
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .='background:'.$border_color.';';
$borderstyle .='height:'.$border_size.'px;';
//$borderstyle .='height:'; //text color for btm border
$before='<span class="link_top btn9_link_top " style="'.$borderstyle.'"></span>';
$after .='<span class="link_btm  btn9_link_btm" style="'.$borderstyle.'"></span>';

}
else if($link_hover_style=='Style_10'){               //style10
$class .='cl-effect-10';
$id .='cl-effect-10';//
$colorstyle .='font-size:'.$title_font_size.'px;';
$borderstyle .='background:'.$border_color.';';
$borderstyle .='height:'.$border_size.'px;';
$span_style .= 'background:'.$background_color.';';
if($border_style!=''){
 $span_style .= 'border-top:'.$border_size.'px '.$border_style.' '.$border_color.';';
}

$span_style1='';
$span_style1 .= 'background:'.$bghovercolor.';';
}
else if($link_hover_style=='Style_11'){               //style11
$class .='cl-effect-11';
$id .='cl-effect-11';//
$borderstyle .='background:'.$border_color.';';
$span_style1='';
$span_style1 .= 'background:'.$bghovercolor.';';
$span_style1 .= 'color:'.$text_hovercolor.';';
$span_style1 .= $secondtitle_style;

$before='<span class="link_top btn11_link_top " style="'.$span_style1.'">'.$text.'</span>';

}
$text=ucfirst($text);
	$output='';

	if($link_hover_style!='Style_10'){

			$output .='<div class=" main_cl '.$el_class.'" >
	 			<nav class="'.$class.'  cr_link" id="'.$id.'">
					<a  href = "'.$url.'" '.$target.' class="color1link " style="'.$colorstyle.' "  '.$data_link.'>
						'.$before.'
						<span data-hover="'.$text.'" style="'.$title_style.';'.$span_style.'" class="btn10_span">'.$text.'</span>
						'.$after.'
					</a>
				</nav>
			</div>';

		}
	  else if($link_hover_style=='Style_10'){

			$output .='<div class=" main_cl  '.$el_class.'" >
	 			<nav class="'.$class.'  cr_link" id="'.$id.'">
					<a  href = "'.$url.'" '.$target.' class="color1link" style="'.$colorstyle.' "  '.$data_link.'>
						<span   class="btn10_span" style="'.$span_style.'" data-color="'.$border_color.'"  data-bhover="'.$bghovercolor.'" data-bstyle="'.$border_style.'">
							<span class="link_btm  btn10_link_top" style="'.$span_style1.'">
								<span style="'.$title_style.';color:'.$text_hovercolor.'">'.$text.'</span>
							</span>
							<span style="'.$title_style.';">'.$text.'</span>
						</span>
						
					</a>
				</nav>
			</div>';
	 	 }

	return $output;

		}


		function ultimate_createlink()
		{
			if(function_exists('vc_map'))
			{
				vc_map(
					array(
					   "name" => __("Creative Link"),
					   "base" => "ult_createlink",
					   "icon"=>plugins_url("../admin/img/creative-link.png",__FILE__),
					   "category" => __("Ultimate VC Addons","smile"),
					   "description" => __("Add a custom link.","smile"),
					   "params" => array(							
							// Play with icon selector
					   		array(
								"type" => "vc_link",
								"class" => "",
								"heading" => __("Link ","smile"),
								"param_name" => "btn_link",
								"value" => "",
								"description" => __("Add a custom link or select existing page. You can remove existing link as well.","smile"),
								//"group" => "Title Setting",

							),
							
							
							/*---typography-------*/
	
							array(
									"type" => "ult_param_heading",
									"param_name" => "bt1typo-setting",
									"text" => __("Typography", "ultimate"),
									"value" => "",
									"class" => "",
									"group" => "Typography ",
									'edit_field_class' => 'ult-param-heading-wrapper vc_column vc_col-sm-12',
									
								),

							array(
								"type" => "ultimate_google_fonts",
								"heading" => __("Title Font Family", "imedica"),
								"param_name" => "font_family",
								"description" => __("Select the font of your choice. You can <a target='_blank' href='".admin_url('admin.php?page=ultimate-font-manager')."'>add new in the collection here</a>.", "imedica"),
								"group" => "Typography ",
								),	

							array(
								"type" => "ultimate_google_fonts_style",
								"heading" 		=>	__("Font Style", "imedica"),
								"param_name"	=>	"heading_style",
								
								"group" => "Typography ",
							),	
							array(
								"type" => "number",
								"param_name" => "title_font_size",
								"heading" => __("Font size","imedica"),
								"value" => "15",
								"suffix" => "px",
								"group" => "Typography ",
							),
							
							array(
								"type" => "number",
								"param_name" => "title_line_ht",
								"heading" => __("Line Height","imedica"),
								"value" => "20",
								"suffix" => "px",
								"group" => "Typography ",
								
							),
							/*-----------general------------*/
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Link Hover Style", "smile"),
								"param_name" => "link_hover_style",
								"value" => array(
									"None"=> "",
									"Style 1"=> "Style_1",
									"Style 2" => "Style_2",
									"Style 3" => "Style_3",
									"Style 4"=> "Style_4",
									"Style 5" => "Style_5",
									"Style 6" => "Style_6",
									/*"Style 7" => "Style_7",*/
									"Style 7" => "Style_8",
									"Style 8" => "Style_9",
									"Style 9" => "Style_10",
									"Style 10" => "Style_11",
								),
								"description" => __("Select the Hover style for Link.","smile"),
								
							),
							array(
									"type" => "ult_param_heading",
									"param_name" => "button1bg_settng",
									"text" => __("Color Settings", "smile"),
									"value" => "",
									"class" => "",
									'edit_field_class' => 'ult-param-heading-wrapper vc_column vc_col-sm-12',
								),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Text Color", "smile"),
								"param_name" => "text_color",
								"value" => "#333333",
								"description" => __("Select text color for Link.", "smile"),	
															
							),
							/*array(
								"type" => "chk-switch",
								"class" => "",
								"heading" => __("Hover Effect ", "smile"),
								"param_name" => "enable_hover",
								"value" => "",
								"options" => array(
										"enable" => array(
											"label" => "Enable Hover effect?",
											"on" => "Yes",
											"off" => "No",
										)
									),
								/*"description" => __("Enable Hover effect on hover?", "imedica"),
							"dependency" => Array("element" => "link_hover_style","value" => array("Style_2")),
							),*/
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Text Hover Color", "smile"),
								"param_name" => "text_hovercolor",
								"value" => "#333333",
								"description" => __("Select text hover color for Link.", "smile"),	
								"dependency" => Array("element" => "link_hover_style","not_empty" => true),
								
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Background Color", "smile"),
								"param_name" => "background_color",
								"value" => "#ffffff",
								"description" => __("Select Background Color for link.", "smile"),	
								//"group" => "Title Setting",
								"dependency" => Array("element" => "link_hover_style","value" => array("Style_2","Style_10","Style_11")),
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Background Hover Color", "smile"),
								"param_name" => "bghovercolor",
								"value" => "",
								"description" => __("Select background hover color for Button.", "smile"),	
								"dependency" => Array("element" => "link_hover_style","value" => array("Style_2","Style_10","Style_11")),
								
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Border Style", "smile"),
								"param_name" => "border_style",
								"value" => array(
									"None"=> " ",
									"Solid"=> "solid",
									"Dashed" => "dashed",
									"Dotted" => "dotted",
									"Double" => "double",
									"Inset" => "inset",
									"Outset" => "outset",
									
								),
								"description" => __("Select the border style for link.","smile"),
								"dependency" => Array("element" => "link_hover_style","value" => array("Style_3","Style_4","Style_5","Style_7","Style_8","Style_9","Style_10")),
								
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __(" Border Color", "smile"),
								"param_name" => "border_color",
								"value" => "#333333",
								"description" => __("Select border color for link.", "smile"),	
								//"dependency" => Array("element" => "border_style", "not_empty" => true),
								"dependency" => Array("element" => "border_style", "value" => array("solid","dashed","dotted","double","inset","outset")),
								
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __(" Border HoverColor", "smile"),
								"param_name" => "border_hovercolor",
								"value" => "#333333",
								"description" => __("Select border hover color for link.", "smile"),	
								"dependency" => Array(
									"element"=>"link_hover_style","value" => array("Style_8"),
									/*"element" => "border_style",  "not_empty" => true*/ ),
								
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __(" Border Width", "smile"),
								"param_name" => "border_size",
								"value" => 1,
								"min" => 1,
								"max" => 10,
								"suffix" => "px",
								"description" => __("Thickness of the border.", "smile"),
								//"dependency" => Array("element" => "border_style", "not_empty" => true),	
								"dependency" => Array("element" => "border_style", "value" => array("solid","dashed","dotted","double","inset","outset")),
								
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __(" Dot Color", "smile"),
								"param_name" => "dot_color",
								"value" => "#333333",
								"description" => __("Select color for dots.", "smile"),	
								"dependency" => Array("element"=>"link_hover_style","value" => array("Style_6")),
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Custom CSS Class", "smile"),
								"param_name" => "el_class",
								"value" => "",
								"description" => __("Ran out of options? Need more styles? Write your own CSS and mention the class name here.", "smile"),
							),
						),
					)
				);
			}
		}
		
	}
}
if(class_exists('AIO_creative_link'))
{
	
$AIO_creative_link = new AIO_creative_link;

}