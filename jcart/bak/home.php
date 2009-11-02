<?php
ob_start();

// INCLUDE JCART BEFORE SESSION START
include 'jcart/jcart.php';
session_start();
include_once('/home2/sativaon/dbConnect/connectfunc.php');

// INITIALIZE JCART AFTER SESSION START
$cart=&$_SESSION['jcart'];

if (!is_object($cart))
    $cart=new jcart();

if (isset($_GET['club_id']) && is_numeric($_GET['club_id']))
    {
    $ref=$_GET['club_id'];

    if ($stmt=$mysql->prepare(
        "SELECT clubName, catagory, clubAddress, clubCity, clubState, clubZip, clubPhone, clubEmail, clubWebsite, coordinates, googleAddress, shortDesc, onSiteMed, delivery, countyOnly, membershipCard, coffeeShop, emailOption, logoImage, moldTest, thcTest,sunOpen, sunClose, monOpen, monClose, tueOpen, tueClose, wedOpen, wedClose, thuOpen, thuClose, friOpen, friClose, satOpen, satClose, googleCalendarCode, catagory FROM dispensaryDetails WHERE clubId= ? LIMIT 1"))
        {
        $stmt->bind_param("s", $ref);
        $stmt->execute();
        $stmt->bind_result($clubName, $catagory, $clubAdd, $clubCity, $clubState, $clubZip, $clubPhone, $clubEmail,
            $clubWebsite, $coordinates, $googAdd, $shortDesc, $smoke, $delivery, $county, $memberCard, $coffee,
            $emailOption, $logo, $mold, $thc, $sunO, $sunC, $monO, $monC, $tueO, $tueC, $wedO, $wedC, $thuO, $thuC,
            $friO, $friC, $satO, $satC, $googleCalendarCode, $catagory);
        $stmt->fetch();
        $stmt->close();
        }

    if ($coordinates == "")
        {
        $noMap=true;
        }
    else
        {
        $noMap=false;
        }
    }

//email function
if (!isset($_SESSION['messagesSent']))
    {
    $_SESSION['messagesSent']=0;
    }

if (array_key_exists('send', $_POST) && $_SESSION['messagesSent'] < 4 && $_POST['sodomfield'] != "today")
    {
    $expected=array
        (
        'name',
        'email',
        'subject',
        'comments'
        );

    $required=array
        (
        'name',
        'email',
        'subject',
        'comments'
        );

    $missing=array();

    foreach ($_GET as $key => $value)
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

    if (empty($missing))
        {
        $name=$_POST['name'];
        $email=$_POST['email'];
        $subject=$_POST['subject'];
        $comments=$_POST['comments'];

        // build the message
        $message="Name: $name\n\n";
        $message.="Email: $email\n\n";
        $message.="Comments: $comments";

        // limit line length to 70 characters
        $message=wordwrap($message, 70);

        // send it
        $mailSent=mail($clubEmail, $subject, $message, null, '-fadmin@sativaonline.com');
        $_SESSION['contactUS']++;
        }
    }
