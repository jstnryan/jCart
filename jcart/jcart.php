<?php
// JCART v1.2
// http://conceptlogic.com/jcart/
// SESSION BASED SHOPPING CART CLASS FOR JCART
/**********************************************************************
Based on Webforce Cart v.1.5
(c) 2004-2005 Webforce Ltd, NZ
http://www.webforce.co.nz/cart/
**********************************************************************/
//Additional code by Justin Ryan, http://jstnryan.com

// USER CONFIG
include_once('jcart-config.php');
// DEFAULT CONFIG VALUES
include_once('jcart-defaults.php');

class jcart {
  var $total = 0;
  var $itemcount = 0;
  var $items = array();
  
  //debug:
  var $debug = '';
  
  //CONSTRUCTOR FUNCTION
  function cart() {}
  
  //GET CART CONTENTS
  function get_contents() {
    $items = array(); //to contain all the items to return
    foreach($this->items as $store => $s_items) {
    
      foreach($s_items as $key => $tmp_item) {
        $item = FALSE;
        
        $item['store'] = $store;
        $item['id'] = $key;
        $item['name'] = $tmp_item['name'];
        $b_price = $tmp_item['price'];
        foreach($tmp_item['variation'] as $key => $variation) {
          $item['variation'] = $key;
    
          $item['qty'] = $variation['qty'];
          $opt_price = 0;
//$opt_str = ""; //possibly return this as array instead
//$opt = array();
          foreach($variation['options'] as $k => $v) {
            $opt_price += $v['price'];
//$opt_str = $opt_str . $v['name'] . ": " . $v['value'] . ", ";
//$opt[$v['name']] = $v['value'];
          }
//$item['option'] = substr($opt_str, 0, strlen($opt_str) - 2);
//$item['option'] = $opt;
$item['option'] = $variation['options'];
          $item['price'] = $b_price + $opt_price;
          $item['subtotal'] = $variation['qty'] * $item['price'];
          
          $items[] = $item;
        }
      }
      
    }
    
    return $items;
  }//get_contents()
  
  //ADD AN ITEM
  function add_item($store_id, $item_id, $item_qty=1, $item_price, $item_info=array('name'=>"",'options'=>array())) {
    $valid_item_qty = $valid_item_price = false;
    //Ensure quantity is POSITIVE integer or Zero
    if (preg_match("/^[0-9]+$/i", $item_qty) && ($item_qty > -1)) {
      $valid_item_qty = true;
    }
    //Ensure price is a floating point number (can be negative, ie: discount)
    if (is_numeric($item_price)) {
      $valid_item_price = true;
    }
    //If valid qty/price add item to cart
    if ($valid_item_qty && $valid_item_price) {
      //Check if store_id is already in cart
      $name = $item_info['item-name'];
      unset($item_info['item-name']);
      if (empty($item_info)) { $item_info = array(); }
      
      if ($this->items[$store_id]) {
        //store exists
        //Check to see if already in cart
        if ($this->items[$store_id][$item_id]) {
          //Some variation of this item already in cart
          $found_match = false;
          //Look for matching set of options
          foreach ($this->items[$store_id][$item_id]['variation'] as $key => $var) {
            if ($var['options'] === $item_info) { $found_match = $key; break; }
          }
          unset($key, $var); //don't know if unset is actually necessary here -jstn
          if ($found_match === false) {
            //Match not found, add as a new variation
            $this->items[$store_id][$item_id]['variation'][] = array("qty"=>$item_qty,"options"=>$item_info);
          } else {
            //Match found, increase quantity of matching variation
            $this->items[$store_id][$item_id]['variation'][$found_match]['qty'] = $item_qty + $this->items[$store_id][$item_id]['variation'][$found_match]['qty'];
          }
        } else {
          //if item is not aleady in cart, add
          $this->items[$store_id][$item_id] = array("price"=>$item_price,"name"=>$name,"variation"=>array(array("qty"=>$item_qty,"options"=>$item_info)));
        }
      } else {
        //create store, add product
        $this->items[$store_id] = array($item_id=>array("price"=>$item_price,"name"=>$name,"variation"=>array(array("qty"=>$item_qty,"options"=>$item_info))));
      }

      $this->_update_total();
      return true;

    //If product had invalid qty/price return error
    } else if (!$valid_item_qty) {
      return 'qty';
    } else if (!$valid_item_price) {
      return 'price';
    }
  }//add_item()
  
