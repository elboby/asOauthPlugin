<?php
// once authenticated


class TwitterDataAccess extends myOauthBase
{
  protected
    $oauth;
    
  public function init($user_token_array, $oauth_encryption=OAUTH_SIG_METHOD_HMACSHA1, $oauth_type=OAUTH_AUTH_TYPE_URI)
  {
    list($user_token, $user_token_secret) = $user_token_array;
    
    $this->oauth = new OAuth($this->key, $this->secret, $oauth_encryption, $oauth_type);
    $this->oauth->enableDebug();  // This will generate debug output in your error_log
    $this->oauth->setToken($user_token, $user_token_secret);
        
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info('{TwitterDataAccess} init: req:'.$user_token.' secret:'.$user_token_secret);
    }
  }
  
  public function getUserInfo()
  {
    $this->oauth->fetch('http://twitter.com/account/verify_credentials.json'); 
    $json = json_decode($this->oauth->getLastResponse());

    return $json;
    // $array = (array)$json;
    //    $array['status'] = (array)$array['status'];
    //    return $array;
    // $debug = $this->oauth->getLastResponseInfo();
    //     print_r($debug);
  }
}