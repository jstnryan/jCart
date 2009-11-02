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

			html { background:#fff; font-family:trebuchet ms, candara, sans-serif; font-size:62.5%; }
			body { font-size:1.5em; }

			#wrapper { margin:30px auto 250px auto; width:890px; border:solid 1px #ccc; padding:30px; background:#efefef; }

			h2 { margin-bottom:1em; }

			#sidebar { width:35%; float:right; }

			#content  { width:60%; }

			.jcart { margin:0 20px 20px 0; padding-top:20px; border:dashed 2px #66cc66; float:left; background:#fff; text-align:center; }
			.jcart ul { margin:0; list-style:none; padding:0 20px; text-align:left; }
			.jcart fieldset { border:0; }
			.jcart strong { color:#000066; }
			.jcart .button { margin:20px; padding:5px; }

			fieldset { border:0; }
			#paypal-button { display:block; padding:10px; margin:20px auto; }

			.clear { clear:both; }
		</style>

		<link rel="stylesheet" type="text/css" media="screen, projection" href="jcart/jcart.css" />
	</head>
	<body>
		<div id="wrapper">
			<h2>Demo Checkout</h2>

			<div id="sidebar">
			</div>

			<div id="content">
				<?php $cart->display_cart($jcart);?>

				<p><a href="store.php">&larr; Continue shopping</a></p>

				<div class="clear"></div>
			</div>
		</div>

		<script type="text/javascript" src="jcart/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="jcart/jcart-javascript.min.php"></script>
	</body>
</html>