  //UPDATE AN ITEM
  function update_item($store_id, $item_id, $item_variation=0, $item_qty) {
    //Ensure quantity is POSITIVE integer or Zero
    if (preg_match("/^[0-9-]+$/i", $item_qty) && ($item_qty > -1)) {
      if ($item_qty < 1) {
        $this->del_item($store_id, $item_id, $item_variation);
      } else {
        $this->items[$store_id][$item_id]['variation'][$item_variation]['qty'] = $item_qty;
      }
      $this->_update_total();
      return true;
    }
  }//update_item()

	//UPDATE THE ENTIRE CART
	//IT IS POSSIBLE FOR VISITOR TO CHANGE MULTIPLE FIELDS BEFORE CLICKING UPDATE
	//(ONLY USED WHEN JAVASCRIPT IS DISABLED, ELSE THE CART IS UPDATED ONKEYUP)
  function update_cart() {
//DOUG: it does not appear that we need this statement:
/*
    //POST VALUE IS AN ARRAY OF ALL ITEM IDs IN THE CART
		if (is_array($_POST['jcart_item_ids'])) {
			//TREAT VALUES AS A STRING FOR VALIDATION
      $item_ids = implode($_POST['jcart_item_ids']);
		}
*/
    //POST VALUE IS AN ARRAY OF ALL ITEM QUANTITIES IN THE CART
		if (is_array($_POST['jcart_item_qty'])) {
		  //TREAT VALUES AS A STRING FOR VALIDATION
			$item_qtys = implode($_POST['jcart_item_qty']);
		}

		//IF NO ITEM IDs, THE CART IS EMPTY
		if ($_POST['jcart_item_id']) {
			//IF THE ITEM QTY IS AN INTEGER, OR ZERO, OR EMPTY
			//UPDATE THE ITEM
			if (preg_match("/^[0-9-]+$/i", $item_qtys) || $item_qtys == '') {
				//THE INDEX OF THE ITEM AND ITS QUANTITY IN THEIR RESPECTIVE ARRAYS
				$count = 0;

				foreach ($_POST['jcart_item_id'] as $item_id) {
				  $item_id = explode(",", $item_id);
					//GET THE ITEM QTY AND DOUBLE-CHECK THAT THE VALUE IS AN INTEGER
					$update_item_qty = intval($_POST['jcart_item_qty'][$count]);

					if ($update_item_qty < 1) {
						$this->del_item($item_id[0], $item_id[1]);
					} else {
						//UPDATE THE ITEM
						$this->update_item($item_id[0], $item_id[1], $update_item_qty);
					}

					//INCREMENT INDEX FOR THE NEXT ITEM
					$count++;
				}
				return true;
			}
		}
		//IF NO ITEMS IN THE CART, RETURN TRUE TO PREVENT UNNECSSARY ERROR MESSAGE
		else if (!$_POST['jcart_item_id'])
			{
			return true;
			}
  }//update_cart()
  
