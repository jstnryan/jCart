<?php

		// DISPLAY THE CART HEADER
//		echo "<!-- BEGIN JCART -->\n<div id='jcart'>\n";
		echo "\t$error_message\n";
		echo "\t<form method='post' action='$form_action'>\n";
		echo "\t\t<fieldset>\n";
		echo "\t\t\t<table border='1'>\n";
		echo "\t\t\t\t<tr>\n";
		echo "\t\t\t\t\t<th id='jcart-header' colspan='3'>\n";
		echo "\t\t\t\t\t\t<strong id='jcart-title'>" . $text['cart_title'] . "</strong> (" . $this->itemcount . "&nbsp;" . $text['items_in_cart'] .")\n";
		echo "\t\t\t\t\t</th>\n";
		echo "\t\t\t\t</tr>". "\n";

		// IF ANY ITEMS IN THE CART
		if($this->itemcount > 0)
			{

			// DISPLAY LINE ITEMS
			foreach($this->get_contents() as $item)
				{
				echo "\t\t\t\t<tr>\n";

				// ADD THE ITEM ID AS THE INPUT ID ATTRIBUTE
				// THIS ALLOWS US TO ACCESS THE ITEM ID VIA JAVASCRIPT ON QTY CHANGE, AND THEREFORE UPDATE THE CORRECT ITEM
				// NOTE THAT THE ITEM ID IS ALSO PASSED AS A SEPARATE FIELD FOR PROCESSING VIA PHP
				echo "\t\t\t\t\t<td class='jcart-item-qty'>\n";
				echo "\t\t\t\t\t\t<input type='text' size='2' id='jcartItem=" . $item['id'] . "," . $item['variation'] . "' name='jcart_item_qty[ ]' value='" . $item['qty'] . "' />\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td class='jcart-item-name'>\n";
				echo "\t\t\t\t\t\t" . $item['name'] . "<input type='hidden' name='jcart_item_name[ ]' value='" . $item['name'] . "' />\n";
				echo "\t\t\t\t\t\t<input type='hidden' name='jcart_item_id[ ]' value='" . $item['id'] . "," . $item['variation'] . "' />\n";
echo "\n<br>" . $item['option'] . "\n";
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t\t<td class='jcart-item-price'>\n";
				echo "\t\t\t\t\t\t<span>" . $text['currency_symbol'] . number_format($item['subtotal'],2) . "</span><input type='hidden' name='jcart_item_price[ ]' value='" . $item['price'] . "' />\n";
//JSTN-begin
        echo "\t\t\t\t\t\t<a class='jcart-remove' href='?jcart_remove=" . $item['id'] . "," . $item['variation'];
if ($_GET['js'] == "false") { echo "&js=false"; }
        echo "'>" . $text['remove_link'] . "</a>\n";
//JSTN-end
				echo "\t\t\t\t\t</td>\n";
				echo "\t\t\t\t</tr>\n";
				}
			}

		// THE CART IS EMPTY
		else
			{
			echo "\t\t\t\t<tr><td colspan='3' class='empty'>" . $text['empty_message'] . "</td></tr>\n";
			}

		// DISPLAY THE CART FOOTER
		echo "\t\t\t\t<tr>\n";
		echo "\t\t\t\t\t<th id='jcart-footer' colspan='3'>\n";

		// IF THIS IS THE CHECKOUT HIDE THE CART CHECKOUT BUTTON
		if ($is_checkout !== true)
			{
			if ($button['checkout']) { $input_type = 'image'; $src = ' src="' . $button['checkout'] . '" alt="' . $text['checkout_button'] . '" title="" ';	}
			echo "\t\t\t\t\t\t<input type='" . $input_type . "' " . $src . "id='jcart-checkout' name='jcart_checkout' class='jcart-button' value='" . $text['checkout_button'] . "' />\n";
			}

		echo "\t\t\t\t\t\t<span id='jcart-subtotal'>" . $text['subtotal'] . ": <strong>" . $text['currency_symbol'] . number_format($this->total,2) . "</strong></span>\n";
		echo "\t\t\t\t\t</th>\n";
		echo "\t\t\t\t</tr>\n";
		echo "\t\t\t</table>\n\n";

		echo "\t\t\t<div class='jcart-hide'>\n";
		if ($button['update']) { $input_type = 'image'; $src = ' src="' . $button['update'] . '" alt="' . $text['update_button'] . '" title="" ';	}
		echo "\t\t\t\t<input type='" . $input_type . "' " . $src ."name='jcart_update_cart' value='" . $text['update_button'] . "' class='jcart-button' />\n";
		if ($button['empty']) { $input_type = 'image'; $src = ' src="' . $button['empty'] . '" alt="' . $text['empty_button'] . '" title="" ';	}
		echo "\t\t\t\t<input type='" . $input_type . "' " . $src ."name='jcart_empty' value='" . $text['empty_button'] . "' class='jcart-button' />\n";
		echo "\t\t\t</div>\n";

		// IF THIS IS THE CHECKOUT DISPLAY THE PAYPAL CHECKOUT BUTTON
		if ($is_checkout == true)
			{
			// HIDDEN INPUT ALLOWS US TO DETERMINE IF WE'RE ON THE CHECKOUT PAGE
			// WE NORMALLY CHECK AGAINST REQUEST URI BUT AJAX UPDATE SETS VALUE TO jcart-relay.php
			echo "\t\t\t<input type='hidden' id='jcart-is-checkout' name='jcart_is_checkout' value='true' />\n";

			// SEND THE URL OF THE CHECKOUT PAGE TO jcart-gateway.php
			// WHEN JAVASCRIPT IS DISABLED WE USE A HEADER REDIRECT AFTER THE UPDATE OR EMPTY BUTTONS ARE CLICKED
			$protocol = 'http://'; if (!empty($_SERVER['HTTPS'])) { $protocol = 'https://'; }
			echo "\t\t\t<input type='hidden' id='jcart-checkout-page' name='jcart_checkout_page' value='" . $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "' />\n";

			// PAYPAL CHECKOUT BUTTON
			if ($button['paypal_checkout'])	{ $input_type = 'image'; $src = ' src="' . $button['paypal_checkout'] . '" alt="' . $text['checkout_paypal_button'] . '" title="" '; }
			echo "\t\t\t<input type='" . $input_type . "' " . $src ."id='jcart-paypal-checkout' name='jcart_paypal_checkout' value='" . $text['checkout_paypal_button'] . "'" . $disable_paypal_checkout . " />\n";
			}
		echo "\t\t</fieldset>\n";
		echo "\t</form>\n";

		// IF UPDATING AN ITEM, FOCUS ON ITS QTY INPUT AFTER THE CART IS LOADED (DOESN'T SEEM TO WORK IN IE7)
		if ($_POST['jcart_update_item'])
			{
			echo "\t" . '<script type="text/javascript">$(function(){$("#jcart-item-id-' . $_POST['item_id'] . '").focus()});</script>' . "\n";
			}
//		echo "</div>\n<!-- END JCART -->\n";

//DIAGNOSTIC OUTPUT:
echo "<pre>";
echo print_r($this->items);
echo "</pre>";

?>