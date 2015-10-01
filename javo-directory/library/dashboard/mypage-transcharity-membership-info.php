<?php
/**
***	My Page Settings Page
***/
require_once 'mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-page-change-password">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->
<div class='chooseOverlay'></div>
	<div class="container secont-container-content">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/sidebar', 'menu');?>
			<div class="col-xs-12 col-sm-10 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap">
							<div class="panel-heading">
								<p class="pull-left visible-xs">
									<button class="btn btn-primary btn-xs" data-toggle="mypage-offcanvas"><?php _e('My Page Menu', 'javo_fr'); ?></button>
								</p> <!-- offcanvas button -->
								<div class="row">
									<div class="col-md-11 my-page-title">
									TRANSCHARITY MEMBERSHIP INFO
									</div> <!-- my-page-title -->

									<div class="col-md-1">
										<p class="text-center"><a href="#full-mode" class="toggle-full-mode"><i class="fa fa-arrows-alt"></i></a></p>
										<script type="text/javascript">
										(function($){
											"use strict";
											$('body').on('click', '.toggle-full-mode', function(){
												$('body').toggleClass('content-full-mode');
											});
										})(jQuery);
										</script>
									</div> <!-- my-page-title -->
								</div> <!-- row -->
							</div> <!-- panel-heading -->

							<div class="panel-body" style='padding:10px !important'>
							<!-- Starting Content -->
								<div class="row">
									<div class="col-md-12">
<h1 class='impact_header'>BUY TIER MEMBERSHIP</h1>
<?php
//Collect the paypal array;

$paypal=array();
$live="https://www.paypal.com/cgi-bin/webscr";
$sandbox="https://www.sandbox.paypal.com/cgi-bin/webscr";

$paypal['actionUrl']=(get_option('transcharity_transaction_mode')=='live')?$live:$sandbox;
$paypal['businessEmail']=get_option('transcharity_paypal_email');
$paypal['returnUrl']=get_option('siteurl')."/member/".wp_get_current_user()->user_login."/transcharity-membership-info/?payment=success";
$paypal['cancelUrl']=get_option('siteurl')."/member/".wp_get_current_user()->user_login;
$paypal['itemDescription']=get_option('transcharity_transaction_description');

?>
<script>

var fan="<img style='position: relative; left: 40%; top: 40%;' src='http://transcharity.com/wp-content/plugins/transcharity-tier-membership/ajax-fan.gif'>";
function closeOverlay(){
	jQuery('.chooseDiv,.chooseOverlay,.closeButtonTcP').fadeOut();
	jQuery('.chooseDiv').html(fan);
	}
function choosePassionLocation(){
	// We have to go for a ajax call
	var item_number=jQuery("[name='item_number']").val();

	if(item_number)
	 return true;

		jQuery('.chooseDiv,.chooseOverlay').fadeIn();
		jQuery('.chooseDiv').html(fan);

		ajaxUrl="<?php get_option('siteurl'); ?>/wp-admin/admin-ajax.php";

	  var plan_choosen=jQuery("[name='transcharity_tier_membership']:checked").val();
								jQuery.ajax({
								   url: ajaxUrl,
							     type:'POST',
									 data:{'plan_type':plan_choosen,'action':'getPassionLocationFormBuilder'},
								   success: function(data) {
							     jQuery('.chooseDiv').html(data);
									 jQuery('.closeButtonTcP').fadeIn();
								   },
									 error: function() {
								      jQuery('.chooseDiv').html('<p style="color:red">An error has occurred, Please try again later !</p>');
                      jQuery('.closeButtonTcP').fadeIn();
									 }
								});

return false;

}

