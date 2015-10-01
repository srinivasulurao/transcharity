<?php
/*
Plugin Name: Transcharity Tier Membership
Plugin URI: http://www.appddictionstudio.com
Description: Sync your WordPress users and members with MailChimp lists.
Version: 1.0.6
Author: N.Srinivasulu Rao
Author URI: http://srinivasulu-rao.branded.me
*/
/*
	Copyright 2011	Stranger Studios	(email : n.srinivasulurao@gmail.com)
	GPLv2 Licence
*/

add_action('wp_login','redirect_to_profile',$user);
//This is a very important step for us to implement.
function redirect_to_profile($user){
header("Location:".get_site_url()."/member/$user");
exit;
}


function transcharity_tier_membership_settings_page() {

	add_menu_page( 'Transcharity Tier Membership Settings', 'Transcharity', 'manage_options', 'transcharity-tier-membership-settings', 'ttmsp', plugins_url( 'transcharity-tier-membership/paypal.png' ), 6 );
}

add_action('admin_menu','transcharity_tier_membership_settings_page');
function debug($ArrayObject)
{
  echo "<pre>";
  print_r($ArrayObject);
  echo '</pre>';
}


function ttmsp(){
  if($_POST['submit_transcharity_t_mem']):
    update_option('transcharity_paypal_email',$_POST['transcharity_paypal_email']);
    update_option('transcharity_paypal_password',$_POST['transcharity_paypal_password']);
    update_option('transcharity_transaction_mode',$_POST['transcharity_transaction_mode']);
    update_option('transcharity_transaction_description',$_POST['transcharity_transaction_description']);
  endif;

  $transcharity_paypal_email=get_option('transcharity_paypal_email');
  $transcharity_paypal_password=get_option('transcharity_paypal_password');
  $transcharity_transaction_mode=get_option('transcharity_transaction_mode');
  $transcharity_transaction_description=get_option('transcharity_transaction_description');
  $mode_sandbox=($transcharity_transaction_mode=='sandbox')?"checked='checked'":"";
  $mode_live=($transcharity_transaction_mode=='live')?"checked='checked'":"";

    echo <<<xyz
    <h1>Transcharity Tier Membership Settings</h1><hr>
    <form method='post' action=''>
    <table>
    <tr><td class='label'>Paypal Email:</td><td> <input type='text' value='$transcharity_paypal_email' name='transcharity_paypal_email' style='width:300px'></td></tr>
    <tr><td class='label'>Paypal Password:</td><td> <input type='text' value='$transcharity_paypal_password' name='transcharity_paypal_password' style='width:300px'> </td></tr>
    <tr><td class='label'>Transaction Mode:</td><td><input type='radio' $mode_sandbox name='transcharity_transaction_mode'  value='sandbox'> Sandbox <input name='transcharity_transaction_mode' $mode_live type='radio' value='live'> Live</td></tr>
    <tr><td class='label'>Description:</td><td> <input type='text' value='$transcharity_transaction_description' name='transcharity_transaction_description' style='width:300px'></td></tr>
    <tr><td colspan='2' style='text-align:center'><br><input class='button button-primary button-large' type='submit' name='submit_transcharity_t_mem' value='SAVE'></td></tr>
    </table>
    </form>
    <style>
    .label{
      width:150px;
    }
    </style>
xyz;
}

add_action('init','add_transaction_details');

function add_transaction_details(){
  global $wpdb;
  $user_id=wp_get_current_user()->ID;
  //echo $user_id;
  if($_REQUEST['tx']): // this would be indication from the
    $paypal_result=base64_encode(serialize($_REQUEST)); // Reverse Mechanism to retrieve the result.
	  $user_plan_information=unserialize(base64_decode($_REQUEST['cm']));

    $plan_type=$user_plan_information['plan_type'];
		$passions=$user_plan_information['passions'];
		$locations=$user_plan_information['locations'];
    $now=date("Y-m-d H:i:s",time());
    $wpdb=$wpdb->query("INSERT INTO transcharity_tier_membership SET user_id='$user_id',passions='$passions',locations='$locations', paypal_transaction_history='$paypal_result',created_on='$now',plan_type='$plan_type'");
    header("Location:".get_option('siteurl')."/member/".wp_get_current_user()->user_login."/transcharity-membership-info");
    exit;
  endif;
}

