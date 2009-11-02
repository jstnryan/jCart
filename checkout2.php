<?php

// INCLUDE JCART BEFORE SESSION START
include 'jcart/jcart.php';

// START SESSION
session_start();

// INITIALIZE JCART AFTER SESSION START
$cart =& $_SESSION['jcart']; if(!is_object($cart)) $cart = new jcart();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Customer Info</title>
	</head>
	<body>
		<div id="form">
			<form method="post" action="<?php echo $jcart['path'] . 'jcart-gateway.php'; ?>" class="jcart-checkout">
				<fieldset>
          <input type="hidden" name="checkout-step" value="2" />
          <label for="customer_first_name">First Name:</label><br /><input type="text" name="customer_first_name" /><br />
          <label for="customer_last_name">Last Name:</label><br /><input type="text" name="customer_last_name" /><br />
          <label for="customer_address_one">Address:</label><br /><input type="text" name="customer_address_one" /><br />
          <input type="text" name="customer_address_two" /><br />
          <label for="customer_city">City:</label><br /><input type="text" name="customer_city" /><br />
          <label for="customer_state">State:</label><br />
            <select name="customer_state"> 
            <option value="" selected="selected">Select a State</option> 
            <option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option>
            <option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
            </select><br />
          <label for="customer_zip">Zip Code:</label><br /><input type="text" name="customer_zip" /><br />
          <label for="customer_phone">Phone:</label><br /><input type="text" name="customer_phone" /><br />
          <label for="customer_email">Email:</label><br /><input type="text" name="customer_email" /><br />

					<br /><input type="submit" name="jcart-checkout2-submit" value="Continue" class="button" />
				</fieldset>
			</form>
		</div>
	</body>
</html>
