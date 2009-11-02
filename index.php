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

		<title>jCart - Free Ajax/PHP shopping cart</title>

		<style type="text/css">
			* { margin:0; padding:0; }

			html { background:#fff; font-size:62.5%; }
			body { font-size:1.5em; }

			#wrapper { margin:0 auto; width:890px; border:solid 1px #ccc; padding:5px; background:#efefef; overflow:auto; }

			h2 { margin:0; clear:both; }

			#sidebar { width:35%; float:right; }

			#content  { width:64%; float:left; }

			.jcart { margin:0; padding:0 5px; border:dashed 1px #66cc66; float:left; background:#fff; text-align:center;  min-height:200px; }
			.jcart ul { margin:0; list-style:none; padding:0; text-align:left; }
			.jcart .bulk {margin-left: 10px; font-size:.5em;}
			.jcart fieldset { border:0; }
			.jcart strong { color:#000066; }
			.jcart .button { margin:0px; padding:0px;width:80%; }

			fieldset { border:0; }
			#paypal-button { display:block; padding:10px; margin:20px auto; }

			.clear { clear:both; }
			
			pre {font-size:10px;line-height:9px;}
		</style>

		<link rel="stylesheet" type="text/css" media="screen, projection" href="jcart/jcart.css" />

	</head>
	<body>
		<div id="wrapper">
			<h2>Demo Store (no ID)</h2>

			<div id="sidebar">
<?php
echo "<pre>";
echo print_r($_POST);
echo "</pre>";
?>

<?php if ($_GET['js'] == "false") { ?>
<p style="text-align:center; padding-top:20px;">Try it <a href="?js=true">with</a> javascript.</p>
<?php } else { ?>						
<p style="text-align:center; padding-top:20px;">Try it <a href="?js=false">without</a> javascript.</p>
<?php } ?>
<!--
<form method='post' action='jcart/jcart-gateway.php'>
  <input type='hidden' id='jcart-is-checkout' name='jcart_is_checkout' value='true' /> 
	<input type='hidden' id='jcart-checkout-page' name='jcart_checkout_page' value='http://lampserv:81/index.php' />
<input type='submit' name='jcart_empty' value='empty' class='jcart-button' />
</form>
-->
			
        <!-- BEGIN JCART --><div id='jcart'>
				<?php $cart->display_cart($jcart);?>
        </div><!-- END JCART -->
			</div>

			<div id="content">
				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="id1" />
						<input type="hidden" name="my-item-name" value="Soccer Ball" />
						<input type="hidden" name="my-item-price" value="25.00" />

						<ul>
							<li><strong>Soccer Ball</strong></li>
							<li>Price: $25.00</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="id2" />
						<input type="hidden" name="my-item-name" value="Baseball Mitt" />
						<input type="hidden" name="my-item-price" value="19.50" />

						<ul>
							<li><strong>Baseball Mitt</strong></li>
							<li>Price: $19.50</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[19][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[19][value]">
    						  <option>Small</option>
    						  <option>Medium</option>
    						  <option>Large</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="id3-hockey-stick" />
						<input type="hidden" name="my-item-name" value="Hockey Stick" />
						<input type="hidden" name="my-item-price" value="33.25" />

						<ul>
							<li><strong>Hockey Stick</strong></li>
							<li>Price: $33.25</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Color" />
							 <label>Color: 
    						<select name="my-item-option[0][value]">
    						  <option>Red</option>
    						  <option>Blue</option>
    						  <option value="Gold,10.00">Gold (+$10)</option>
    						</select>
    					 </label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[1][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[1][value]">
    						  <option>Small</option>
    						  <option value="Medium,3.00">Medium (+$3)</option>
    						  <option value="Large,5.00">Large (+$5)</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>
				
<?php /*
      // THE FOLLOWING BLOCK OF FORMS (COMMENTED BY PHP BLOCKS) GIVE EXTENDED
      // EXAMPLES OF HOW TO USE THE PRODUCT OPTIONS FEATURES ?>
				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="newest-test" />
						<input type="hidden" name="my-item-name" value="Text Input Test" />
						<input type="hidden" name="my-item-price" value="99.99" />

						<ul>
							<li><strong>Text Input Test</strong></li>
							<li>Price: $99.99</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Text" />
							 <label>User Input: 
    						<br /><input type="text" name="my-item-option[0][value]" />
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="soft-taco" />
						<input type="hidden" name="my-item-name" value="Soft Taco" />
						<input type="hidden" name="my-item-price" value="0.99" />

						<ul>
							<li><strong>Soft Taco</strong></li>
							<li>Price: $0.99</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Sour Cream" /
							 <!-- <input type="hidden" name="my-item-option[0][price]" value="0.10" /> -->
							 <label>Sour Cream: 
    						<input type="checkbox" name="my-item-option[0][value]" value=",0.10" />
    					 </label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[1][name]" value="Guacamole" />
							 <!-- <input type="hidden" name="my-item-option[1][price]" value="0.20" /> -->
							 <label>Guacamole: 
    						<input type="checkbox" name="my-item-option[1][value]" value=",0.20" />
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="soft-taco-2" />
						<input type="hidden" name="my-item-name" value="Soft Taco 2" />
						<input type="hidden" name="my-item-price" value="1.99" />

						<ul>
							<li><strong>Soft Taco 2</strong></li>
							<li>Price: $1.99</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 Extras:<br />
							 <input type="hidden" name="my-item-option[0][name]" value="Extras" />
							 <input type="hidden" name="my-item-option[0][price]" value="0.10" />
							 <label>Sour Cream: 
    						<input type="checkbox" name="my-item-option[0][value]" value="Sour Cream" />
    					 </label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[1][name]" value="Extras" />
							 <!-- <input type="hidden" name="my-item-option[1][price]" value="0.20" /> -->
							 <label>Guacamole: 
    						<input type="checkbox" name="my-item-option[1][value]" value="Guacamole,0.20" />
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
						<input type="hidden" name="my-item-id" value="burger" />
						<input type="hidden" name="my-item-name" value="Hamburger" />
						<input type="hidden" name="my-item-price" value="5.00" />

						<ul>
							<li><strong>Hamburger</strong></li>
							<li>Price: $5.00</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Type" />
							 <!-- <input type="hidden" name="my-item-option[0][price]" value="0.10" /> -->
							 Type:<br />
    						<label><input type="radio" name="my-item-option[0][value]" value="Beef" checked="checked" />Beef<label /><br />
    						<label><input type="radio" name="my-item-option[0][value]" value="Chicken,2.00" />Chicken (+$2.00)<label /><br />
    						<label><input type="radio" name="my-item-option[0][value]" value="Veggie,1.50" />Veggie (+$1.50)<label />
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>
<?php */ ?>				
<!-- *********************************************************************** -->
        
        <h2>Demo Store (id "1")</h2>
				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="1,First Store Indeed" />
						<input type="hidden" name="my-item-id" value="id1" />
						<input type="hidden" name="my-item-name" value="Soccer Ball" />
						<input type="hidden" name="my-item-price" value="25.00" />
  						<input type="hidden" name="my-item-bulk" value="6,20.00;11,15.00;16,10.00" />

						<ul>
							<li><strong>Soccer Ball</strong></li>
							<li>Price: $25.00</li>
							<li>Bulk Price:
                <ul class="bulk">
                  <li>6+ $20.00</li>
                  <li>11+ $15.00</li>
                  <li>16+ $10.00</li>
                </ul>
              </li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="1,First Store Indeed" />
						<input type="hidden" name="my-item-id" value="id2" />
						<input type="hidden" name="my-item-name" value="Baseball Mitt" />
						<input type="hidden" name="my-item-price" value="19.50" />
  						<input type="hidden" name="my-item-bulk" value="80,11.00;20,17.00;60,13.00;40,15.00;100,9.00" />

						<ul>
							<li><strong>Baseball Mitt</strong></li>
							<li>Price: $19.50</li>
							<li>Bulk Price:
                <ul class="bulk">
                  <li>20+ $17.00</li>
                  <li>40+ $15.00</li>
                  <li>60+ $13.00</li>
                  <li>80+ $11.00</li>
                  <li>100+ $9.00</li>
                </ul>
              </li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[19][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[19][value]">
    						  <option>Small</option>
    						  <option>Medium</option>
    						  <option>Large</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="1,First Store Indeed" />
						<input type="hidden" name="my-item-id" value="id3-hockey-stick" />
						<input type="hidden" name="my-item-name" value="Hockey Stick" />
						<input type="hidden" name="my-item-price" value="33.25" />

						<ul>
							<li><strong>Hockey Stick</strong></li>
							<li>Price: $33.25</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Color" />
							 <label>Color: 
    						<select name="my-item-option[0][value]">
    						  <option>Red</option>
    						  <option>Blue</option>
    						  <option value="Gold,10.00">Gold (+$10)</option>
    						</select>
    					 </label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[1][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[1][value]">
    						  <option>Small</option>
    						  <option value="Medium,3.00">Medium (+$3)</option>
    						  <option value="Large,5.00">Large (+$5)</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>
				
<!-- *********************************************************************** -->
        
        <h2>Demo Store (id "2")</h2>
				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="2,The Second Store" />
						<input type="hidden" name="my-item-id" value="id1" />
						<input type="hidden" name="my-item-name" value="Soccer Ball" />
						<input type="hidden" name="my-item-price" value="25.00" />

						<ul>
							<li><strong>Soccer Ball</strong></li>
							<li>Price: $25.00</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="2,The Second Store" />
						<input type="hidden" name="my-item-id" value="id2" />
						<input type="hidden" name="my-item-name" value="Baseball Mitt" />
						<input type="hidden" name="my-item-price" value="19.50" />

						<ul>
							<li><strong>Baseball Mitt</strong></li>
							<li>Price: $19.50</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[19][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[19][value]">
    						  <option>Small</option>
    						  <option>Medium</option>
    						  <option>Large</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>

				<form method="post" action="" class="jcart">
					<fieldset>
            <input type="hidden" name="my-store-id" value="2,The Second Store" />
						<input type="hidden" name="my-item-id" value="id3-hockey-stick" />
						<input type="hidden" name="my-item-name" value="Hockey Stick" />
						<input type="hidden" name="my-item-price" value="33.25" />

						<ul>
							<li><strong>Hockey Stick</strong></li>
							<li>Price: $33.25</li>
							<li>
								<label>Qty: <input type="text" name="my-item-qty" value="1" size="3" /></label>
							</li>
							
							<li>
							 <input type="hidden" name="my-item-option[0][name]" value="Color" />
							 <label>Color: 
    						<select name="my-item-option[0][value]">
    						  <option>Red</option>
    						  <option>Blue</option>
    						  <option value="Gold,10.00">Gold (+$10)</option>
    						</select>
    					 </label>
							</li>
							<li>
							 <input type="hidden" name="my-item-option[1][name]" value="Size" />
							 <label>Size: 
    						<select name="my-item-option[1][value]">
    						  <option>Small</option>
    						  <option value="Medium,3.00">Medium (+$3)</option>
    						  <option value="Large,5.00">Large (+$5)</option>
    						  <option value="Extra Large,7.00">XLarg (+$7)</option>
    						</select>
    					 </label>
							</li>
							
						</ul>

						<input type="submit" name="my-add-button" value="add to cart" class="button" />
					</fieldset>
				</form>
				
			</div>
		</div>
		
<?php if ($_GET['js'] == "false") { ?>
    <!-- JavaScript temporarily disabled -->
<?php } else { ?>
		<script type="text/javascript" src="jcart/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="jcart/jcart-javascript.php"></script>
<?php } ?>

	</body>
</html>
