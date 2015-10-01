<?php
/**
 * Javo Themes functions and definitions *
 *
 * @package WordPress
 * @subpackage Javo_Directory
 * @since Javo Themes 1.0
 */

 // Path Initialize
$javo_appPath = pathinfo(__FILE__);
$javo_site_url = get_site_url();
define("JAVO_APP_PATH", $javo_appPath['dirname']);			// Get Theme Folder URL : hosting absolute path
define("JAVO_SITE_URL", $javo_site_url);
define("JAVO_THEME_DIR", get_template_directory_uri());		// Get http URL : ex) http://www.abc.com/
define("JAVO_SYS_DIR", JAVO_APP_PATH."/library");			// Get Library path
define("JAVO_TP_DIR", JAVO_APP_PATH."/templates");			// Get Tempate folder
define("JAVO_ADM_DIR", JAVO_SYS_DIR."/admin");				// Administrator Page
define("JAVO_SCS_DIR", JAVO_SYS_DIR."/shortcodes");			// Shortcodes folder
define("JAVO_IMG_DIR", JAVO_THEME_DIR."/assets/images");	// Images folder
define("JAVO_WG_DIR", JAVO_SYS_DIR."/widgets");				// Widgets Folder
define("JAVO_HDR_DIR", JAVO_SYS_DIR."/header");				// Get Headers
define("JAVO_CLS_DIR", JAVO_SYS_DIR."/classes");			// Classes
define("JAVO_DSB_DIR", JAVO_SYS_DIR."/dashboard");			// Dash Board
define("JAVO_FUC_DIR", JAVO_SYS_DIR."/functions");			// Functions
define("JAVO_PLG_DIR", JAVO_SYS_DIR."/plugins");		// Plugin folder

// Includes : Basic or default functions and included files
require_once JAVO_SYS_DIR."/define.php";					// defines
require_once JAVO_SYS_DIR."/load.php";						// loading functions, classes, shotcode, widgets
require_once JAVO_SYS_DIR."/enqueue.php";					// enqueue js, css
require_once JAVO_SYS_DIR."/wp_init.php";					// post-types, taxonomies
require_once JAVO_ADM_DIR."/theme-settings.php";			// theme options
require_once JAVO_ADM_DIR."/meta-options.php";				// theme screen options tab.
require_once JAVO_DSB_DIR."/functions.php";					// Membership Dashboard

function create_user_from_registration($cfdata) {
    if (!isset($cfdata->posted_data) && class_exists('WPCF7_Submission')) {
        // Contact Form 7 version 3.9 removed $cfdata->posted_data and now
        // we have to retrieve it from an API
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $formdata = $submission->get_posted_data();
        }
    } elseif (isset($cfdata->posted_data)) {
        // For pre-3.9 versions of Contact Form 7
        $formdata = $cfdata->posted_data;
    } else {
        // We can't retrieve the form data
        return $cfdata;
    }
    // Check this is the user registration form
    if ( $cfdata->title() == 'Your Registration Form Title') {
        $password = wp_generate_password( 12, false );
        $email = $formdata['form-email-field'];
        $name = $formdata['form-name-field'];
        // Construct a username from the user's name
        $username = strtolower(str_replace(' ', '', $name));
        $name_parts = explode(' ',$name);
        if ( !email_exists( $email ) ) {
            // Find an unused username
            $username_tocheck = $username;
            $i = 1;
            while ( username_exists( $username_tocheck ) ) {
                $username_tocheck = $username . $i++;
            }
            $username = $username_tocheck;
            // Create the user
            $userdata = array(
                'user_login' => $username,
                'user_pass' => $password,
                'user_email' => $email,
                'nickname' => reset($name_parts),
                'display_name' => $name,
                'first_name' => reset($name_parts),
                'last_name' => end($name_parts),
                'role' => 'subscriber'
            );
            $user_id = wp_insert_user( $userdata );
            if ( !is_wp_error($user_id) ) {
                // Email login details to user
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $message = "Welcome! Your login details are as follows:" . "\r\n";
                $message .= sprintf(__('Username: %s'), $username) . "\r\n";
                $message .= sprintf(__('Password: %s'), $password) . "\r\n";
                $message .= wp_login_url() . "\r\n";
                wp_mail($email, sprintf(__('[%s] Your username and password'), $blogname), $message);
            }
        }
    }
    return $cfdata;
}
add_action('wpcf7_before_send_mail', 'create_user_from_registration', 1);


/* Custom code goes below this line. */