<?php
/*
    Template Name: Logged-In Users Page
 */ 
 if(!defined('ABSPATH')){ exit; }
get_header();
?>
<div class="container" style="width:1368px">
<?php if(have_posts()): the_post();
	$post_id = get_the_ID();
	$javo_sidebar_option = get_post_meta($post_id, "javo_sidebar_type", true);
	switch($javo_sidebar_option){
		case "left":?>
		<div class="row">
			<?php get_sidebar();?>
			<div class="col-md-9 pp-single-content">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
		</div>
		<?php break; case "full":?>
		<div class="row">
			<div class="col-md-12">
			<?php echo do_shortcode('[cfdb-json form="Contact form Professional" var="myJsVar" show="Nonprofit,Real_Estate_Professional,Property_Seller,Property_Buyer"  filter="Submitted Login='.wp_get_current_user()->user_login.'"]'); ?>
			<!--Javo start -->
				<div class="jv-my-page">
					<div class="row top-row">
						<div class="col-md-12">
							<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
						</div> <!-- col-12 -->
					</div> <!-- top-row -->
					<div class="container secont-container-content">
						<div class="row row-offcanvas row-offcanvas-left">
							<!--Sidebar-->
							<div class="col-xs-6 col-sm-2 sidebar-offcanvas main-content-left my-page-nav" id="sidebar" role="navigation">
					<p class="visible-xs">
						<button type="button" class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas">
							<i class="glyphicon glyphicon-chevron-left"><?php _e('Close', 'javo_fr');?></i>
						</button>
					</p>
					<div class="well sidebar-nav mypage-left-menu">		
						<ul class="nav nav-sidebar">
							<li class="titles profile"><?php _e('PROFILE', 'javo_fr');?></li>
							<!-- Profile -->
							<li class="side-menu home" id="sidebarvalues">
								<a href="/registration-personal/">
								<i class="glyphicon glyphicon-cog"></i> &nbsp;Personal</a>
								<a href="/registration-professional/">
								<i class="glyphicon glyphicon-cog"></i> &nbsp;Professional</a>
								<a href="/membership/" id="need_to_remove">
								<i class="glyphicon glyphicon-cog"></i> &nbsp;Membership</a>
							</li>
							<!-- Profile -->
						</ul>						
					</div><!--/.well -->
				</div><!--/col-xs--><!--Javo end -->				
				
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
		</div>
		<?php break; case "right": default:?>
		<div class="row">
			<div class="col-md-9 pp-single-content">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>
			<?php get_sidebar();?>
		</div>
	<?php }; ?>
<?php endif; ?>
</div>
<script type="text/javascript">
 jQuery(document ).ready(function() {	
<?php 
$slug = basename(get_permalink());
?>
var obj = myJsVar;
 if( "<?php echo $slug;?>" == "registration_service")
 {
		jQuery('body').find('[id="need_to_remove"]').remove();
		if (obj[0]['Nonprofit']=='Nonprofit')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;SERVICE PROVIDER </a>'); 
		}
		if (obj[0]['Real_Estate_Professional']=='Real Estate Professional')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;REAL ESTATE </a>'); 
		}
		if (obj[0]['Property_Seller']=='Property Seller')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY SELLER </a>'); 
		}
		if (obj[0]['Property_Buyer']=='Property Buyer')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY BUYER </a>'); 
		}
		jQuery('body').find('[name="Address"]').attr("disabled", "disabled"); 
		jQuery('body').find('[name="Phone"]').attr("disabled", "disabled"); 
		jQuery('body').find('[name="LicenseNo"]').attr("disabled", "disabled"); 
		jQuery('body').find('[name="chk_address[]"]').click(function(){
			if(jQuery('body').find('[name="chk_address[]"]').is(':checked'))
			{
				 jQuery('body').find('[name="Address"]').removeAttr("disabled"); 
			}
			else
			{
				jQuery('body').find('[name="Address"]').attr("disabled", "disabled"); 
			}
			
		});
		jQuery('body').find('[name="chk_phone[]"]').click(function(){
			if(jQuery('body').find('[name="chk_phone[]"]').is(':checked'))
			{
				 jQuery('body').find('[name="Phone"]').removeAttr("disabled"); 
			}
			else
			{
				jQuery('body').find('[name="Phone"]').attr("disabled", "disabled"); 
			}
			
		});
		jQuery('body').find('[name="chk_license[]"]').click(function(){
			if(jQuery('body').find('[name="chk_license[]"]').is(':checked'))
			{
				 jQuery('body').find('[name="LicenseNo"]').removeAttr("disabled"); 
			}
			else
			{
				jQuery('body').find('[name="LicenseNo"]').attr("disabled", "disabled"); 
			}
			
		}); 		
		if (obj[0]['Real_Estate_Professional']!='Real Estate Professional' && obj[0]['Property_Seller']!='Property Seller' && obj[0]['Property_Buyer']!='Property Buyer')
		{	
		jQuery('body').find('[value="Next >>"]').attr('value', 'Finish'); 
		}
		
}		
	if( "<?php echo $slug;?>" == "registration_realestate")
	{
		jQuery('body').find('[id="need_to_remove"]').remove();
		if (obj[0]['Nonprofit']=='Nonprofit')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;SERVICE PROVIDER </a>'); 
		}
		if (obj[0]['Real_Estate_Professional']=='Real Estate Professional')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;REAL ESTATE </a>'); 
		}
		if (obj[0]['Property_Seller']=='Property Seller')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY SELLER </a>'); 
		}
		if (obj[0]['Property_Buyer']=='Property Buyer')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY BUYER </a>'); 
		}
		jQuery('body').find('[name="Phone"]').attr("disabled", "disabled"); 
		jQuery('body').find('[name="chk_phone[]"]').click(function(){
			if(jQuery('body').find('[name="chk_phone[]"]').is(':checked'))
			{
				 jQuery('body').find('[name="Phone"]').removeAttr("disabled"); 
			}
			else
			{
				jQuery('body').find('[name="Phone"]').attr("disabled", "disabled"); 
			}
			
		});
		if (obj[0]['Property_Seller']!='Property Seller' && obj[0]['Property_Buyer']!='Property Buyer')
		{	
		jQuery('body').find('[value="Next >>"]').attr('value', 'Finish'); 
		}
					
	}
	if( "<?php echo $slug;?>" == "registration_propertyseller")
	{
		jQuery('body').find('[id="need_to_remove"]').remove();
		if (obj[0]['Nonprofit']=='Nonprofit')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;SERVICE PROVIDER </a>'); 
		}
		if (obj[0]['Real_Estate_Professional']=='Real Estate Professional')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;REAL ESTATE </a>'); 
		}
		if (obj[0]['Property_Seller']=='Property Seller')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY SELLER </a>'); 
		}
		if (obj[0]['Property_Buyer']=='Property Buyer')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY BUYER </a>'); 
		}
		else
		{
			jQuery('body').find('[value="Next >>"]').attr('value', 'Finish');
		}
		
	}	
	if( "<?php echo $slug;?>" == "registration_propertybuyer")
	{
		jQuery('body').find('[id="need_to_remove"]').remove();
		if (obj[0]['Nonprofit']=='Nonprofit')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;SERVICE PROVIDER </a>'); 
		}
		if (obj[0]['Real_Estate_Professional']=='Real Estate Professional')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;REAL ESTATE </a>'); 
		}
		if (obj[0]['Property_Seller']=='Property Seller')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY SELLER </a>'); 
		}
		if (obj[0]['Property_Buyer']=='Property Buyer')
		{	
		jQuery('body').find('[id="sidebarvalues"]').append('<a href="#"><i class="glyphicon glyphicon-cog"></i> &nbsp;PROPERTY BUYER </a>'); 
		}
		jQuery('body').find('[value="Next >>"]').attr('value', 'Finish');
	}		
		});	
    
