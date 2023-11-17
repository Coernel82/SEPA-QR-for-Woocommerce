<?php

/**
 * MXP SEPA-QR-Code for Woocommerce
 *
 * @package   muxp/sepaqr
 * @author    Bureau fuer Angelegenheiten 
 * @license   GPL-2.0+
 * @link      https://github.com/Coernel82/SEPA-QR-for-Woocommerce
 * @copyright 2022 BUFANG
 *
 * @wordpress-plugin
 * Plugin Name:       SEPA-QR-Code for Woocommerce
 * Plugin URI:        https://github.com/Coernel82/SEPA-QR-for-Woocommerce
 * Description:       Adds a SEPA QR code to the Woocommerce Thankyou page and to the confirmation e-mail. The QR code is generated locally due to privacy reasons and is using the IBAN and BIC of the first BACS account.
 * Version:           1.1.0
 * Author:            https://github.com/Coernel82/SEPA-QR-for-Woocommerce
 * Requires at least: 5.0
 * Requires PHP:	  7.0
 * Author URI:        https://github.com/Coernel82/SEPA-QR-for-Woocommerce
 * Text Domain:       mxp-sepa-qr-code-addon-for-woocommerce
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* config parameters */

define('muxp_QUERY_PARAM','muxp_muxp_qr'); // name of the query param (only needed if qr link is used)
define('muxp_TRANSIENT_PREFIX', 'muxp_MXPQR_'); // identify our transients or cache entries if the need to be deleted
// activate muxp_USE_TRANSIENTS if longer lifetime beyond current request is desired ,
// otherwise wp-cache is used which will not persist across requests without a persistent caching plugin
// define('muxp_USE_TRANSIENTS',true);  
define('muxp_TRANSIENT_LIFETIME', 3 * DAY_IN_SECONDS); //  for transients only, useless used for nonpersistent cache

// Texts and fallback values for iban and bic if none are found
// in a better world, this data would be in fields on an admin page
// if you need one, have a look at https://jeremyhixon.com/tool/wordpress-option-page-generator/

define('muxp_BACS_IBAN',''); // optional: insert your IBAN as fallback
define('muxp_BACS_BIC',''); // optional: insert your BIC as fallback
define('muxp_BACS_COMPANY', 'recipient'); // optional: insert your business name as fallback


/* 
define('muxp_THANKYOU_PAGE', 'For a convenient payment scan this qr code!'); // PLAIN Text shown before QR code in TY page
define('muxp_THANKYOU_EMAIL', 'For a convenient payment scan this qr code! Some email clients unfortunately will not show Base64 encoded images. Sorry for that!'); // PLAIN Text shown before QR code in Email 
define('muxp_PURPOSE_PREFIX', 'Order-ID:'); // this string will be prefixed to  the order ID in SEPA purpose Text
 */


// Dummy image for dev purposes:
define('muxp_SAD_SMILEY','data:image/png;base64,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');



/**
*   add html snippet with qr-code after data table in thankyou page
*   if the total amount is greater than 0
*/
function muxp_add_text_to_thankyoupage($order_id) {

    $order = wc_get_order( $order_id );
	$order_data = $order->get_data(); 
    // do we need the user? if so: $user = $order->get_user();
	if ( !empty($order->get_total()) && (float)$order->get_total() > 0 ) {
		echo '<p>' . esc_attr(__('For a convenient payment scan this qr code!' , 'mxp-sepa-qr-code-addon-for-woocommerce')) . '<br>';
		echo '<img class="muxp-bacs-qrcode" src="' . esc_attr(muxp_get_qrcode($order->get_total(), $order_id)) . '" alt="qr-code"></p>';
	} 
}

// only executed when using bacs as payment:
add_action( 'woocommerce_thankyou_bacs', 'muxp_add_text_to_thankyoupage' );
// use action 'woocommerce_thankyou' to execute with all payment methods


/**
*   add  text snippet after the order table in email message
*	if total amount is greater than 0 
*   since there is no specific hook for payment methods, we need to make sure that the code is 
*   only shown if bacs is used as payment method
*/

function muxp_email_after_order_table( $order, $sent_to_admin, $plain_text, $email) { 
	// TODO: check whether spam filters are triggered by embedded image data, 
	// if so replace image with a link to the  qr minipage ( site_url() . '?' . muxp_QUERY_PARAM . '=' . md5($order->get_total(). '_' . $order->get_id())
	// and activate muxp_USE_TRANSIENTS 
	if ( !empty($order->get_total()) && (float)$order->get_total() > 0 && $order->get_payment_method() == 'bacs' && ('on-hold' == $order->status || 'pending' == $order->status)) {
		echo '<p>' . esc_attr(__('For a convenient payment scan this qr code! Some email clients unfortunately will not show Base64 encoded images. Sorry for that!' ,'mxp-sepa-qr-code-addon-for-woocommerce')) . '<br> <img class="muxp-bacs-qrcode" src="' . esc_attr(muxp_get_qrcode($order->get_total(), $order->get_id())) . '"></p>';
	}
}

add_action( 'woocommerce_email_after_order_table', 'muxp_email_after_order_table', 10, 4 );


/**
*   testing qr generation by URL param ( value must be a valid md5 hash, otherwise you get the dummy 11-11 Code
*   use 351436ef4b279e1811a6c68a2dd58b1b for testing ...
*/