  //REMOVE AN ITEM
	/*
	GET VAR COMES FROM A LINK, WITH THE ITEM ID TO BE REMOVED IN ITS QUERY STRING
	AFTER AN ITEM IS REMOVED ITS ID STAYS SET IN THE QUERY STRING, PREVENTING THE SAME ITEM FROM BEING ADDED BACK TO THE CART
	SO WE CHECK TO MAKE SURE ONLY THE GET VAR IS SET, AND NOT THE POST VARS

	USING POST VARS TO REMOVE ITEMS DOESN'T WORK BECAUSE WE HAVE TO PASS THE ID OF THE ITEM TO BE REMOVED AS THE VALUE OF THE BUTTON
	IF USING AN INPUT WITH TYPE SUBMIT, ALL BROWSERS DISPLAY THE ITEM ID, INSTEAD OF ALLOWING FOR USER FRIENDLY TEXT SUCH AS 'remove'
	IF USING AN INPUT WITH TYPE IMAGE, INTERNET EXPLORER DOES NOT SUBMIT THE VALUE, ONLY X AND Y COORDINATES WHERE BUTTON WAS CLICKED
	CAN'T USE A HIDDEN INPUT EITHER SINCE THE CART FORM HAS TO ENCOMPASS ALL ITEMS TO RECALCULATE TOTAL WHEN A QUANTITY IS CHANGED, WHICH MEANS THERE ARE MULTIPLE REMOVE BUTTONS AND NO WAY TO ASSOCIATE THEM WITH THE CORRECT HIDDEN INPUT
	*/
  function del_item($store_id, $item_id, $item_variation=0) {
    if ($item_variation < 0) {
      //This statement is not neccessary, but I have included this function
      //  for future use, to implement a feature to delete ALL variations
      //  of a product at the same time by sending ($item_variation = -1) - jstn
      unset($this->items[$store_id][$item_id]);
    } else {
      //Delete only selected variation
      if (sizeof($this->items[$store_id][$item_id]['variation']) > 1) {
        //If there are multiple options for this $item_id,
        //  delete only selected variation
        unset($this->items[$store_id][$item_id]['variation'][$item_variation]);
      } else {
        //If this is the last variation for this $item_id,
        //  delete the entire item
        unset($this->items[$store_id][$item_id]);
      }
      //Instead of above, we could set variation QTY to zero, but the array
      //  will grow very large if a lot of products are added and removed - jstn
      /*
      $this->items[$item_id]['variation'][$item_variation]['qty'] = 0;
      */
    }
    if (sizeof($this->items[$store_id]) < 1) {
      //removed last item from store, remove store
      unset($this->items[$store_id]);
    }
    $this->_update_total();
  }//del_item()
  
  //EMPTY THE CART
  function empty_cart() {
    $this->total = 0;
    $this->itemcount = 0;
    $this->items = array();
  }//empty_cart()
  
  //INTERNAL FUNCTION WHICH RECALCULATES COMBINED CART TOTALS
  function _update_total() {
    $this->itemcount = 0;
    $this->total = 0;
    if (sizeof($this->items > 0)) {
      foreach($this->items as $store) {
        foreach($store as $item) {

        $variation_price = 0;
        foreach($item['variation'] as $variation) {
          $option_price = 0;
          foreach($variation['options'] as $option) {
            $option_price += $option['price'];
          }
          $variation_price += ($item['price'] + $option_price) * $variation['qty'];
          
          //TOTAL NUMBER OF ITEMS IN CART
          $this->itemcount += $variation['qty'];
          //ORIGINAL wfCart COUNTED TOTAL NUMBER OF LINE ITEMS
          //  This could be implemented IN ADDITION TO above total by declaring
          //  another variable in the jcart class and using the following - jstn
          /*
          $this->itemcount++;
          */
          //  The placement of this line is significant. Where it is now, it
          //  will count the number of unique variations of all products. Inside
          //  the foreach() directly above, count total number of all items.
          //  Outside of the current loop, will count only number of product
          //  IDs, but not ID variations.
        }
        $this->total += $variation_price;
        
        }
        
      }
    }
  }//_update_total()
  
/* ************************************************************************** */
  
