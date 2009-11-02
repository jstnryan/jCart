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

<h1>Thank you for your order!</h1>
<a href="/">Return home</a>

<pre><?php echo $_SESSION['email_sent']; unset($_SESSION['email_sent']); ?></pre>

	</body>
</html>
