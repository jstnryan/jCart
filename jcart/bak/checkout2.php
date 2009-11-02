<?php
ob_start();
//make sure the page is scured with https
if ('on' != $_SERVER['HTTPS'])
    {
    header('Location: https://www.sativaonline.com/App/checkout2.php');
    }
//connect to the database
include_once('/home2/sativaon/dbConnect/connectfunc.php');
// INCLUDE JCART BEFORE SESSION START
include 'jcart/jcart.php';

// START SESSION
session_start();
// INITIALIZE JCART AFTER SESSION START
$cart=&$_SESSION['jcart'];

if (!is_object($cart))
    $cart=new jcart();
	


/* 
heres some test info so you don't have to fill out all the fields everytime you want to test this script
later I will change this so that it will only display if the user has entered the data  via $_POST and if they return to this page 
the data will be sticky and they can edit or update this info 
*/
$_SESSION['customer_first_name'] = "Joe";
$_SESSION['customer_last_name'] = "Shmoe";
$_SESSION['customer_address_one'] = "1600 Pensilvania ave";
$_SESSION['customer_address_two'] = "Suite #2";
$_SESSION['customer_city'] = "San Francisco";
$_SESSION['customer_state'] ="CA";
$_SESSION['customer_zip'] = "12345";
$_SESSION['customer_phone'] = "1234567890";
$_SESSION['customer_email'] = "admin@sativaonline.com";
$_SESSION['dispensary_id'] = "1";
$_SESSION['invoice_description'] = "all kinds of products";
$_SESSION['invoice_total'] = "12.50";
$_SESSION['invoice_date'] = date('M d Y');
$_SESSION['invoice_time'] = date('g:i:s');
$_SESSION['invoice_time_meridian'] = date('A');




//this is a good email validation function
//it returns true if all email passes validation
//it gets passed one variable 
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || 
 ↪checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}


//heres is an action that might be useful to you it checks for missing form data when it is submitted 
//then checks the email to see if it is valid
//then inserts the data into the appropriate table 
//then emails the appropriate dispensary to inform them of an order
//the last two parts can be in a "for() loop" you can change it any way that suits you best but the insert SQL should look like 
//like what is inside this action Call me if you need help deciphering this 


if (array_key_exists("some_Btn",$_POST)){					 
$expected=array(
'customer_first_name',
'customer_last_name',
'customer_address_one',
'customer_address_two',
'customer_city',
'customer_state',
'customer_zip',
'customer_phone',
'customer_email');

$required=array(
'customer_first_name',
'customer_last_name',
'customer_address_one',
'customer_address_two',
'customer_city',
'customer_state',
'customer_zip',
'customer_phone',
'customer_email');

    $missing=array();

    foreach ($_POST as $key => $value)
        {
        $temp = is_array($value) ? $value : trim($value);

        if (empty($temp) && in_array($key, $required))
            {
            array_push($missing, $key);
            }
        elseif (in_array($key, $expected))
            {
            ${$key}=$temp;
            }
        }
	//If all fields have been filled out correctly move on with validation.
    if (empty($missing))
        {
				//Pass the email address though the email validation function.
				if(validEmail($_POST['customer_email']) === true){
					// Do this if the email address passes validation.
					
					//you will probably want to use a for loop in this section so that this action takes place for each dispensary that has an item in the users shopping cart but this is what it looks like without a for loop change the email address to your email address if you wan to test it 
							if($stmt = $mysql -> prepare("INSERT INTO invoices(
																				customer_first_name,
																				customer_last_name,
																				customer_address_one,
																				customer_address_two,
																				customer_city,
																				customer_state,
																				customer_zip,
																				customer_phone,
																				customer_email,
																				dispensary_id,
																				invoice_description,
																				invoice_total,
																				order_date,
																				order_time,
																				order_time_meridian)
																  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
										  $stmt -> bind_param("sssssssssssssss", 
																	  $_POST['customer_first_name'],
																	  $_POST['customer_last_name'],
																	  $_POST['customer_address_one'],
																	  $_POST['customer_address_two'],
																	  $_POST['customer_city'],
																	  $_POST['customer_state'],
																	  $_POST['customer_zip'],
																	  $_POST['customer_phone'],
																	  $_POST['customer_email'],
																	  $_SESSION['dispensary_id'],
																	  $_SESSION['invoice_description'],
																	  $_SESSION['invoice_total'],
										                              $_SESSION['invoice_date'],
																	  $_SESSION['invoice_time'],
																	  $_SESSION['invoice_time_meridian']);
										  $stmt -> execute();
										  $stmt -> close();
											$message="An order has been place on Sativaonline.com for you. You can manage and view your order by login in here. Here are the details of that order.\n\n 
											'$_POST[customer_first_name]',\n 
											'$_POST[customer_last_name]',\n 
											'$_POST[customer_address_one]',\n 
											'$_POST[customer_address_two]',\n 
											'$_POST[customer_city]',\n 
											'$_POST[customer_state]',\n 
											'$_POST[customer_zip]',\n
											'$_POST[customer_phone]',\n
											'$_POST[customer_email]',\n 
											'$_SESSION[dispensary_id]',\n
											'$_SESSION[invoice_description]',\n
											'$_SESSION[invoice_total]',\n 
											'$_SESSION[invoice_date]',\n 
											'$_SESSION[invoice_time]',\n 
											'$_SESSION[invoice_time_meridian]'"; 
											$mailSent=mail($_POST['customer_email'], "An order has been place on Sativaonline.com", $message, null,'-fadmin@orchid1software.com');
											
											//echo some responce text if needed or return some responce HERE if needed
											$error .="Your order has been emailed. You should receive an email confirmation shortly.";						
					}
					else{
						$error .= "OOps an error has accoured";	
					}
				}
				//Do this if the email address does not pass validation. 
				else{
					$error .=  "Please enter a valid email address. If you continue to get this message, please call the dispensary(s) you                                 are trying to email.";
				}
		}
	//Do this if some fileds have been left blank.
	elseif(!empty($missing)){
			$error .= "Some fields were left blank. Please fill out the form entirely.";
		}
 }

