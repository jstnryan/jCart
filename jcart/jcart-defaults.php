<?php

// JCART v1.1
// http://conceptlogic.com/jcart/

// DEFAULT CART TEXT USED IF NOT OVERRIDDEN IN jcart-config.php
// DEFAULTS MUST BE AVAILABLE TO jcart.php AND jcart-javascript.php
// INCLUDED AS A SEPARATE FILE TO SIMPLIFY USER CONFIG

if (!$jcart['path']) die('The path to jCart isn\'t set. Please see <strong>jcart-config.php</strong> for more info.');

if (!$jcart['text']['cart_title']) $jcart['text']['cart_title']							= 'Shopping Cart';
if (!$jcart['text']['single_item']) $jcart['text']['single_item']						= 'Item';
if (!$jcart['text']['multiple_items']) $jcart['text']['multiple_items']					= 'Items';
if (!$jcart['text']['currency_symbol']) $jcart['text']['currency_symbol']				= '$';
if (!$jcart['text']['subtotal']) $jcart['text']['subtotal']								= 'Subtotal';

if (!$jcart['text']['update_button']) $jcart['text']['update_button']					= 'update';
if (!$jcart['text']['checkout_button']) $jcart['text']['checkout_button']				= 'checkout';
if (!$jcart['text']['checkout_paypal_button']) $jcart['text']['checkout_paypal_button']	= 'Checkout with PayPal';
if (!$jcart['text']['remove_link']) $jcart['text']['remove_link']						= 'remove';
if (!$jcart['text']['empty_button']) $jcart['text']['empty_button']						= 'empty';
if (!$jcart['text']['empty_message']) $jcart['text']['empty_message']					= 'Your cart is empty!';
if (!$jcart['text']['item_added_message']) $jcart['text']['item_added_message']			= 'Item added!';

if (!$jcart['text']['price_error']) $jcart['text']['price_error']						= 'Invalid price format!';
if (!$jcart['text']['quantity_error']) $jcart['text']['quantity_error']					= 'Item quantities must be whole numbers!';
if (!$jcart['text']['checkout_error']) $jcart['text']['checkout_error']					= 'Your order could not be processed!';

?>
