<?php
//Delete below?
//require_once("OAuth.php");

class Stocktwits {
  public $base_url = "https://api.stocktwits.com/api/2/";
  public $secure_base_url = "https://api.stocktwits.com/api/2/";
  public $request_token;
  public $access_token_path;
  public $request_token_path;
  public $authorize_path;
  public $debug = false;
  public $clientId;
  public $clientSecret;
  public $redirectUri;
  public $scope = "read,watch_lists,publish_messages,publish_watch_lists,direct_messages,follow_users,follow_stocks"; // api permissions
  public $useragent = "Jay Zalowitz's Stocktwits API PHP Class";
  public $access_token;
  public $username;
  public $userid;

  function __construct($clientId, $clientSecret, $redirectUri = NULL) {
    
    if ($redirectUri) {
      $this->redirectUri = $redirectUri;
    }

    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->request_token_path = $this->secure_base_url . "oauth/token";  
    $this->access_token_path = $this->secure_base_url . "oauth/authorize";
    $this->authorize_path = $this->secure_base_url . "oauth/signin";
    
  }

  // Generates authorize url, where you direct client to sign in
  function generateAuthorizeUrl() {
    return $this->authorize_path . "?client_id=" . $this->clientId . "&response_type=code&redirect_uri=".$this->redirectUri."&scope=".$this->scope; 
  }

  function getAccessToken($code) {
    $postdata = array(
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $this->redirectUri
        );
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $this->request_token_path,
    CURLOPT_USERAGENT => $this->useragent,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $postdata
    ));
  $response = curl_exec($curl);
curl_close($curl);
  return $response;
    }
  
  function getProfile($id) {
    $profileApiLocation = $this->secure_base_url .'streams/user/'.$id.'.json';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $profileApiLocation,
    CURLOPT_USERAGENT => $this->useragent
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }
 
  function getStream($id = "home") {
    // Gets resource streams/home.json by default
    $profileApiLocation = $this->secure_base_url .'streams/'.$id.'.json?access_token='.$this->access_token;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $profileApiLocation,
    CURLOPT_USERAGENT => $this->useragent
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }
  
  function searchSymbol($query) {
    // Gets resource streams/home.json by default
    $profileApiLocation = $this->secure_base_url .'search/symbols.json?access_token='.$this->access_token.'&q='.$query;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $profileApiLocation,
    CURLOPT_USERAGENT => $this->useragent
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }

  function searchUser($query) {
    // Gets resource streams/home.json by default
    $profileApiLocation = $this->secure_base_url .'search/users.json?access_token='.$this->access_token.'&q='.$query;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $profileApiLocation,
    CURLOPT_USERAGENT => $this->useragent
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }

  function searchGeneral($query) {
    // Gets resource streams/home.json by default
    $profileApiLocation = $this->secure_base_url .'search.json?access_token='.$this->access_token.'&q='.$query;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $profileApiLocation,
    CURLOPT_USERAGENT => $this->useragent
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }


  function message($body, $sentiment = null, $in_reply_to_message_id = null, $chart = null) {
    $postdata = array(
        'body' => $body);
    

    if (!is_null($in_reply_to_message_id)){
        $postdata['in_reply_to_message_id'] = $in_reply_to_message_id;
    }

    if (!is_null($chart)){
        $postdata['chart'] = $chart;
    }

    if (!is_null($sentiment)){
        $postdata['sentiment'] = $sentiment;
    }

    $messageurl = 'https://api.stocktwits.com/api/2/messages/create.json?access_token='.$this->access_token;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $messageurl,
    CURLOPT_USERAGENT => $this->useragent,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $postdata
    ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
  }


}