//I created an array so that later the value for the state can be easily made sticky
$states=array
            (
								"AL" => "Alabama",
                                "AK"=>"Alaska",
                                "AZ"=>"Arizona",
                                "AR"=>"Arkansas",
                                 "CA"=>"California",
                                 "CO"=>"Colorado",
                                 "CT"=>"Connecticut",
                                 "DE"=>"Delaware",
                                 "DC"=>"District Of Columbia",
                                 "FL"=>"Florida",
                                 "GA"=>"Georgia",
                                 "HI"=>"Hawaii",
                                 "ID"=>"Idaho",
                                 "IL"=>"Illinois",
                                 "IN"=>"Indiana",
                                 "IA"=>"Iowa",
                                 "KS"=>"Kansas",
                                 "KY"=>"Kentucky",
                                 "LA"=>"Louisiana",
                                 "ME"=>"Maine",
                                 "MD"=>"Maryland",
                                 "MA"=>"Massachusetts",
                                 "MI"=>"Michigan",
                                 "MN"=>"Minnesota",
                                 "MS"=>"Mississippi",
                                 "MO"=>"Missouri",
                                 "MT"=>"Montana",
                                 "NE"=>"Nebraska",
                                 "NV"=>"Nevada",
                                 "NH"=>"New Hampshire",
                                 "NJ"=>"New Jersey",
                                 "NM"=>"New Mexico",
                                 "NY"=>"New York",
                                 "NC"=>"North Carolina",
                                 "ND"=>"North Dakota",
                                 "OH"=>"Ohio",
                                 "OK"=>"Oklahoma",
                                 "OR"=>"Oregon",
                                 "PA"=>"Pennsylvania",
                                 "RI"=>"Rhode Island",
                                 "SC"=>"South Carolina",
                                 "SD"=>"South Dakota",
                                 "TN"=>"Tennessee",
                                 "TX"=>"Texas",
                                 "UT"=>"Utah",
                                 "VT"=>"Vermont",
                                 "VA"=>"Virginia",
                                 "WA"=>"Washington",
                                 "WV"=>"West Virginia",
                                 "WI"=>"Wisconsin",
                                 "WY"=>"Wyoming");
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml" xml:lang = "en" lang = "en">
    <head>
        <meta http-equiv = "content-type" content = "text/html; charset=utf-8" />

        <title>Customer Info</title>

        <link rel = "shortcut icon" href = "siteImages/favicon.ico" type = "image/x-icon" />

        <link rel = "icon" href = "siteImages/favicon.ico" type = "image/x-icon" />

        <link href = "assets/main.css" rel = "stylesheet" type = "text/css" />
    </head>

    <body>
        <div id = "container">
            <div id = "header"></div>
                <div id = "Navigation">
                    <?php
                    if ($_SESSION['menu'] == 'admin')
                        {
                        include('includes/menu.inc.php');
                        }

                    if (!isset($_SESSION['menu']))
                        {
                        include('includes/visitorsNav.inc.php');
                        }
                    ?>
                </div>
            <div id = "content">
                <div id = "form">
                    <form method = "post" action = "<?php echo $jcart['path'] . 'jcart-gateway.php'; ?>"
                        class = "jcart-checkout">
                        <input type = "hidden" name = "checkout-step" value = "2" />

                        <div>
                            <label for = "customer_first_name">First Name:</label>

                            <input type = "text" name = "customer_first_name" value = "<?php echo $_SESSION['customer_first_name']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_last_name">Last Name:</label>

                            <input type = "text" name = "customer_last_name" value = "<?php echo $_SESSION['customer_last_name']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_address_one">Address:</label>

                            <input type = "text" name = "customer_address_one" value = "<?php echo $_SESSION['customer_address_one']; ?>"/>
						</div>
                        <br />
                        <div>
                        <label for = "customer_address_two">Address 2:</label>
                            <input type = "text" name = "customer_address_two" value = "<?php echo $_SESSION['customer_address_two']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_city">City:</label>

                            <input type = "text" name = "customer_city" value = "<?php echo $_SESSION['customer_city']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_state">State:</label>

                            <select name = "customer_state">
                               <?php
							   	foreach($states as $key=> $value ){
									if($_SESSION['customer_state'] == $key){
										echo "<option value = '$key' selected = 'selected'>$value</option>";	
									}
									else{
										echo "<option value = '$key'>$value</option>";	
									}
									
								}
							   
							   ?>


                            </select>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_zip">Zip Code:</label>

                            <input type = "text" name = "customer_zip" value = "<?php echo $_SESSION['customer_zip']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_phone">Phone:</label>

                            <input type = "text" name = "customer_phone" value = "<?php echo $_SESSION['customer_phone']; ?>"/>
                        </div>

                        <br />

                        <div>
                            <label for = "customer_email">Email:</label>

                            <input type = "text" name = "customer_email" value = "<?php echo $_SESSION['customer_email']; ?>"/>
                        </div>

                        <br />

                        <br />

                        <input type = "submit" name = "jcart-checkout2-submit" value = "Continue" class = "button" />
                    </form>
                </div>
            </div>

            <?php include('includes/footer.inc.php'); ?>
        </div>
    </body>

</html>