	// PROCESS AND DISPLAY CART
	function display_cart($jcart)
		{
		// JCART ARRAY HOLDS USER CONFIG SETTINGS
		extract($jcart);

		// ASSIGN USER CONFIG VALUES AS POST VAR LITERAL INDICES
		// INDICES ARE THE HTML NAME ATTRIBUTES FROM THE USERS ADD-TO-CART FORM
		$store_id = $_POST[$store_id];
		  if ($store_id == "undefined") { $store_id = 0; }
		$item_id = $_POST[$item_id];
		$item_qty = $_POST[$item_qty];
		$item_price = $_POST[$item_price];
		$item_name = $_POST[$item_name];
    $item_option = $_POST[$item_options];
      if ($item_option == 'undefined') { $item_option = array(); }

		// ADD AN ITEM
		if ($_POST[$item_add])
			{
//
//			$item_added = $this->add_item($item_id, $item_qty, $item_price, $item_name);
      $options = $item_option;
/* USE THIS BLOCK FOR STANDARD ARRAY FORMATTING */
      // The following if statment block ensures proper formatting of product
      //  options. It also tries to extract price modifiers for each option. If
      //  no price was provided, a price of zero is explicitly stated.
      if (count($options) > 0) {
        foreach ($options as $key => &$option) {
          if ($option['value']) {
            if (!$option['price']) { 
              $pos = strrpos($option['value'], ','); //could use explode() but there may be multiple commas - jstn
              if ($pos !== false) {
                $option['price'] = substr($option['value'],$pos+1);
                $option['value'] = substr($option['value'],0,$pos);
                if ($pos == 0) {
                  $option['value'] = $option['price'];
                }
                //double check price is valid
                if (preg_match('/^[-|+]?\d+(?:\.\d+)?$/', $option['price']) === (0|false)) { $option['value'] = $option['value'] . "," . $option['price']; $option['price'] = "0.00"; }
              } else {
                $option['price'] = "0.00";
              }
            } else {
              //double check price is valid
              if (preg_match('/^[-|+]?\d+(?:\.\d+)?$/', $option['price']) === (0|false)) { $option['price'] = "0.00"; }
            }
          } else {
            //If a CHECKBOX is used, but not selected, ['value'] will be empty,
            //  so we remove the blank option from the array
            unset($options[$key]);
          }
        }
        unset($option);
      }
/* END STANDARD BLOCK */
      $options['item-name'] = $item_name;
      $item_added = $this->add_item($store_id, $item_id, $item_qty, $item_price, $options);
//
			// IF NOT TRUE THE ADD ITEM FUNCTION RETURNS THE ERROR TYPE
			if ($item_added !== true)
				{
				$error_type = $item_added;
				switch($error_type)
					{
					case 'qty':
						$error_message = $text['quantity_error'];
						break;
					case 'price':
						$error_message = $text['price_error'];
						break;
					}
				}
			}

		// UPDATE A SINGLE ITEM
		// CHECKING POST VALUE AGAINST $text ARRAY FAILS?? HAVE TO CHECK AGAINST $jcart ARRAY
		if ($_POST['jcart_update_item'] == $jcart['text']['update_button'])
			{
			$which = explode(",", $_POST['item_id']);
			$item_updated = $this->update_item($which[2], $which[0], $which[1], $_POST['item_qty']);
			unset($which);
			if ($item_updated !== true)
				{
				$error_message = $text['quantity_error'];
				}
			}

		// UPDATE ALL ITEMS IN THE CART
		if($_POST['jcart_update_cart'] || $_POST['jcart_checkout'])
			{
			$cart_updated = $this->update_cart();
			if ($cart_updated !== true)
				{
				$error_message = $text['quantity_error'];
				}
			}

		// REMOVE AN ITEM
		if($_GET['jcart_remove'] && !$_POST[$item_add] && !$_POST['jcart_update_cart'] && !$_POST['jcart_check_out'])
			{
			$which = explode(",", $_GET['jcart_remove']);
			$this->del_item($which[2], $which[0], $which[1]);
			}

		// EMPTY THE CART
		if($_POST['jcart_empty'])
			{
			$this->empty_cart();
			}

		// DETERMINE WHICH TEXT TO USE FOR THE NUMBER OF ITEMS IN THE CART
		if ($this->itemcount >= 0)
			{
			$text['items_in_cart'] = $text['multiple_items'];
			}
		if ($this->itemcount == 1)
			{
			$text['items_in_cart'] = $text['single_item'];
			}

		// DETERMINE IF THIS IS THE CHECKOUT PAGE
		// WE FIRST CHECK THE REQUEST URI AGAINST THE USER CONFIG CHECKOUT (SET WHEN THE VISITOR FIRST CLICKS CHECKOUT)
		// WE ALSO CHECK FOR THE REQUEST VAR SENT FROM HIDDEN INPUT SENT BY AJAX REQUEST (SET WHEN VISITOR HAS JAVASCRIPT ENABLED AND UPDATES AN ITEM QTY)
		$is_checkout = strpos($_SERVER['REQUEST_URI'], $form_action);
		if ($is_checkout !== false || $_REQUEST['jcart_is_checkout'] == 'true')
			{
			$is_checkout = true;
			}
		else
			{
			$is_checkout = false;
			}

		// OVERWRITE THE CONFIG FORM ACTION TO POST TO jcart-gateway.php INSTEAD OF POSTING BACK TO CHECKOUT PAGE
		// THIS ALSO ALLOWS US TO VALIDATE PRICES BEFORE SENDING CART CONTENTS TO PAYPAL
		if ($is_checkout == true)
			{
			$form_action = $path . 'jcart-gateway.php';
			}
		//REPLACE PREVIOUS BLOCK WITH BELOW FOR WordPress Plugin USE
		/*
		if ($is_checkout == true)
			{
			$form_action = WP_PLUGIN_URL . '/jcart/jcart-gateway.php';
			}
		else
			{
			$form_action = '/' . $form_action;
			}
		*/
		
//DEPRECIATED as of jCart v1.2
/*
		// DEFAULT INPUT TYPE
		// CAN BE OVERRIDDEN IF USER SETS PATHS FOR BUTTON IMAGES
		$input_type = 'submit';
*/

		// IF THIS ERROR IS TRUE THE VISITOR UPDATED THE CART FROM THE CHECKOUT PAGE USING AN INVALID PRICE FORMAT
		// PASSED AS A SESSION VAR SINCE THE CHECKOUT PAGE USES A HEADER REDIRECT
		// IF PASSED VIA GET THE QUERY STRING STAYS SET EVEN AFTER SUBSEQUENT POST REQUESTS
		if ($_SESSION['quantity_error'] == true)
			{
			$error_message = $text['quantity_error'];
			unset($_SESSION['quantity_error']);
			}

		// SET CURRENCY SYMBOL BASED ON SELECTED CURRENCY CODE
		// Wikipedia is a good source for actual symbols (some not available through
		//  escape codes) http://en.wikipedia.org/wiki/Currency_sign - jstn
		switch($jcart['paypal_currency'])
			{
			case 'EUR':
				$text['currency_symbol'] = '&#128;';
				break;
			case 'GBP':
				$text['currency_symbol'] = '&#163;';
				break;
			case 'JPY':
				$text['currency_symbol'] = '&#165;';
				break;
			case 'CHF':
				$text['currency_symbol'] = 'CHF&nbsp;'; // SwF, SFr, S?
				break;
			case 'SEK':
			case 'DKK':
			case 'NOK':
				$text['currency_symbol'] = 'kr&nbsp;';
				break;
			case 'PLN':
				$text['currency_symbol'] = 'z&#322;&nbsp;';
				break;
			case 'HUF':
				$text['currency_symbol'] = 'Ft&nbsp;';
				break;
			case 'CZK':
				$text['currency_symbol'] = 'K&#269;&nbsp;';
				break;
			case 'ILS':
				$text['currency_symbol'] = '&#8362;&nbsp;';
				break;
			case 'AUD':
			case 'CAD':
			case 'USD':
			case 'NZD':
			case 'HKD':
			case 'SGD':
			case 'MXN':
			default:
				$text['currency_symbol'] = '$';
				break;
			}

		// OUTPUT THE CART

		// IF THERE'S AN ERROR MESSAGE WRAP IT IN SOME HTML
		if ($error_message)
			{
			$error_message = "<p class='jcart-error'>$error_message</p>";
			}

		// Make this a $jcart['template'] variable? - jstn
		include_once('jcart-template.php');
		
		}//display_cart()
}//class jcart{}
