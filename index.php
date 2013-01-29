<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Its a stocktwits API test page </title>
<style type="text/css">
h1 {
	text-align: center;
}
body {
  //background: url('/images/IMG_7467.jpg') no-repeat;
  //background-size: 100%;
}
/* search form 
-------------------------------------- */
.searchform {
	display: inline-block;
	zoom: 1; /* ie7 hack for display:inline-block */
	*display: inline;
	border: solid 1px #d2d2d2;
	padding: 3px 5px;
	
	-webkit-border-radius: 2em;
	-moz-border-radius: 2em;
	border-radius: 2em;

	-webkit-box-shadow: 0 1px 0px rgba(0,0,0,.1);
	-moz-box-shadow: 0 1px 0px rgba(0,0,0,.1);
	box-shadow: 0 1px 0px rgba(0,0,0,.1);

	background: #f1f1f1;
	background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ededed));
	background: -moz-linear-gradient(top,  #fff,  #ededed);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed'); /* ie7 */
	-ms-filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed'); /* ie8 */
}
.searchform input {
	font: normal 12px/100% Arial, Helvetica, sans-serif;
}
.searchform .neighborhood {
	background: #fff;
	padding: 6px 6px 6px 8px;
	width: 400px;
	border: solid 1px #bcbbbb;
	outline: none;

	-webkit-border-radius: 2em;
	-moz-border-radius: 2em;
	border-radius: 2em;

	-moz-box-shadow: inset 0 1px 2px rgba(0,0,0,.2);
	-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.2);
	box-shadow: inset 0 1px 2px rgba(0,0,0,.2);
}
.searchform .searchbutton {
	color: #fff;
	border: solid 1px #494949;
	font-size: 11px;
	height: 27px;
	width: 27px;
	text-shadow: 0 1px 1px rgba(0,0,0,.6);

	-webkit-border-radius: 2em;
	-moz-border-radius: 2em;
	border-radius: 2em;

	background: #5f5f5f;
	background: -webkit-gradient(linear, left top, left bottom, from(#9e9e9e), to(#454545));
	background: -moz-linear-gradient(top,  #9e9e9e,  #454545);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#9e9e9e', endColorstr='#454545'); /* ie7 */
	-ms-filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#9e9e9e', endColorstr='#454545'); /* ie8 */
}
#logo {
  margin-bottom: 1em;
}

#tagline {
  font-size: 54px;
  font-style: italic;
// color: white;
  margin-bottom: 3em;
}

</style>
</head>

<body>

<div class="container">
<div class="header"></a> 
    <!-- end .header --></div>
<div class="content" style="text-align:center">
  <p id="tagline">Stocktwits Test</p>

    
    
		<?php
        //error_reporting(E_ALL);
        //ini_set("display_errors", 1);
        
        //You can find the below at https://stocktwits.com/developers/apps
        
        //Declare your key, secret key and callback URL variables (Without these, the api wont work at all)
        $consumerKey = "yourclientkey";
        $clientSecret = "yourclientsecret";
        $redirectUri="http://your.com/index.php";
        
        //Include the Stocktwits class
        
        require_once('stocktwits.php');
        
        //And some code!
        session_start();
        $stocktwits = new Stocktwits($consumerKey, $clientSecret, $redirectUri );

        // No code in get or access token in session
        if ((!isset($_GET['code'])) and (!isset($_SESSION['access_token']))){
        $results = $stocktwits->generateAuthorizeUrl();
        echo '<span style="font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 36px;">Log in using ';
        echo "<a href=\"$results\">Stocktwits</a>";
        } else if((isset($_GET['code'])) and (!isset($_SESSION['access_token']))) {
        	// if has a code, but no access token, get access token
        	echo ' </span>';
        $code = $_GET['code'];

        // Get the access token from code
        $accesstoken = $stocktwits->getAccessToken($code);
        echo '<br><span style="font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 36px;">';
        
        // Turn the returned token into an associative array
        $accesstoken = json_decode($accesstoken, true);
        
        // Put access token into session
        $_SESSION['access_token'] = $accesstoken['access_token'];
        $_SESSION['scope'] = $accesstoken['scope'];
        $_SESSION['user_id'] = $accesstoken['user_id'];
        $_SESSION['username'] = $accesstoken['username'];

        // get rid of the code in the url and update so that the session is correctly set with no errors
        echo'<script type="text/javascript">
		<!--
		window.location = "'.$redirectUri.'"
		//-->
		</script>';
        } else if(isset($_SESSION['access_token'])) {
        	// Great, now we have an access token, lets do some stuff with it.
        	// to find what you have in session print_r($_SESSION);

        	// set the session to class variables.
        	$stocktwits->access_token = $_SESSION['access_token'];
  			$stocktwits->username = $_SESSION['username'];
  			$stocktwits->userid = $_SESSION['user_id'];

  			//Get the logged in users profile with the class's function getProfile(), username must be set already
  			$currentusersprofile =  json_decode($stocktwits->getProfile($stocktwits->userid));

  			// You can also get another users profile by entering their ID or name, dont forget to turn into an array with decode
  			$stocktwitsprofile = json_decode($stocktwits->getProfile(170), true);
  			$howardsprofile = json_decode($stocktwits->getProfile('howardlindzon'), true);

  			// getting the authenticated user's streams is as simple as getStream(nameofstream), home is default, 
  			// You will need partner level access, which you can get by applying at http://stocktwits.com/developers/contact

  			// Some sample streams for your ease, to find more go to http://stocktwits.com/developers/docs/api
  			$currentUsersHomeStream = json_decode($stocktwits->getStream(), true);
  			$currentUsersFriendsStream = json_decode($stocktwits->getStream('friends'), true);
  			$currentUsersMentionsStream = json_decode($stocktwits->getStream('mentions'), true);
  			$currentUsersDirectStream = json_decode($stocktwits->getStream('direct'), true);

  			//There are three types of search, general, symbol, and user

  			// Stock symbols use searchSymbol('name')
  			$aStockSymbolSearch = json_decode($stocktwits->searchSymbol('aapl'), true);
  			$aPersonSearch = json_decode($stocktwits->searchUser('howardlindzon'), true);
  			$aGeneralSearch = json_decode($stocktwits->searchGeneral('howardlindzon'), true);
  			
  			// Messages can be sent with message($body, $sentiment = null, $in_reply_to_message_id = null, $chart = null)
  			// this means you only need body to send it, null values must fill in blanks otherwise

  			$body = 'I am testing the stocktwits API! Hi @jayzalowitz';
  			$sentiment ="bullish"; // Acceptable values: bullish, bearish, neutral. Defaults to neutral. (Optional)
  			$in_reply_to_message_id ="11743204";
  			$chart = "http://i.imgur.com/vMlZa.gif"; // urls work best here
  			$message = json_decode($stocktwits->message($body,$sentiment,$in_reply_to_message_id,$chart), true);

  			// make the page all good looking
  			echo '<br><span style="text-align:left;font-family: Arial, Helvetica, sans-serif; color: black; font-weight: bold; font-size: 20px;">';
        	// prettyprint
  			echo '<pre>';
  			//This is an easy testing grounds, change to see what variable you want to see.
  			print_r($currentusersprofile);
        	echo '</pre>';
        }
        
        ?>
    
     </span>
    <div>
  </div>
  <div class="footer">
  <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
</html>

