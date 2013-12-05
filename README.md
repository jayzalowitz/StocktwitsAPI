StocktwitsAPI
=============

An api for stocktwits

To setup:

        //Include your app information
        $consumerKey = "yourclientkey";
        $clientSecret = "yourclientsecret";
        $redirectUri="http://your.com/index.php";
        
        //Include the Stocktwits class
        require_once('stocktwits.php');

        // Instanciate the class
        $stocktwits = new Stocktwits($consumerKey, $clientSecret, $redirectUri );

        // thats it!

Examples to use from index.php: 

        //generate auth url
        $stocktwits->generateAuthorizeUrl();
        //get access token from the visiting users code
        $accesstoken = $stocktwits->getAccessToken($_GET['code']);

        // get current users profile
        $currentusersprofile =  json_decode($stocktwits->getProfile($stocktwits->userid));

    		// You can also get another users profile by entering their ID or name, dont forget to turn into an array with decode
  			$stocktwitsprofile = json_decode($stocktwits->getProfile(170), true);
  			$howardsprofile = json_decode($stocktwits->getProfile('howardlindzon'), true);
        //getting profiles dosent even require authing!

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

  			$body = 'I am testing the stocktwits API! Hi @jayzalowitz @howardlindzon & @stocktwits';
  			$sentiment ="bullish"; // Acceptable values: bullish, bearish, neutral. Defaults to neutral. (Optional)
  			$in_reply_to_message_id ="17938320";
  			$chart = "http://i.imgur.com/vMlZa.gif"; // urls work best here
  			$message = json_decode($stocktwits->message($body,$sentiment,$in_reply_to_message_id,$chart), true);