function confirmSelection(){
countChecking=keepCount();
console.log(countChecking);
if(countChecking==false){
	return false;
}

var serializedData=jQuery('form').serialize();
serializedData=serializedData.split('action').join('dummy_not_required');
serializedData=serializedData+"&action=confirmSelection";
							jQuery.ajax({
								 url: ajaxUrl,
								 type:'POST',
								 data:serializedData,
								 success: function(data) {
								 json_data=JSON.parse(data);
								 jQuery('.madeSelection').html(json_data.text_to_show);
								 jQuery("[name='item_number'],[name='custom']").val(json_data.encrypted_data);
								 jQuery("[name='item_name_1']").val(json_data.planDescription);
								 jQuery("[name='amount_1']").val(json_data.price);
								 closeOverlay();
								 jQuery('.butt1').hide();
								 jQuery('.butt2').show();
								 },
								 error: function() {
										jQuery('.chooseDiv').html('<p style="color:red">An error has occurred, Please try again later !</p>');
								 }
							});

}

function keepCount(){

  var plan_choosen=jQuery("[name='transcharity_tier_membership']:checked").val();
	var passionCount=parseInt(jQuery("[name='select_transcharity_passion[]']:checked").length);
	var locationCount=parseInt(jQuery("[name='select_transcharity_location[]']:checked").length);

	if(plan_choosen==1){
		if(passionCount!=1 || locationCount!=1){
		alert('You must select One passion & One location to proceed futher !');
		return false;
	  }
	}

	if(plan_choosen==2){
		if(passionCount!=3 || locationCount!=1){
		alert('You must select Three passion & One location to proceed futher !');
		return false;
	  }
	}

	if(plan_choosen==3){
		if(passionCount!=6 || locationCount!=1){
		alert('You have to select Six passion & One location to proceed futher !');
		return false;
	  }
	}

	if(plan_choosen==4){
		if(passionCount!=1){
		alert('You must select One passion to proceed futher !');
		return false;
	  }
	}

	if(plan_choosen==5){
		if(locationCount!=1){
		alert('You must select One location to proceed futher !');
		return false;
	  }
	}

   return true;
}
</script>
  <form action="<?php echo $paypal['actionUrl']; ?>" method="post" name="paypal_form" onsubmit="return choosePassionLocation();">
<div style='min-height:200px;width:90%;display:inline-block'>
    <label style='width:300px;display:inline-block;vertical-align:top;'>SELECT TIER MEMBERSHIP PLAN :</label><br>
    <div style='display:inline-block'>
    <input type='radio' required='required' name='transcharity_tier_membership' value='1'> <label style='display:inline-block;width:180px;'> TIER 1.</label> (<b>$125.00</b><i> - One Passion & One Location</i>)<br>
    <input type='radio' required='required' name='transcharity_tier_membership' value='2'> <label style='display:inline-block;width:180px;'> TIER 2.</label> (<b>$250.00</b><i> - Three Passion & One Location</i>)<br>
    <input type='radio' required='required' name='transcharity_tier_membership'  value='3'> <label style='display:inline-block;width:180px;'> TIER 3.</label> (<b>$400.00</b><i> - Six Passion & One Location</i>)<br>
    <input type='radio' required='required' name='transcharity_tier_membership' value='4'> <label style='display:inline-block;width:180px;'> Additional Passions.</label> (<b>$50.00</b><i> - Only One Passion</i>)<br>
    <input type='radio' required='required' name='transcharity_tier_membership' value='5'> <label style='display:inline-block;width:180px;'> Additional Locations.</label> (<b>$50.00</b><i> - Only One Location</i>)
  </div>

	<div class='madeSelection' style='display:inline-block;width:300px;vertical-align:top;margin-left:50px;'></div>

  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="upload" value="1">
  <input type="hidden" name="currency_code" value="<?php echo $paypal['businessEmail']; ?>">
  <input type="hidden" name="return" value="<?php echo $paypal['returnUrl']; ?>">
  <input type="hidden" name="cancel_return" value="<?php echo $paypal['cancelUrl']; ?>">
  <input type="hidden" name="rm" value="2">
  <input type="hidden" name="cbt" value= "Please Click Here to Finalize the Payment">
  <input type="hidden" name="business" value="<?php echo $paypal['businessEmail']; ?>">
  <input type="hidden" name="item_name_1" value="<?php echo $paypal['itemDescription']; ?>">
	<input type="hidden" name="item_number" value="">
	<input type='hidden' name='custom'>
  <input type="hidden" name="amount_1" value="200" required='required'>
  <input type="hidden" name="quantity_1" value="1" >
  <input type="hidden" name="no_shipping" value="1">
  <input type="hidden" name="image_url" value="http://www.transcharity.org/wp-content/uploads/2015/04/tc_header_logo1.png">
  <input type="hidden" value="PayPal"><br><br>

