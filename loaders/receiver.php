<?php

/**
 * 
 * Usage = URL -> ?get&n={decode_name}&e={decode_email}&p={decode_phone}&s={decode_store}
 * Experimental 
 * 
 */

add_action('parse_request', 'userReceiver');

function userReceiver() {

$request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'set_uri') !== false) {

		ob_start();
		
		$name = sanitizedText($_GET['n']);
		$email = validateEmail($_GET['e']);
		$phone = sanitizedText($_GET['p']);
		$store_code = sanitizedText($_GET['s']);
		$cleanNumber = cleanPhoneNumber($phone);
		$OriginalString = sanitizedText($_GET['url']);
		$return_user = sanitizedText($_GET['return']);

        // setUserData($email, $email, $phone, $store_code);
        // wc_create_new_customer_custom($email, '', $cleanNumber, $name, $phone, $store_code);

        $url = (explode( "/", $OriginalString));
        
        function get_product_by_slug($page_slug, $output = OBJECT) {
            global $wpdb;
                $product = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, 'product'));
                if ( $product )
                    return get_post($product, $output);
            return null;
        }

        $product = get_product_by_slug($url[2]);
		$getID = $_COOKIE["count"];

		if($return_user == 'true') {
			updateProjectBoard($getID, $product->ID);
			wp_redirect( home_url( "$OriginalString" ) );
		} else {
			createAll($name, $cleanNumber, $email, $phone, $store_code, $product->ID);
			wp_redirect( home_url( "/?password_protected_pwd=$store_code&wp-submit&password_protected_cookie_test=1&redirect_to=$OriginalString" ) );
		}
    ?>

<?php
      exit();
   }
}

?>