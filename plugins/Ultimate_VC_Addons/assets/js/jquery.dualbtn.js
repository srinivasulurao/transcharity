(function(jQuery) {
	jQuery(document).ready(function(){

		//jQuery(window).on('resize', function() {

		var width=jQuery( window ).width();
		//alert(width);
		if(width>300 && width <400)
		{
			//alert('tsmall');
		var bshadow="inset 0px -200px 0px 0px ";	
		var bshadow2=" inset 0px 200px 0px 0px ";
		}
		else if(width>400 && width <750)
		{
			//alert('small');
		var bshadow="inset -200px 0 0 0 ";	
		var bshadow2="inset 200px 0 0 0";
		}
		else if( width>750 && width<1015){
			//alert('medi');
		var bshadow="inset 0px -200px 0px 0px ";	
		var bshadow2=" inset 0px 200px 0px 0px ";
		}
		else{
			//alert('bgr');
		var bshadow="inset -200px 0 0 0 ";	
		var bshadow2="inset 200px 0 0 0";
		}

	// }).trigger('resize'); 


		/*--- bt1 ----*/
		jQuery(document).on("mouseenter", ".ult_dual1", function() {
	
			var style=jQuery(this).find('.ult-dual-btn-1').attr('class');
			var arr=style.split(" ");
			var style=arr[1]+arr[2];
			
			if(style=='Style1')
			{
			var bghover = jQuery(this).find('.ult-dual-btn-1').data('bghovercolor');
			jQuery(this).css({'background-color':bghover});
			}
			if(style=='Style2')
			{
			var bghover = jQuery(this).find('.ult-dual-btn-1').data('bghovercolor');
			jQuery(this).css({'box-shadow':bshadow+bghover})
			}

			if(style=='Style3')
			{
			var bghover = jQuery(this).find('.ult-dual-btn-1').data('bghovercolor');
			jQuery(this).css({'box-shadow':' inset 0 0 20px 50px '+bghover})
			}

			if(style!='undefined')
			{
			var iconhover = jQuery(this).find('.ult-dual-btn-1').data('icon_hover_color');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'color':iconhover});
			
			var iconbghover = jQuery(this).find('.ult-dual-btn-1').data('iconbghovercolor');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'background':iconbghover});
			
			var iconborderhover = jQuery(this).find('.ult-dual-btn-1').data('iconhoverborder');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'border-color':iconborderhover});

			//for image hover
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon-img').css({'background':iconbghover});
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon-img').css({'border-color':iconborderhover});

						
			var titlehover = jQuery(this).find('.ult-dual-btn-1').data('texthovercolor');
			jQuery(this).find('.ult-dual-btn-1').find('.ult-dual-button-title').css({'color':titlehover});


			}
			
		});
		
		jQuery(document).on("mouseleave", ".ult_dual1", function() {
		
			var style1=jQuery(this).find('.ult-dual-btn-1').attr('class');
			var arr=style1.split(" ");
			var style1=arr[1]+arr[2];
			if(style1=='Style1'){
			var bgcolor = jQuery(this).find('.ult-dual-btn-1').data('bgcolor');
			jQuery(this).css({'background-color':bgcolor});
			
			}
			
			if(style1=='Style2')
			{
			var bgcolor = jQuery(this).find('.ult-dual-btn-1').data('bgcolor');
			jQuery(this).css({'box-shadow':'inset 0px 0 0 0 '+bgcolor});

			}
			if(style1=='Style3')
			{
			var bgcolor = jQuery(this).find('.ult-dual-btn-1').data('bgcolor');
			jQuery(this).css({'box-shadow':'inset 0px 0 0 0 '+bgcolor});
			}
			if(style1!='undefined')
			{
			var iconcolor = jQuery(this).find('.ult-dual-btn-1').data('icon_color');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'color':iconcolor});
			
			var titlecolor = jQuery(this).find('.ult-dual-btn-1').data('textcolor');
			jQuery(this).find('.ult-dual-btn-1').find('.ult-dual-button-title').css({'color':titlecolor});
			
			var iconbgcolor = jQuery(this).find('.ult-dual-btn-1').data('iconbgcolor');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'background':iconbgcolor});
			
			var iconbordercolor = jQuery(this).find('.ult-dual-btn-1').data('iconborder');
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon').css({'border-color':iconbordercolor});

			//for image hover
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon-img').css({'background':iconbgcolor});
			jQuery(this).find('.ult-dual-btn-1').find('.aio-icon-img').css({'border-color':iconbordercolor});

			}
		});
		
		/*--- bt2 ----*/
		jQuery(document).on("mouseenter", ".ult_dual2", function() {
		
			var style1=jQuery(this).find('.ult-dual-btn-2').attr('class');
			var arr=style1.split(" ");
			var style1=arr[1]+arr[2];
			
			if(style1=='Style1'){
			var bghover = jQuery(this).find('.ult-dual-btn-2').data('bghovercolor');
			jQuery(this).css({'background-color':bghover});
		
			}
			
			if(style1=='Style2')
			{
			var bghover = jQuery(this).find('.ult-dual-btn-2').data('bghovercolor');
			jQuery(this).css({'box-shadow':bshadow2+bghover});
			}
			if(style1=='Style3')
			{
			var bghover = jQuery(this).find('.ult-dual-btn-2').data('bghovercolor');
			jQuery(this).css({'box-shadow':' inset 0 0 20px 50px '+bghover});
			}

			if(style1!='undefined')
			{
			var iconhover = jQuery(this).find('.ult-dual-btn-2').data('icon_hover_color');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'color':iconhover});
			
			var titlehover = jQuery(this).find('.ult-dual-btn-2').data('texthovercolor');
			
			jQuery(this).find('.ult-dual-btn-2').find('.ult-dual-button-title').css({'color':titlehover});
			
			var iconbghover = jQuery(this).find('.ult-dual-btn-2').data('iconbghovercolor');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'background':iconbghover});
			
			var iconborderhover = jQuery(this).find('.ult-dual-btn-2').data('iconhoverborder');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'border-color':iconborderhover});

			//for image hover
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon-img').css({'background':iconbghover});
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon-img').css({'border-color':iconborderhover});

			}
		});
		
		jQuery(document).on("mouseleave", ".ult_dual2", function() {
			var style1=jQuery(this).find('.ult-dual-btn-2').attr('class');
			var arr=style1.split(" ");
			var style1=arr[1]+arr[2];
			if(style1=='Style1'){

			var bgcolor = jQuery(this).find('.ult-dual-btn-2').data('bgcolor');
			jQuery(this).css({'background-color':bgcolor});
			
			}


			if(style1=='Style2')
			{
			var bgcolor = jQuery(this).find('.ult-dual-btn-2').data('bgcolor');
			jQuery(this).css({'box-shadow':'inset 0px 0 0 0 '+bgcolor});

			}
			
			if(style1=='Style3')
			{
			var bgcolor = jQuery(this).find('.ult-dual-btn-2').data('bghovercolor');
			jQuery(this).css({'box-shadow':' inset 0 0 0 0 '+bgcolor});
			}

			if(style1!='undefined')
			{
			var iconcolor = jQuery(this).find('.ult-dual-btn-2').data('icon_color');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'color':iconcolor});
			
			var titlecolor = jQuery(this).find('.ult-dual-btn-2').data('textcolor');
			jQuery(this).find('.ult-dual-btn-2').find('.ult-dual-button-title').css({'color':titlecolor});
			
			var iconbgcolor = jQuery(this).find('.ult-dual-btn-2').data('iconbgcolor');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'background':iconbgcolor});
			
			var iconbordercolor = jQuery(this).find('.ult-dual-btn-2').data('iconborder');
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon').css({'border-color':iconbordercolor});

			//for image hover
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon-img').css({'background':iconbgcolor});
			jQuery(this).find('.ult-dual-btn-2').find('.aio-icon-img').css({'border-color':iconbordercolor});

			}
		});
		
		
			});

		/*---for button----*/

		jQuery(document).on("mouseenter", ".main-dual", function() {
			
			var mainhoverborder = jQuery(this).data('bhcolor');
			//jQuery(this).find('.ivan-button').css({'border-color':mainhoverborder});

		});


		jQuery(document).on("mouseleave", ".main-dual", function() {

			var mainborder = jQuery(this).data('bcolor');
			//jQuery(this).find('.ivan-button').css({'border-color':mainborder});

		});

	 

}( jQuery ));

	jQuery(document).ready(function(){
		//alert('hi');
		jQuery( ".main-dual" ).each(function( index ) {
 		
 		var ht1=jQuery(this).find('.ult_dual1').outerHeight();
 		ht1=parseInt(ht1);
 		
 		var ht2=jQuery(this).find('.ult_dual2').outerHeight();
 		ht2=parseInt(ht2);
 		
 		if(ht1>ht2)
 		{
 			jQuery(this).find('.ult_dual2').css({'height':ht1});
 			jQuery(this).find('.ult_dual1').css({'height':ht1});
 			
 		}
 		else if(ht1<ht2)
 		{
			jQuery(this).find('.ult_dual1').css({'height':ht2});
			jQuery(this).find('.ult_dual2').css({'height':ht2});
		 			
 		}
 		else if(ht1==ht2)
 		{
 			jQuery(this).find('.ult_dual1').css({'height':ht2});
			jQuery(this).find('.ult_dual2').css({'height':ht2});

 		}
 		

	});

	});