function getUserPassions(){
  global $wpdb;
  $user_id=wp_get_current_user()->ID;
  $results=$wpdb->get_results("select passions from transcharity_tier_membership WHERE user_id='$user_id'");
  $passions="";
  foreach($results as $key):
    $passions.=($key->passions)?$key->passions.",":"";
  endforeach;
  $passions=rtrim($passions,",");
  $passions=explode(",",$passions);
  return $passions;
}

function getUserLocations(){
  global $wpdb;
  $user_id=wp_get_current_user()->ID;
  $results=$wpdb->get_results("select locations from transcharity_tier_membership WHERE user_id='$user_id'");
  $locations="";
  foreach($results as $key):
    $locations.=($key->locations)?$key->locations.",":"";
  endforeach;
  $locations=rtrim($locations,",");
  $locations=explode(",",$locations);
  return $locations;
}

function convertPassionsToText($passions){
  global $wpdb;
  $passionsToText=array();
  $passions=@explode(",",$passions);
  foreach($passions as $value):
  $result=$wpdb->get_results("Select a.name FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE a.term_id='$value' AND taxonomy='item_category'");
  $passionsToText[]=$result[0]->name;
  endforeach;
  return @implode(", ",$passionsToText);
}

function convertLocationsToText($locations){
  global $wpdb;
  $locationsToText=array();
  $locations=@explode(",",$locations);
  foreach($locations as $value):
  $result=$wpdb->get_results("Select a.name FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE a.term_id='$value' AND taxonomy='item_location'");
  $locationsToText[]=$result[0]->name;
  endforeach;
  return @implode(", ",$locationsToText);
}

add_action('wp_ajax_confirmSelection','confirmSelection');

function getPlanPrice($plan_type){
	$plan=array();
	$plan[1]=125;
	$plan[2]=250;
	$plan[3]=400;
	$plan[4]=50;
	$plan[5]=50;
	return $plan[$plan_type];
}

function getPlanDescription($plan_type){
	$planDescription=array();
  $planDescription[1]="Transcharity TIER-1 Plan";
	$planDescription[2]="Transcharity TIER-2 Plan";
	$planDescription[3]="Transcharity TIER-3 Plan";
	$planDescription[4]="Transcharity Additional Passion Plan";
	$planDescription[5]="Transcharity Additional Location Plan";
	return $planDescription[$plan_type];
}

function confirmSelection(){
	$passions=@implode(",",$_POST['select_transcharity_passion']);
	$locations=@implode(",",$_POST['select_transcharity_location']);

	$data['passions']=$passions;
	$data['locations']=$locations;
	$data['plan_type']=$_POST['transcharity_tier_membership'];

	$html="";
  if($passions)
	$html.="<b>Added Passions</b> - <br>".convertPassionsToText($passions)."<br>";
	if($locations)
	$html.="<b>Added Locations</b> - <br>".convertLocationsToText($locations);

	$return=array();
	$return['encrypted_data']=str_replace("=","",base64_encode(serialize($data)));
	$return['text_to_show']=$html;
	$return['price']=getPlanPrice($_POST['transcharity_tier_membership']);
	$return['planDescription']=getPlanDescription($_POST['transcharity_tier_membership']);
echo json_encode($return);
exit;
}
add_action('wp_ajax_getPassionLocationFormBuilder','getPassionLocationFormBuilder');

function getPassionLocationFormBuilder()
{
	sleep(3);// give a three second delay, to show the loader, makes the system steady & ready for the execution.
$value=$_REQUEST['plan_type'];

      switch ($value) {
        case 1:
          echo @tierOne();
          break;
        case 2:
          echo @tierTwo();
          break;
        case 3:
          echo @tierThree();
          break;
        case 4:
          echo @additionalPassion();
          break;
        case 5:
          echo @additionalLocation();
          break;
        default:
          echo "Unknown value found, unable to select the passion or location";
      }
			echo "<div style='text-align:center;display:block;'><input onclick='confirmSelection()' type='button' class='btn btn-warning' value='CONFIRM SELECTION'></div>";
			exit;
}

