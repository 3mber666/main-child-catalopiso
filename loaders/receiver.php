<?php

/**
 * Create Users [Create Wishlist] -> /set_uri?&n={name}&e={email}&p={phone_number}&s={store_code}&product=1&url={product_prefix}
 * Return Users [For Filters, Searches and ETC only] -> /set_uri?&product=0&return=1&url={product_prefix}
 * Return Users [Update Wishlist] -> 	
 * */

add_action('parse_request', 'userReceiver');

function userReceiver() {

$request_uri_string = $_SERVER['REQUEST_URI'];
    if(strpos($request_uri_string, 'set_uri') !== false) {
				
		$name = sanitizedText($_GET['n']);
		$email = validateEmail($_GET['e']);
		$phone = sanitizedText($_GET['p']);
		$store_code = sanitizedText($_GET['s']);
		$cleanNumber = cleanPhoneNumber($phone);

		$OriginalString = sanitizedText($_GET['url']);
		$return_user = sanitizedText($_GET['return']);
		$isProduct = sanitizedText($_GET['product']);
        $url = (explode( "/", $OriginalString));
		
		setCookies('count', getProjectBoard($email), 3600);
		
		if($isProduct) {

			$product = get_product_by_slug($url[2]);

			if(!$return_user) {
				$emailUsed = isEmailExist($email);

				if($emailUsed) {
					$x = $_COOKIE["count"];
					if(!$x) {
						createProjectBoard($emailUsed, $email, $product->ID);
						wp_redirect(home_url("/?password_protected_pwd=$store_code&redirect_to=$OriginalString"));
					}

				}

				if(!$emailUsed) {
					$randNumber = rand(10,100);
					createAll($name.$randNumber, $cleanNumber, $email, $phone, $store_code, $product->ID);
					wp_redirect( home_url( "/?password_protected_pwd=$store_code&redirect_to=$OriginalString" ) );
				}
			}

			if($return_user) {
					$x = $_COOKIE["count"];
					updateProjectBoard($x, $product->ID);
					wp_redirect(home_url("$OriginalString"));
				}
			}

		
		if(!$isProduct) {
			$emailUsed = isEmailExist($email);

			if(!$return_user) {
				if(!$emailUsed) {
					wp_redirect( home_url ( "/?password_protected_pwd=$store_code&redirect_to=$OriginalString" ) );
				}
			}
			if($return_user) {
					wp_redirect( home_url ( "$OriginalString" ) );
			}
		}

    ?>

<?php
      exit();
   }
}

?>