function muxp_qrpage_from_hash($template) {
    global $wp_query;

    if(!isset( $wp_query->query[muxp_QUERY_PARAM] ) || $wp_query->query[muxp_QUERY_PARAM] == '') {
		// stop processing if our param does not exist or is empty
        return $template;
	}
    
	$md5 = $wp_query->query[muxp_QUERY_PARAM];

	if ( false === muxp_valid_md5($md5) ) { 
		$html = '<p>' .  __('This is not a valid md5 hash :-( ', 'mxp-sepa-qr-code-addon-for-woocommerce')  . '<br>' .  __('Generating uncached dummy QR for 11 Euro/OrderID 11', 'mxp-sepa-qr-code-addon-for-woocommerce') . '<br><img src="' . muxp_create_qrcode(11,11) . '"></p>';
	} elseif ( false === (  $qrcode = muxp_get_transient_by_hash($md5)  ) ) { 
		// valid md5, but nothing found, what a pity ...
		if ( $md5 == "351436ef4b279e1811a6c68a2dd58b1b" ) { 
			// this is the test md5 generated from 11_11 ... test for cache population
			muxp_set_transient(11,11,muxp_SAD_SMILEY); 
		}
		$html =  '<pre>QR-Code not found or expired. Doh!' . PHP_EOL . '</pre><img src="' . muxp_SAD_SMILEY . '" alt="Sad Smiley">';
	} else {
		$html = '<img class="muxp-bacs-qrcode" src="' . $qrcode . '" alt="QR Code">';
	}
	echo '<html><body><div>' . esc_attr($html) . ' </div></body></html>';
	// bail out without returning since we are done and do not want a complete WP page
	exit;

}
// TODO : check for a better place than template_redirect to hook into 
add_action('template_redirect', 'muxp_qrpage_from_hash');


/**
*   Introduce a custom query variable for QR retrieval and testing purposes
*/

function muxp_add_qv($vars){
    $vars[] = muxp_QUERY_PARAM;
    return $vars;
}
add_filter( 'query_vars', 'muxp_add_qv');


/*   Utility and Wrapper functions */

/**
*   get/create QR code from amount and order id, using first bacs account or defaults
*   first, fetch from cache, if possible
*	store result into cache or transient storage for later retrieval
*   create cache/transient ID from a md5 hash of amount and orderid
*/

function muxp_get_qrcode($amount,$orderid) {

	if ( false === ( $qrcode = muxp_get_transient_by_data($amount,$orderid) ) ) {
		// not found - so regenerate the code  and save as transient
		$qrcode = muxp_create_qrcode($amount,$orderid);
	}
	if ( empty($qrcode) ) {
		return false;
	}
	
  muxp_set_transient( $amount,$orderid, $qrcode );	
  return $qrcode;
}


/**
 * create a SEPA qr code based on first bacs account iban and bic , amount and id of order
 * store into transient for later retrieval in email link
 * SEPA QR-Code generation powered by https://github.com/fellwell5/bezahlcode/ (LGPL)
 * we use the local phpqrcode generation method to avoid external dependencies and data transmission
 */
  
function muxp_create_qrcode ($amount,$orderid) {

 $payloadtext = __('Order-ID:' , 'mxp-sepa-qr-code-addon-for-woocommerce') . ' ' . $orderid;
 $bacs_accounts  = get_option( 'woocommerce_bacs_accounts');
 if ( ! empty( $bacs_accounts[0] ) ) {
    $iban =  $bacs_accounts[0]['iban'];
    $bic = $bacs_accounts[0]['bic'];
    $company = $bacs_accounts[0]['account_name'];
  }
  // fallbacks if empty: 
  if ( empty($iban) ) { $iban = muxp_BACS_IBAN; }
  if ( empty($bic) ) { $bic = muxp_BACS_BIC; }
  if ( empty($company) ) { $company = muxp_BACS_COMPANY; }
  
  require_once(dirname(__FILE__) . "/vendor/bezahlcode/bezahlcode.class.php");
  
  $bezahlcode = new Bezahlcode($iban, $bic, $company, "phpqrcode", false);
  $bezahlcode->generatePayload($payloadtext, $amount);
  $base64 = $bezahlcode->generateBase64('png');
  if (empty($base64) ) {
    return false;
  }
  return $base64;
}

// wrapper functions for caching or transients, depending on config

function muxp_get_transient_by_data ($amount,$id) {
	  $transient = muxp_TRANSIENT_PREFIX . md5($amount. '_' . $id);
	  if (defined('muxp_USE_TRANSIENTS')) { 
		  return get_transient( $transient );
	  }
      return wp_cache_get( $transient );
}

function muxp_get_transient_by_hash ($md5) {
	  $transient = muxp_TRANSIENT_PREFIX . $md5;
	  if (defined('muxp_USE_TRANSIENTS')) { 
		  return get_transient( $transient );
	  }
      return wp_cache_get( $transient );
	  
}

 
function muxp_set_transient ($amount,$id,$qrcode) {
	  $transient = muxp_TRANSIENT_PREFIX . md5($amount . '_' . $id);
	  if (defined('muxp_USE_TRANSIENTS')) { 
		$store = set_transient( $transient, $qrcode, muxp_TRANSIENT_LIFETIME );
	  } else {
	  	$store = wp_cache_set( $transient, $qrcode, '', muxp_TRANSIENT_LIFETIME );
	  }
	  return $store;
}

// helper to validate  the string is syntactically correct md5

function muxp_valid_md5($md5 ='') {
  	return strlen($md5) == 32 && ctype_xdigit($md5);
}

?>