function tierOne(){
  global $wpdb;
    $passions=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_category'");

    $html="<div class='select_transcharity_passions' style='padding:10px'><b><u>Select Passion</u></b><br>";
    foreach($passions as $passion):
		if(!in_array($passion->term_id,getUserPassions()))
    $html.="<input type='radio' required='required' name='select_transcharity_passion[]' value='{$passion->term_id}'> $passion->name <br>";
    endforeach;
    $html.="</div>";

    $locations=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_location'");
    $html.="<div class='select_transcharity_locations' style='padding:10px'><b><u>Select Location</u></b><br>";
    foreach($locations as $location):
		if(!in_array($location->term_id,getUserLocations()))
    $html.="<input type='radio' required='required' name='select_transcharity_location[]' value='{$location->term_id}'> $location->name <br>";
    endforeach;
    $html.="</div>";
  return $html;
}

function tiertwo(){
  global $wpdb;
    $passions=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_category'");

    $html="<div class='select_transcharity_passions' style='padding:10px'><b><u>Select Passion</u></b><br>";
    foreach($passions as $passion):
		if(!in_array($passion->term_id,getUserPassions()))
    $html.="<input type='checkbox' required='required' name='select_transcharity_passion[]'  value='{$passion->term_id}'> $passion->name <br>";
    endforeach;
    $html.="</div>";

    $locations=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_location'");
    $html.="<div class='select_transcharity_locations' style='padding:10px'><b><u>Select Location</u></b><br>";
    foreach($locations as $location):
		if(!in_array($location->term_id,getUserLocations()))
    $html.="<input type='radio' required='required' name='select_transcharity_location[]' value='{$location->term_id}'> $location->name <br>";
    endforeach;
    $html.="</div>";
  return $html;
}

function tierThree(){
  global $wpdb;
    $passions=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_category'");

    $html="<div class='select_transcharity_passions' style='padding:10px'><b><u>Select Passion</u></b><br>";
    foreach($passions as $passion):
		if(!in_array($passion->term_id,getUserPassions()))
    $html.="<input type='checkbox' required='required' name='select_transcharity_passion[]'  value='{$passion->term_id}'> $passion->name <br>";
    endforeach;
    $html.="</div>";

    $locations=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_location'");
    $html.="<div class='select_transcharity_locations' style='padding:10px'><b><u>Select Location</u></b><br>";
    foreach($locations as $location):
		if(!in_array($location->term_id,getUserLocations()))
    $html.="<input type='radio' required='required' name='select_transcharity_location[]' value='{$location->term_id}'> $location->name <br>";
    endforeach;
    $html.="</div>";
  return $html;
}

function additionalPassion(){
  global $wpdb;
    $passions=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_category'");

    $html="<div class='select_transcharity_passions' style='padding:10px'><b><u>Select Passion</u></b><br>";
    foreach($passions as $passion):
		if(!in_array($passion->term_id,getUserPassions()))
    $html.="<input type='radio' required='required'  name='select_transcharity_passion[]' value='{$passion->term_id}'> $passion->name <br>";
    endforeach;
    $html.="</div>";
  return $html;
}

function additionalLocation(){

  global $wpdb;
  $locations=$wpdb->get_results("Select a.name,a.term_id FROM wp_terms as a INNER JOIN wp_term_taxonomy as b ON a.term_id=b.term_taxonomy_id WHERE  b.taxonomy='item_location'");
  $html="<div class='select_transcharity_locations' style='padding:10px'><b><u>Select Location</u></b><br>";
  foreach($locations as $location):
	if(!in_array($location->term_id,getUserLocations()))
  $html.="<input type='radio' required='required' name='select_transcharity_location[]' value='{$location->term_id}'> $location->name <br>";
  endforeach;
  $html.="</div>";

return $html;

}




?>
