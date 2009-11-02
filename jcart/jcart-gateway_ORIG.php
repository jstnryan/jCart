<?php

// JCART v1.1
// http://conceptlogic.com/jcart/

// THIS FILE IS CALLED WHEN ANY BUTTON ON THE CHECKOUT PAGE (PAYPAL CHECKOUT, UPDATE, OR EMPTY) IS CLICKED
// WE CAN ONLY DEFINE ONE FORM ACTION, SO THIS FILE ALLOWS US TO FORK THE FORM SUBMISSION DEPENDING ON WHICH BUTTON WAS CLICKED
// ALSO ALLOWS US TO VERIFY PRICES BEFORE SUBMITTING TO PAYPAL

// INCLUDE JCART BEFORE SESSION START
include_once 'jcart.php';

// START SESSION
session_start();

// INITIALIZE JCART AFTER SESSION START
$cart =& $_SESSION['jcart']; if(!is_object($cart)) $cart = new jcart();

// WHEN JAVASCRIPT IS DISABLED THE UPDATE AND EMPTY BUTTONS ARE DISPLAYED
// RE-DISPLAY THE CART IF THE VISITOR CLICKS EITHER BUTTON
if ($_POST['jcart_update_cart']  || $_POST['jcart_empty'])
	{

	// UPDATE THE CART
	if ($_POST['jcart_update_cart'])
		{
		$cart_updated = $cart->update_cart();
		if ($cart_updated !== true)
			{
			$_SESSION['quantity_error'] = true;
			}
		}

	// EMPTY THE CART
	if ($_POST['jcart_empty'])
		{
		$cart->empty_cart();
		}

	// REDIRECT BACK TO THE CHECKOUT PAGE
	header('Location: ' . $_POST['jcart_checkout_page']);
	exit;
	}

// THE VISITOR HAS CLICKED THE PAYPAL CHECKOUT BUTTON
else
	{

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////
	/*

	A malicious visitor may try to change item prices before checking out,
	either via javascript or by posting from an external script.

	Here you can add PHP code that validates the submitted prices against
	your database or validates against hard-coded prices.

	The cart data has already been sanitized and is available thru the
	$cart->get_contents() function. For example:

	foreach ($cart->get_contents() as $item)
		{
		$item_id	= $item['id'];
		$item_name	= $item['name'];
		$item_price	= $item['price'];
		$item_qty	= $item['qty'];
		}

	*/
	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	$valid_prices = true;

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	// IF THE SUBMITTED PRICES ARE NOT VALID
	if ($valid_prices !== true)
		{
		// KILL THE SCRIPT
		die($jcart['text']['checkout_error']);
		}

	// PRICE VALIDATION IS COMPLETE
	// SEND CART CONTENTS TO PAYPAL USING THEIR UPLOAD METHOD, FOR DETAILS SEE http://tinyurl.com/djoyoa
	else if ($valid_prices === true)
		{
		$paypal_count = 1; // PAYPAL COUNT STARTS AT ONE INSTEAD OF ZERO
		$query_string;
		foreach ($cart->get_contents() as $item)
			{
			// BUILD THE QUERY STRING
			$query_string .= '&item_name_' . $paypal_count . '=' . $item['name'];
			$query_string .= '&item_number_' . $paypal_count . '=' . $item['id'];
			$query_string .= '&amount_' . $paypal_count . '=' . $item['price'];
			$query_string .= '&quantity_' . $paypal_count . '=' . $item['qty'];
			if ($item['option'] != '') {
        //again, need to modify this to support JSON, instead of string - jstn
  			$query_string .= '&on0_' . $paypal_count . '=Option'; if (strpos($item['option'], ",") !== false) { $query_string .= 's'; }
  			$query_string .= '&os0_' . $paypal_count . '=' . $item['option'];
  		}

			// INCREMENT THE COUNTER
			++$paypal_count;
			}
			
		// ADD OPTIONAL PayPal CHECKOUT VALUES, IF SET
		if($jcart['currency_code']) { $query_string .= '&currency_code=' . $jcart['paypal_currency']; }
		if($jcart['paypal_return']) { $query_string .= '&return=' . $jcart['paypal_return']; }
		if($jcart['paypal_cancel']) { $query_string .= '&cancel_return=' . $jcart['paypal_cancel']; }
			
		// EMPTY THE CART
		$cart->empty_cart();
// FUTURE CHANGE: only empty cart upon successful purchase. If user clicks
//  "Cancel and return to [website]", cart contents will be retained. - jstn

		if($jcart['paypal_id'])
			{
			// REDIRECT TO PAYPAL WITH MERCHANT ID AND CART CONTENTS
			header( 'Location: https://www.paypal.com/cgi-bin/webscr?cmd=_cart&upload=1&charset=utf-8&business=' . $jcart['paypal_id'] . $query_string);
			exit;
			}
		else
			// THE USER HAS NOT CONFIGURED A PAYPAL ID
			// DISPLAY THE PAYPAL URL WITH AN ERROR MESSAGE
			{
//Notice from jCart v1.1
/*
			echo 'PayPal integration requires a secure merchant ID. Please see the <a href="http://conceptlogic.com/jcart/install.php">installation instructions</a> for more info.<br /><br />';
			echo 'Below is the URL that would be sent to PayPal if a merchant ID was set in <strong>jcart-config.php</strong>:<br /><br />';
			echo 'https://www.paypal.com/cgi-bin/webscr?cmd=_cart&upload=1&business=PAYPAL_ID' . $items_query_string;
*/

//Notice from jCart 1.2
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title></title>
	</head>
	<body>
		<div style="width:950px; margin:40px auto; padding:20px; border:solid 2px #333; background:#ededed;">
			<p><strong>PayPal integration requires a secure merchant ID!</strong></p>
			<p>Below is the URL that would be sent to PayPal if a merchant ID was set<!-- in your <a href="<?php echo get_option('siteurl');?>/wp-admin/options-general.php?page=jcart/jcart-admin.php">jCart options</a>-->:</p>
			<p>https://www.paypal.com/cgi-bin/webscr?cmd=_cart&upload=1&charset=utf-8&currency_code=<?php echo $jcart['paypal_currency'];?>&business=PAYPAL_ID<?php echo $items_query_string;?></p>
		</div>
	</body>
</html>
<?php
			exit;
			}
		}
	}

?>