else
    {
    $_SESSION['messagesSent']++;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />

        <title>Sativa Online</title>

        <link rel = "shortcut icon" href = "siteImages/favicon.ico" type = "image/x-icon" />

        <link rel = "icon" href = "siteImages/favicon.ico" type = "image/x-icon" />

        <link rel = "shortcut icon" href = "siteImages/favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" media = "screen, projection" href = "assets/pagestyle.css" />

        <link rel = "stylesheet" type = "text/css" media = "screen, projection" href = "assets/clubframe.css" />

        <script type = "text/javascript"
            src = "http://maps.google.com/maps?file=api&v=2&key=ABQIAAAA7cxltrCBXpI4H-j2IaiiLhQ95EVtkTvDH6S9q5sar0sTfsTRsBSWNsRhv1pVsOgfYKjrhGZAeoezmw">
        </script>

        <script type = "text/javascript" src = "js/jquery.js">
        </script>

        <script type = "text/javascript" src = "jquery.ui-1.5.2/ui/ui.core.js">
        </script>

        <script type = "text/javascript" src = "jquery.ui-1.5.2/ui/ui.tabs.js">
        </script>

        <script type = "text/javascript" src = "js/jquery.validate.js">
        </script>
        <script>
            var coordinates = new GLatLng(<?php echo $coordinates; ?>);

            var windowInfo =
                '<p><?php echo $clubName,"<br />",$clubAdd,"<br />",$clubCity,", ",$clubState," ",$clubZip;?></p>';
            $(document).ready(function()
                {
                $('#sodomfield').val("true");

                $('#input').validate(
                    {
                    rules:
                        {
                        name:
                            {
                            required: true,
                            },
                        email:
                            {
                            email: true,
                            required: true,
                            },
                        subject:
                            {
                            required: true,
                            },
                        comments:
                            {
                            required: true
                            }
                        }
                    }); //validation rules end
                });     // end ready()
        </script>

        <script type = "text/javascript" src = "assets/homeGMap.js">
        </script>
    </head>

    <body onload = "loadMap()" onunLoad = "GUnload()">
        <div id = "container">
            <div id = "header"></div>

            <div id = "content">
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

                <div id = 'clubInfo'>
                    <?php
                    echo "<div id='clubtitle'>";

                    if ($logo != "")
                        {
                        echo "<img src= '$logo'/>";
                        }
                    else
                        {
                        echo $clubName;
                        }

                    echo "</div>";
                    ?>
                </div>

                <div id = "jQueryUITabs1">
                    <ul>
                        <li><a href = "#jQueryUITabs1-1"><span>Main</span></a></li>

                        <?php
                        if (!empty($coordinates) and ($catagory == "Dispensary" or $catagory == "Delivery Service"))
                            {
                            echo '<li><a href="#jQueryUITabs1-2"><span>Maps</span></a></li>';
                            }
                        ?>

                        <li><a href = "#jQueryUITabs1-3"><span>Calendar</span></a></li>

                        <li><a href = "#jQueryUITabs1-5"><span>Documents</span></a></li>

                        <li><a href = "#jQueryUITabs1-6"><span>Contact</span></a></li>
                    </ul>

                    <div id = "jQueryUITabs1-1">
                        <div id = "clubleft">
                            <?php
                            if ($catagory == "Dispensary")
                                {
                                include('includes/clubMenu.inc.php');
                                }
                            elseif ($catagory == "Delivery Service")
                                {
                                include('includes/deliveryMenu.inc.php');
                                }
                            elseif ($catagory == "Nursery")
                                {
                                include('includes/nurseryMenu.inc.php');
                                }
                            ?>
                        </div>

                        <div id = "clubright">
                            <div id = 'clubIcons'>
                                <?php
                                if ($mold != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/mold.png' title='Mold Testing | $clubName tests for mold in their medications.' class='tip'/>";
                                    }

                                if ($thc != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/contents.png' title='Cannabinoid Testing | $clubName tests the potency of the cannabinoids found in thier medicines.' class='tip'/>";
                                    }

                                if ($smoke != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/medication.png' title='Onsite Medicating | $clubName allow onsite medicating.' class='tip'/>";
                                    }

                                if ($delivery != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/delivery.png' title='Delivery | $clubName offers a delivery service.' class='tip'/>";
                                    }

                                if ($county != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/county.png' title='County  Only | $clubName requires you to be a resident of their county to visit.' class='tip'/>";
                                    }

                                if ($memberCard != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/membershipcard.png' title='Membership Card | $clubName requires a membership card to visit.' class='tip'/>";
                                    }

                                if ($clubWebsite != "")
                                    {
                                    echo "<a href = '" . $clubWebsite
                                        . "' ><img src ='siteImages/clubicons/website.png' title='$clubNames Website | Go to the $clubName  Website.' class='tip'  border = '0px'/></a>";
                                    }

                                if ($coffee != "")
                                    {
                                    echo
                                        "<img src ='siteImages/clubicons/coffee.png' title='Coffee Shop | $clubName offers coffee at theire location.' class='tip'/>";
                                    }
                                ?>
                            </div>

                            <?php
                            if ($catagory == "Dispensary" or $catagory == "Delivery Service")
                                {
                                echo "<div class='address'>";

                                echo "$clubAdd<br/>";

                                echo "$clubCity  $clubState $clubZip <br/>";

                                echo "$clubPhone <br/>";

                                echo "</div>";

                                echo "<div class='opHours'>";

                                echo "<ul><li class='opHoursheader'>Hours of Operation</li><li><b>Mon</b> $monO-$monC</li><li><b>Tue</b> $tueO-$tueC</li><li><b>Wed</b> $wedO-$wedC</li><li><b>Thu</b> $thuO-$thuC</li>
<li><b>Fri</b> $friO-$friC</li><li><b>Sat</b> $satO-$satC</li><li><b>Sun</b> $sunO-$sunC</li></ul>";

                                echo "</div>";
                                }
                            ?>

                            <div id = "sidebar">
                                <?php
                                if ($catagory == "Delivery Service")
                                    {
                                    echo "<div id='jcart'>";
                                    $cart->display_cart($jcart);

                                    echo "</div>";
                                    }
                                ?>
                            </div>

                            <div id = "shortDesc">
                                <h3>About

                                <?php echo " ", $clubName ?></h3>

                                <?php
                                echo "<p>";

                                echo $shortDesc;

                                echo "</p>";
                                ?>
                            </div>
                        </div>
                    </div>

                            <?php
                            if (!empty($coordinates) and ($catagory == "Dispensary" or $catagory == "Delivery Service"))
                                {

                                include('Locators/mapAssets/mapJava.php');
                                }
                            ?>

                            <!--calendar  -->

                            <div id = "jQueryUITabs1-3">

                                <?php echo html_entity_decode(stripslashes($googleCalendarCode)); ?>
                            </div>

                            <div id = "jQueryUITabs1-5">

                                <?php
                                if ($stmt=$mysql->prepare(
                                    "SELECT id,documentName, description, documentURL FROM documents WHERE clubId= ? "))
                                    {
                                    $stmt->bind_param("s", $ref);
                                    $stmt->execute();
                                    $stmt->bind_result($doumentId, $documentName, $documentDescription, $documentURL);

                                    while ($stmt->fetch())
                                        {
                                        //process the data for displaying
                                        echo "<div class= 'document' style='width: 750px;'>";

                                        echo "<div class= 'documentLabel'>";

                                        echo "<a style='color: black; font-weight: bold;' href='download.php?file=",
                                            basename($documentURL), "&file_id=" . $doumentId . "' class='clubinfo'>",
                                            $documentName, "</a>";

                                        echo "</div>";

                                        echo "<div class='documentDescription' style='margin-top: 12px;'>",
                                            $documentDescription;

                                        echo "</div>";

                                        echo "</div>";
                                        }
                                    $stmt->close();
                                    }
                                ?>
                            </div>

                            <div id = "jQueryUITabs1-6">
                                <div id = "clubContact">
                                    <?php
                                    if ($emailOption != "")
                                        {
                                        echo
                                            "
<h3>&nbsp;&nbsp;&nbsp;E-Mail<?php echo ' ', $clubName?></h3>

<form id='input' method='post' action=''>

<div>
<label for='name'>&nbsp;&nbsp;&nbsp;&nbsp;Name:</label>
<input name='name' id='name' type='text' class='formbox' maxlength='25'/>
</div>
<br/>
<div>
<label for='email'>&nbsp;&nbsp;&nbsp;&nbsp;Email:</label>
<input name='email' id='email' type='text' class='formbox' maxlength='75'/>
</div>
<br/>
<div>
<label for='club or nursery name'>&nbsp;&nbsp;&nbsp;&nbsp;Subject:</label>
<input type='text' name='subject' id='subject' class='signupTxt' maxlength='40'> 


</div>
<br/>
<label for='comments' class='contactmessagetitle'>&nbsp;&nbsp;&nbsp;Message:</label>
<br/>
<div>
<textarea name='comments' id='comments' cols='43' rows='8' class='commentsbox' maxlength='1000' title='Comments | Send this dispensary an email up to 1,000 characters'></textarea>
</div>
<br/>
<input type='hidden' name='sodomfield' id='sodomfield' value ='today' readonly/>
<input name='send' id='send' type='submit' class='commentbutton' value='Send Message' />
</form>";
                                        }
                                    else
                                        {
                                        echo
                                            "<img src='http://www.sativaonline.com/App/siteImages/emaildisabled.png'/>";
                                        }
                                    ?>
                                </div>
                            </div>
                </div> <!--end of main tab pages -->
            </div>

                                <?php include('includes/footer.inc.php'); ?>
        </div>

                                        <script type = "text/javascript" src = "jcart/jcart-javascript.php">
                                        </script>

                                        <script type = "text/javascript">
                                            // BeginWebWidget jQuery_UI_Tabs: jQueryUITabs1
                                            jQuery("#jQueryUITabs1 > ul").tabs(
                                                {
                                                event: "click"
                                                });

                                            // EndWebWidget jQuery_UI_Tabs: jQueryUITabs1
                                        </script>
    </body>
</html>