function controller()
{	
<?php 
$slug = basename(get_permalink());
?>
//if (typeof jQuery != 'undefined') {
//	alert("yes");
//}
			jQuery('body').find('[value="Next >>"]').button('loading');			
			//$this.find('[type="submit"]').button('reset');
	if ( "<?php echo $slug;?>" == "registration-personal")
	{
				document.location.href='<?php the_permalink() ?>registration-professional/';
	}
	else if( "<?php echo $slug;?>" == "registration-professional")
	{		
		if(jQuery('body').find('[value="Nonprofit"]').is(':checked'))
		{
			document.location.href='<?php the_permalink() ?>registration_service/';
		}
		else if(jQuery('body').find('[value="Real Estate Professional"]').is(':checked'))
		{
			document.location.href='<?php the_permalink() ?>registration_realestate/';
		}
		else if(jQuery('body').find('[value="Property Seller"]').is(':checked'))
		{
			document.location.href='<?php the_permalink() ?>registration_propertyseller/';
		}
		else if(jQuery('body').find('[value="Property Buyer"]').is(':checked'))
		{
			document.location.href='<?php the_permalink() ?>registration_propertybuyer/';
		}
		//jQuery.('.wpcf7-form').addClass('invalid');
	}
	else if( "<?php echo $slug;?>" == "registration_service")
	{		
		var obj = myJsVar;
		//alert(obj.name === "Nonprofit");
		if (obj[0]['Real_Estate_Professional']=='Real Estate Professional')
		{
			document.location.href='<?php the_permalink() ?>registration_realestate/';
		}
		else if (obj[0]['Property_Seller']=='Property Seller')
		{
			document.location.href='<?php the_permalink() ?>registration_propertyseller/';
		}
		else if (obj[0]['Property_Buyer']=='Property Buyer')
		{
			document.location.href='<?php the_permalink() ?>registration_propertybuyer/';
		}
		//alert(myJsVar)
		//jQuery.('.wpcf7-form').addClass('invalid');
	}
	else if( "<?php echo $slug;?>" == "registration_realestate")
	{			
		var obj = myJsVar;
		//alert(obj.name === "Nonprofit");
		if (obj[0]['Property_Seller']=='Property Seller')
		{
			document.location.href='<?php the_permalink() ?>registration_propertyseller/';
		}
		else if (obj[0]['Property_Buyer']=='Property Buyer')
		{
			document.location.href='<?php the_permalink() ?>registration_propertybuyer/';
		}
		else
		{		
			document.location.href='<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/').JAVO_MEMBER_SLUG; ?>';	
		}
	}
	else if( "<?php echo $slug;?>" == "registration_propertyseller")		
	{			
		var obj = myJsVar;
		//alert(obj.name === "Nonprofit");
		if (obj[0]['Property_Buyer']=='Property Buyer')
		{
			document.location.href='<?php the_permalink() ?>registration_propertybuyer/';
		}
		else
		{		
			document.location.href='<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/').JAVO_MEMBER_SLUG; ?>';	
		}
	}
	else if( "<?php echo $slug;?>" == "registration_propertybuyer")		
	{		
		document.location.href='<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/').JAVO_MEMBER_SLUG; ?>';	
	}
	else
	{		
		document.location.href='<?php echo home_url(JAVO_DEF_LANG.JAVO_MEMBER_SLUG.'/'.wp_get_current_user()->user_login.'/').JAVO_MEMBER_SLUG; ?>';	
	}
	
}

</script>
<?php get_footer();?>