<a href='javascript:void(0)' class='closeButtonTcP' onclick='closeOverlay()'>X</a>
<div class='chooseDiv'></div>
 <input type='submit' style='margin-left:200px;' value='PROCESS' class='btn btn-warning butt1' >
  <input style='top:30px;position:relative;margin-left:200px;display:none' class='butt2' type="image" src='http://empiresweepstakesconvention.com/wp-content/uploads/2013/09/paypal-button.png' value='Submit Paymeny'>
<a href='' style='margin-left:10px' class='btn btn-danger'>CANCEL</a>
</form>
</div>


<h1 class='impact_header'>TRANSACTION HISTORY</h1>

<table width='100%' class='tth_table'>
<tr><th>Plan Type</th><th>Passion Earned</th><th>Location Earned</th>  <th>Status</th> <th>Paypal Transaction Id</th><th>Amount</th><th>Transaction Time</th></tr>
<?php

$user_id=wp_get_current_user()->ID;
$plan_types=array('1'=>'TIER-1',2=>'TIER-2',3=>'TIER-3',4=>'Additional Passions',5=>'Additional Locations');
$results=$wpdb->get_results("select * from transcharity_tier_membership WHERE user_id='$user_id'");
$pCounter=0;
foreach($results as $key):
  $plan_selected=$plan_types[$key->plan_type];
  $passion_earned=convertPassionsToText($key->passions);
  $location_earned=convertLocationsToText($key->locations);
  $paypal=unserialize(base64_decode($key->paypal_transaction_history));

  $status=$paypal['st'];
  $transactionId=$paypal['tx'];
  $transactionAmount=number_format($paypal['amt'],2) ." ".$paypal['cc'];
  $transactionTime=date("m/d/y h:i A",strtotime($key->created_on));
  echo "<tr><td>$plan_selected</td><td>$passion_earned</td><td>$location_earned</td><td>$status</td><td>$transactionId</td><td>$transactionAmount</td><td>$transactionTime</td></tr>";
$pCounter++;
endforeach;
if(!$pCounter)
echo "<tr><td colspan='7' style='text-align:center'><font color='red'>No Plans has been taken by you so far !</font></td></tr>";
 ?>
</table>






									</div><!-- col-md-offset-1 -->
								</div><!-- Row -->


							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php get_footer();
?>
<style>
.jv-my-page-change-password .panel-body form > div{
margin:20px !important;
}

.impact_header{
  background: steelblue none repeat scroll 0 0;
    border: 1px none;
    border-radius: 4px;
    box-shadow: 0 0 5px black;
    color: white;
    font-family: Times New Roman !important;
    padding: 10px;
}

.tth_table th,.tth_table td{
  font-size:12px;
  padding:10px;
  border:1px solid lightgrey;
}

.tth_table th{
  background:black;
  color:white;
}

.tth_table{
  margin-bottom: 300px;
}

.chooseDiv{
width: 500px;
height: 310px;
border: 15px solid black !important;
background: white;
z-index: 100001;
display: none;
border-radius: 20px;
top: 20%;
left: 30%;
position: fixed;
padding: 30px;
padding-top: 10px;
display:none;
line-height: 16px;
}

.chooseOverlay{
	background: black;
opacity: 0.7;
top: 0px;
left:0px;
height: 100%;
width: 100%;
position: absolute;
display: none;
z-index:100000;
}

.closeButtonTcP{
	background: white none repeat scroll 0 0;
    border-radius: 50%;
    color: red;
    display: none;
    left: 67%;
    padding: 6px 14px;
    position: fixed;
    top: 18%;
    z-index: 1000003;
}

.select_transcharity_passions,.select_transcharity_locations{
	width: 200px;
	display: inline-block;
	vertical-align: top;
}
</style>
