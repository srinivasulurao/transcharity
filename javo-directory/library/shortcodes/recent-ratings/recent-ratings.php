<?php
class javo_recent_ratings{
	public function __construct(){
		add_shortcode('javo_recent_ratings', Array($this, "javo_recent_ratings_callback"));
	}
	public function javo_recent_ratings_callback($atts, $content=""){
		global $javo_tso;
		wp_enqueue_style( 'javo-recent-ratings-css', JAVO_THEME_DIR.'/library/shortcodes/recent-ratings/recent-ratings.css', '1.0' );
		extract(shortcode_atts(
			Array(
				'title'=>''
				, 'sub_title'=>''
				, 'title_text_color'=>'#000'
				, 'sub_title_text_color'=>'#000'
				, 'line_color'=> '#fff'
				, 'items'=> 5
			), $atts)
		);

		ob_start();
		echo apply_filters('javo_shortcode_title', $title, $sub_title, Array('title'=>'color:'.$title_text_color.';', 'subtitle'=>'color:'.$sub_title_text_color.';', 'line'=>'border-color:'.$line_color.';'));

		$javo_rating = new javo_rating( get_the_ID() );
		?>
		<div class="javo-recent-ratings-shortcode">
			<?php echo $javo_rating->ratings(true, $items, 'tab', 150);?>
		</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}
}
new javo_recent_ratings();