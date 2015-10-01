<?php
class get_char{
	var $title
		, $origin_title
		, $post
		, $content
		, $p_type
		, $p_id
		, $excerpt
		, $excerpt_meta
		, $area
		, $price
		, $sns
		, $author
		, $author_name
		, $lister_page
		, $author_item_count;
	public function __construct($post){
		$this->post = $post;
		$this->content = apply_filters('the_content', $this->post->post_content);
		$this->origin_title = sprintf("<a href='%s'>%s</a>", get_permalink($post->ID), $post->post_title);
		$this->p_type = get_post_type($post->ID);
		$this->p_id = $post->ID;
		$this->area = sprintf("%s %s", $this->__meta("area"), $this->__meta("area_Postfix"));
		$this->price = sprintf("%s%s %s"
			, $this->__meta("price_Postfix")
			, number_format((int)$this->__meta("sale_price"))
			, ((strpos(strtolower($this->__hasStatus()), "rent") !== false)? "/ ".__("M", "javo_fr") : ""));
		$sns = Array("sns-facebook", "sns-twitter", "sns-google");
		$sns_output = "";
		foreach($sns as $index=> $class_name)
			$sns_output .= sprintf('<span><i class="%s" data-title="%s" data-url="%s"></i></span>'
				,$class_name, $post->post_title, get_permalink($post->ID)
			);
		$this->sns = $sns_output;

		switch( $this->__meta('item_author') ){
			case "admin":
				$this->author = get_user_by('email', get_option( 'admin_email' ));
			break;
			case "other":
				if( (int)$this->__meta('item_author_id') > 0){
					$this->author = get_userdata((int)$this->__meta('item_author_id'));
				}else{
					$this->author = get_userdata($post->post_author);
				};
			break;
			default:
				$this->author = get_userdata($post->post_author);
		};
		$this->author_name = !empty($this->author)? sprintf("%s %s", $this->author->first_name, $this->author->last_name):null;
		$this->author_item_count = $this->get_author_item_count();

		$javo_lister_page = "";
		if(!empty($this->author)){
			$args = Array("author"=>$this->author->ID, "posts_per_page"=>1, "post_type"=>"lister");
			$lister_post = get_posts($args);
			$javo_lister_page = get_site_url();
			if(!empty($lister_post)){
				$javo_lister_page = get_permalink( $lister_post[0]->ID );
			};

			//wp_reset_postdata();
		};
		$this->lister_page = $javo_lister_page;

		$this->excerpt_meta = sprintf("%s / %s / %s / %s"
			, ($this->p_type == "item" ? $this->__meta("area")." ".__("Sq ft", "javo_fr") : null)
			, ($this->p_type == "item" ? $this->__meta("bedrooms")." ".__("Beds", "javo_fr") : $this->author->user_login)
			, ($this->p_type == "item" ? $this->__meta("bathrooms")." ".__("Baths", "javo_fr") : date("Y-m-d", strtotime($this->post->post_date)))
			, ($this->p_type == "item" ? $this->__meta("parking")." ".__("Parking", "javo_fr") : $this->__cate("category", __("No category", 'javo_fr'), true))
		);
		switch($this->p_type){
			case "item": $this->__item($post); break;
			case "post": default: $this->__post($post); break;
		};
	}
	public function __item($post){
		$this->title = sprintf("<a href='%s'>%s%s%s</a>"
			, get_permalink($post->ID)
			, $this->__meta("price_Postfix")
			, number_format((int)$this->__meta("sale_price"))
			, ((strpos(strtolower($this->__hasStatus()), "rent") !== false)? " / ".__("Month", "javo_fr") : "")
		);
	}
	public function __hasStatus(){
		/*
		$output = "";
		$terms = wp_get_post_terms($this->post->ID, "item_status");
		if(is_Array($terms))
			foreach($terms as $term)
				$output = $term->name;
		return ($output != "")? $output : false;
		*/
		return "";
	}
	public function __post($post){
		$this->title = sprintf("<a href='%s'>%s</a>", get_permalink($post->ID), $post->post_title);
	}
	public function __meta($meta_key){
		return get_post_meta($this->p_id, $meta_key, true);
	}
	public function a_meta($meta_key){
		return !empty($this->author)? get_user_meta($this->author->ID, $meta_key, true):null;
	}
	public function __excerpt($length=120, $html=false){
		$javo__excerpt = javo_str_cut(strip_tags($this->post->post_content), (int)$length);
		if($html){
			$javo__excerpt = sprintf('<a href="%s" target="_self">%s</a>'
				, get_permalink($this->post->ID)
				, $javo__excerpt
			);
		};
		return $javo__excerpt;
	}
	public function get_avatar($strip_img=false){
		$avatar_id = $this->a_meta("avatar");
		$avatar_meta = wp_get_attachment_image_src($avatar_id, "javo-avatar");
		$output = sprintf("<img src='%s'>", $avatar_meta[0]);
		return ($strip_img == false)? $output : $avatar_meta[0];
	}
	public function in_features($type=0){
		global $javo_tso;
		if($javo_tso->get('show_unset_features') != 'hidden'){
			$terms = get_terms("item_amenities", Array("hide_empty"=> false));
		}else{
			$terms = wp_get_post_terms($this->post->ID, "item_amenities");
		}
		$output = "";

		switch($type){
			case 1:
				foreach($terms as $term)
				$output .= sprintf("<p class='features %s'><i class='icon'></i> %s</p>"
					, ((has_term($term->term_id, "item_amenities", $this->post))? "active" :"" )
					, $term->name
				);
			break;
			case 4:
				foreach($terms as $term)
				$output .= sprintf("<p class='col-md-3 features %s'><i class='icon'></i> %s</p>"
					, ((has_term($term->term_id, "item_amenities", $this->post))? "active" :"" )
					, $term->name
				);
				$output = sprintf("<div class='row'>%s</div>", $output);
			break;
			case 0:
			default:
				$i = 0;
				foreach($terms as $term){
					$output .= sprintf("<p class='col-md-4 features %s'><i class='icon'></i> %s</p>"
						, ((has_term($term->term_id, "item_amenities", $this->post))? " active":"" )
						, $term->name
					);
				};
			break;
		}
		return sprintf('<div class="row">%s</div>', $output);
	}
	public function get_author_item_count(){
		$args = Array();
		if(!empty($this->author)){
			$args = Array(
				'author'=> $this->author->ID
				, 'post_type'=> 'item'
				, 'post_status'=> 'publish'
				, 'posts_per_page'=> -1
			);
		};
		return count(get_posts($args));
	}
	public function author_sns($sns=NULL, $html=true){
		if($sns == NULL ) return;

		$origin_sns = Array(
			'twitter'=> sprintf('http://twitter.com/%s', $this->a_meta('twitter'))
			, 'facebook'=> sprintf('http://facebook.com/%s', $this->a_meta('facebook'))
		);
		switch($sns){
			case 'twitter':
				return $html ? sprintf('<a href="%s" target="_blank">%s</a>'
					, $origin_sns['twitter']
					, $this->a_meta('twitter')) : $origin_sns['twitter'];
			break;
			case 'facebook':
				return $html ? sprintf('<a href="%s" target="_blank">%s</a>'
					, $origin_sns['facebook']
					, $this->a_meta('facebook')) : $origin_sns['facebook'];
			break;
			default:
				return __('not found information.', 'javo_fr');
		};
	}
	public function __cate($tax_name, $default=null, $just=false){
		$terms = wp_get_post_terms($this->post->ID, $tax_name);
		if($terms != NULL){
			$output = "";
			if(!$just){
				foreach($terms as $item) $output .= $item->name.", ";
				return substr(trim($output), 0, -1);
			}else{
				return $terms[0]->name;
			};
		};
		return false;
	}
};