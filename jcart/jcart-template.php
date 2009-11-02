<?php
		// DISPLAY THE CART HEADER
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
		if ($this->itemcount > 0)
			{

			// DISPLAY LINE ITEMS
			$cart_contents = $this->get_contents();
			if (sizeof($cart_contents) < 2) { $oneStore = true; }
			foreach($cart_contents as $id => $store)
				{
				if ($id <> '') {
  				echo "\t\t\t\t<tr class='jcart-store-row'><td colspan='3'>" . $text['store_text'] . "<span class='jcart-store-name'>";
          if ($this->storenames[$id] !== '') {
            echo $this->storenames[$id];
          } else {
            echo $id;
          }
          echo "</span></td></tr>\n";
  			} else {
          if (!$oneStore) {
            echo "\t\t\t\t<tr class='jcart-store-row'><td colspan='3'>" . $text['store_text'] . "<span class='jcart-store-name'>" . $text['store_undefined'] . "</span></td></tr>\n";
          }
        }
				
				  foreach($store as $item) {
				
      			echo "\t\t\t\t<tr>\n";
      			// ADD THE ITEM ID AS THE INPUT ID ATTRIBUTE
      			// THIS ALLOWS US TO ACCESS THE ITEM ID VIA JAVASCRIPT ON QTY CHANGE, AND THEREFORE UPDATE THE CORRECT ITEM
      			// NOTE THAT THE ITEM ID IS ALSO PASSED AS A SEPARATE FIELD FOR PROCESSING VIA PHP
      			echo "\t\t\t\t\t<td class='jcart-item-qty'>\n";
      			echo "\t\t\t\t\t\t<input type='text' size='2' id='jcartItem=" . $item['id'] . "," . $item['variation'] . ',' . $item['store'] . "' name='jcart_item_qty[ ]' value='" . $item['qty'] . "' />\n";
      			echo "\t\t\t\t\t</td>\n";
      			echo "\t\t\t\t\t<td class='jcart-item-name'>\n";
      			echo "\t\t\t\t\t\t" . $item['name'] . "<input type='hidden' name='jcart_item_name[ ]' value='" . $item['name'] . "' />\n";
      			echo "\t\t\t\t\t\t<input type='hidden' name='jcart_item_id[ ]' value='" . $item['id'] . "," . $item['variation'] . ',' . $item['store'] . "' />\n";
  //JSTN-begin
  // I really want to change this to a JSON array, instead of a preassembled
  //  string, so designer may process option pairs however they desire, but that
  //  means more code in "template" file, less symantic code, etc.. - jstn
  /*
  echo "\n<br />" . $item['option'] . "\n";
  */
  echo "\n<ul>";
  foreach ($item['option'] as $key => $val) {
  //  echo "\n<li>" . $key . ": " . $val . "</li>";
  echo "\n<li>" . $item['option'][$key]['name'] . ": " . $item['option'][$key]['value'] . "</li>";
  }
  echo "\n</ul>";
  //JSTN-end
      			echo "\t\t\t\t\t</td>\n";
      			echo "\t\t\t\t\t<td class='jcart-item-price'>\n";
      			echo "\t\t\t\t\t\t<span>" . $text['currency_symbol'] . number_format($item['subtotal'],2) . "</span><input type='hidden' name='jcart_item_price[ ]' value='" . $item['price'] . "' />\n";
  //JSTN-begin
            echo "\t\t\t\t\t\t<a class='jcart-remove' href='?jcart_remove=" . $item['id'] . "," . $item['variation'];
            echo ',' . $item['store'];
  if ($_GET['js'] == "false") { echo "&js=false"; }
            echo "'>" . $text['remove_link'] . "</a>\n";
  //JSTN-end
      			echo "\t\t\t\t\t</td>\n";
      			echo "\t\t\t\t</tr>\n";
				
				  }
				
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

		// DEFAULT INPUT TYPE
		// CAN BE OVERRIDDEN IF USER SETS PATHS FOR BUTTON IMAGES
		$input_type = array(); //Must declare as array() or $input_type['..ANYTHING..'] stores only first character (ie: 's')? - jstn
		$input_type['checkout'] = $input_type['update'] = $input_type['empty'] = $input_type['checkout_paypal'] = 'submit';

		// IF THIS IS THE CHECKOUT HIDE THE CART CHECKOUT BUTTON
		if ($is_checkout !== true)
			{
			if ($button['checkout']) { $input_type['checkout'] = 'image'; $src['checkout'] = ' src="' . $button['checkout'] . '" alt="' . $text['checkout_button'] . '" title="" ';	}
			echo "\t\t\t\t\t\t<input type='" . $input_type['checkout'] . "' " . $src['checkout'] . "id='jcart-checkout' name='jcart_checkout' class='jcart-button' value='" . $text['checkout_button'] . "' />\n";
			}

		echo "\t\t\t\t\t\t<span id='jcart-subtotal'>" . $text['subtotal'] . ": <strong>" . $text['currency_symbol'] . number_format($this->total,2) . "</strong></span>\n";
		echo "\t\t\t\t\t</th>\n";
		echo "\t\t\t\t</tr>\n";
		echo "\t\t\t</table>\n\n";

		echo "\t\t\t<div class='jcart-hide'>\n";

		if ($button['update']) { $input_type['update'] = 'image'; $src['update'] = ' src="' . $button['update'] . '" alt="' . $text['update_button'] . '" title="" ';	}
		echo "\t\t\t\t<input type='" . $input_type['update'] . "' " . $src['update'] ."name='jcart_update_cart' value='" . $text['update_button'] . "' class='jcart-button' />\n";

		if ($button['empty']) { $input_type['empty'] = 'image'; $src['empty'] = ' src="' . $button['empty'] . '" alt="' . $text['empty_button'] . '" title="" ';	}
		echo "\t\t\t\t<input type='" . $input_type['empty'] . "' " . $src['empty'] ."name='jcart_empty' value='" . $text['empty_button'] . "' class='jcart-button' />\n";

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

			// IF THE CART IS EMPTY
			if ($this->itemcount < 1)
				{
				// DISABLE PAYPAL CHECKOUT
				$disable_paypal_checkout = ' disabled="disabled" ';
				}

			// PAYPAL CHECKOUT BUTTON
			if ($button['checkout_paypal'])	{ $input_type['checkout_paypal'] = 'image'; $src['checkout_paypal'] = ' src="' . $button['checkout_paypal'] . '" alt="' . $text['checkout_paypal_button'] . '" title="" '; }
			echo "\t\t\t<input type='" . $input_type['checkout_paypal'] . "' " . $src['checkout_paypal'] ."id='jcart-checkout-paypal' name='jcart_checkout_paypal' value='" . $text['checkout_paypal_button'] . "'" . $disable_paypal_checkout . " />\n";
			}
		echo "\t\t</fieldset>\n";
		echo "\t</form>\n";

		// IF UPDATING AN ITEM, FOCUS ON ITS QTY INPUT AFTER THE CART IS LOADED (DOESN'T SEEM TO WORK IN IE7, OR IE8)
		if ($_POST['jcart_update_item'])
			{
			echo "\t" . '<script type="text/javascript">$(function(){$("#jcart-item-id-' . $_POST['item_id'] . '").focus()});</script>' . "\n";
			}

//DIAGNOSTIC OUTPUT:
echo 'Debug: "' . $this->debug . '"';
$this->debug = '';
echo "<pre>";
echo print_r($this->items);
echo '-----------------------------';
echo print_r($cart_contents);
echo '-----------------------------';
echo print_r($this->storenames);
echo "</pre>";
?>
