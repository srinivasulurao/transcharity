<?php
$javo_directory_query			= new javo_get_meta( get_the_ID() );
$javo_rating					= new javo_Rating( get_the_ID() );
global
	$javo_custom_field
	, $post
	, $javo_custom_item_label
	, $javo_custom_item_tab
	, $javo_tso;
$javo_this_author				= get_userdata($post->post_author);
$javo_this_author_avatar_id		= get_the_author_meta('avatar');
$javo_directory_query			= new javo_get_meta( get_the_ID() );
$javo_rating = new javo_Rating( get_the_ID() );

{
	$javo_detail_item_tabs			= Array(
		'about'						=> Array(
			'tab_id'				=> '#item-detail'
			, 'class'				=> 'active about-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-home'
			, 'label'				=> $javo_custom_item_label->get('about', __( "About Us", 'javo_fr' ) )
		)
	);

	// Location Tab
	if( $javo_custom_item_tab->get('location', '') == '' )
	{
		$javo_detail_item_tabs['location'] = Array(
			'tab_id'				=> '#item-location'
			, 'class'				=> 'location-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-map-marker'
			, 'label'				=> $javo_custom_item_label->get('location', __( "Location", 'javo_fr' ) )
		);
	}

	// Events Tab
	if( $javo_custom_item_tab->get('events', '') == '' )
	{
		$javo_detail_item_tabs['events'] = Array(
			'tab_id'				=> '#item-events'
			, 'class'				=> 'event-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-heart-empty'
			, 'label'				=> $javo_custom_item_label->get('events', __( "Event", 'javo_fr' ) )
		);
	}

	// Ratings Tab
	if( $javo_custom_item_tab->get('ratings', '') == '' )
	{
		$javo_detail_item_tabs['ratings'] = Array(
			'tab_id'				=> '#item-ratings'
			, 'class'				=> 'rating-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-star'
			, 'label'				=> $javo_custom_item_label->get('ratings', __( "Ratings", 'javo_fr' ) )
		);
	}

	// Reviews Tab
	if( $javo_custom_item_tab->get('reviews', '') == '' )
	{
		$javo_detail_item_tabs['reviews'] = Array(
			'tab_id'				=> '#item-reviews'
			, 'class'				=> 'review-tab-button'
			, 'icon'				=> 'glyphicon glyphicon-comment'
			, 'label'				=> $javo_custom_item_label->get('reviews', __( "Reviews", 'javo_fr' ) )
		);
	}
} ?>


<div class="tabs-wrap">
	<ul id="single-tabs" class="nav nav-pills nav-justified" data-tabs="single-tabs">

		<?php
		foreach( $javo_detail_item_tabs as $tabs )
		{
			echo "<li class=\"{$tabs['class']}\">";
				echo "<a href=\"{$tabs['tab_id']}\" data-toggle=\"tab\">";
					echo "<span class=\"{$tabs['icon']}\"></span> {$tabs['label']}";
				echo "</a>";
			echo "</li>";

		} ?>		
	</ul>

    <div id="javo-single-tab" class="tab-content">
        <div class="tab-pane active" id="item-detail">
           	<?php get_template_part('templates/parts/part', 'single-detail-tab');?>
        </div>
		<?php if( $javo_custom_item_tab->get('location', '') == '' ): ?>
			<div class="tab-pane" id="item-location">
				<?php get_template_part('templates/parts/part', 'single-maps');?>
				<p>&nbsp;</p>
				<?php get_template_part('templates/parts/part', 'single-contact');?>
			</div>
		<?php endif; ?>

		<?php if( $javo_custom_item_tab->get('events', '') == '' ): ?>
			<div class="tab-pane" id="item-events">
				<?php get_template_part('templates/parts/part', 'single-events');?>
			</div>
		<?php endif; ?>

		<?php if( $javo_custom_item_tab->get('ratings', '') == '' ): ?>
			<div class="tab-pane" id="item-ratings">
				<?php get_template_part('templates/parts/part', 'single-ratings-tab');?>
			</div>
		<?php endif; ?>

		<?php if( $javo_custom_item_tab->get('reviews', '') == '' ): ?>
			<div class="tab-pane" id="item-reviews">
				<?php get_template_part('templates/parts/part', 'single-reviews');?>
			</div>
		<?php endif; ?>


    </div>
</div> <!-- tabs-wrap -->

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#single-tabs').tab();
		// link to specific single-tabs
		var hash = location.hash
		  , hashPieces = hash.split('?')
		  , activeTab = hashPieces[0] != '' ? $('[href=' + hashPieces[0] + ']') : null;
		activeTab && activeTab.tab('show');
    });
</script>