<?php

// JCART v1.1
// http://conceptlogic.com/jcart/

///////////////////////////////////////////////////////////////////////
// REQUIRED SETTINGS

// THE HTML NAME ATTRIBUTES USED IN YOUR ADD-TO-CART FORM
$jcart['store_id']  = 'my-store-id';    // STORE ID

$jcart['item_id']		= 'my-item-id';			// ITEM ID
$jcart['item_name']		= 'my-item-name';		// ITEM NAME
$jcart['item_price']	= 'my-item-price';		// ITEM PRICE
$jcart['item_qty']		= 'my-item-qty';		// ITEM QTY
$jcart['item_add']		= 'my-add-button';		// ADD-TO-CART BUTTON
//JSTN-begin
// ITEM OPTIONS PREFIX, in the form of: <input type="hidden" name="my-item-option[0][name]" value="Color" />
$jcart['item_options'] = 'my-item-option';
//JSTN-end

// PATH TO THE DIRECTORY CONTAINING JCART FILES
$jcart['path'] = 'jcart/';

// THE PATH AND FILENAME WHERE SHOPPING CART CONTENTS SHOULD BE POSTED WHEN A VISITOR CLICKS THE CHECKOUT BUTTON
// USED AS THE ACTION ATTRIBUTE FOR THE SHOPPING CART FORM
$jcart['form_action']	= 'checkout.php';


///////////////////////////////////////////////////////////////////////
// PayPal CONFIGURATION:

// YOUR PAYPAL SECURE MERCHANT ACCOUNT ID
$jcart['paypal_id']		= '';

// OPTIONAL currency code; if blank, PayPal defaults to USD ($)
$jcart['paypal_currency'] = '';
// For a complete list of supported codes, consult PayPal website:
// https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside

// OPTIONAL "return" page (page on your site to redirect to after successful checkout)
$jcart['paypal_return'] = '';
// OPTIONAL "return_cancel" page (page on your site to redirect after canceled checkout)
$jcart['paypal_cancel'] = '';


///////////////////////////////////////////////////////////////////////
// OPTIONAL SETTINGS

// OVERRIDE DEFAULT CART TEXT
$jcart['text']['cart_title']				= '';		// Shopping Cart
$jcart['text']['single_item']				= '';		// Item
$jcart['text']['multiple_items']			= '';		// Items
$jcart['text']['subtotal']					= '';		// Subtotal

//Explicit setting of currency_symbol DEPRECIATED as of jCart v1.2
/*
$jcart['text']['currency_symbol']			= '';		// $
*/

$jcart['text']['update_button']				= '';		// update
$jcart['text']['checkout_button']			= '';		// checkout
$jcart['text']['checkout_paypal_button']	= '';		// Checkout with PayPal
$jcart['text']['remove_link']				= '';		// remove
$jcart['text']['empty_button']				= '';		// empty
$jcart['text']['empty_message']				= '';		// Your cart is empty!
$jcart['text']['item_added_message']		= '';		// Item added!

$jcart['text']['price_error']				= '';		// Invalid price format!
$jcart['text']['quantity_error']			= '';		// Item quantities must be whole numbers!
$jcart['text']['checkout_error']			='';		// Your order could not be processed!

// OVERRIDE THE DEFAULT BUTTONS WITH YOUR IMAGES BY SETTING THE PATH FOR EACH IMAGE
$jcart['button']['checkout']				= '';
$jcart['button']['paypal_checkout']			= '';
$jcart['button']['update']					= '';
$jcart['button']['empty']					= '';

?>
