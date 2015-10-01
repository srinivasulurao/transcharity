<?php
global $post
		, $javo_video_query;

$detail_images		= @unserialize(get_post_meta($post->ID, "detail_images", true));
if(!empty($detail_images) || $javo_video_query->get('html', null) != null):
	echo '<div class="javo_detail_slide">';
		echo '<ul class="slides list-unstyled">';
		if( $javo_video_query->get('video_id', null) != null && $javo_video_query->get('single_position', 'slide') == 'slide' ){
			$javo_this_video_thumbnail_url		= '';
			switch( $javo_video_query->get('portal') ){
				case 'youtube':
					$javo_this_video_thumbnail_url	= 'http://img.youtube.com/vi/'.$javo_video_query->get('video_id').'/0.jpg'; break;
				case 'vimeo':
					$javo_get_vimeo_xml_content		= wp_remote_fopen( 'http://vimeo.com/api/v2/video/'.$javo_video_query->get('video_id').'.json');
					$javo_get_vimeo_xml				= json_decode($javo_get_vimeo_xml_content, true);
					$javo_this_video_thumbnail_url	= $javo_get_vimeo_xml[0]['thumbnail_large'];
				break;
				default:			$javo_this_video_thumbnail_url = JAVO_THEME_DIR.'/assets/images/javo-single-item-video-none.png';
			}; // End Switch
			printf( '<li class="video"><b href="%s"><img src="%s" width="823" height="420"></b></li>', $javo_video_query->get('url'), $javo_this_video_thumbnail_url );
		};
		if( !empty( $detail_images ) ){
			foreach($detail_images as $index => $image):
				$img_src = wp_get_attachment_image_src($image, 'full');
				if( !empty( $img_src ) ){
					printf('<li class="image"><i href="%s" style="cursor:pointer">%s</i></li>'
						, $img_src[0]
						, wp_get_attachment_image($image, 'javo-item-detail')
					);
				};
			endforeach;
		};
		echo '</ul>';
	echo '</div>';
endif;
?>

<script type="text/javascript">
jQuery(function($){
	"use strict";
	$(".javo_detail_slide_cnt").flexslider({
		animation:"slide",
		controlNav:false,
		slideshow:false,
		animationLoop: false,
		itemWidth:80,
		itemMargin:2,
		asNavFor: ".javo_detail_slide"
	});

	$(".javo_detail_slide").flexslider({
		animation:"slide",
		controlNav:false,
		slideshow:true,
		sync: ".javo_detail_slide_cnt"
	}).find('li').css('overflow', 'hidden');

	$('.javo_detail_slide .image').magnificPopup({
		gallery:{ enabled: true }
		, delegate: 'i'
		, type: 'image'
	});
	$('.javo_detail_slide .video').magnificPopup({
		delegate		: 'b'
		, type			: 'iframe'
		, preloader		: true
	});

});
</script>
<!-- slide end -->