<?php

class myOauthAuthenticater extends myOauthBase
{  
  protected
    $oauth;
  
  public function init()
  {
    $this->oauth = new OAuth($this->key, $this->secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $this->oauth->enableDebug();  // This will generate debug output in your error_log
  }
  
  public function getAuthorizeUrl()
  {
    return $this->url_authorize.'?oauth_token='.$this->getRequestToken();
  }
  public function getRequestToken()
  {
    return $this->token_request;
  }
  public function getRequestTokenSecret()
  {
    return $this->token_request_secret;
  }
  
  public function getLastDebugInfo()
  {
    return $this->oauth->debugInfo;
  }
  
  public function processRequestToken()
  {
    $request_token_info = $this->oauth->getRequestToken($this->url_request_token);
    $this->token_request = $request_token_info['oauth_token'];
    $this->token_request_secret = $request_token_info['oauth_token_secret'];
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info('{myOauthAuthenticater} processRequestToken: req:'.$this->token_request.' secret:'.$this->token_request_secret);
    }
  }
  
  public function processAccessToken($req_token, $user_token_secret)
  {
    $this->oauth->setToken($req_token, $user_token_secret);
    $access_token_info = $this->oauth->getAccessToken($this->url_access_token);
    $this->token_request = $access_token_info['oauth_token'];
    $this->token_request_secret = $access_token_info['oauth_token_secret'];
    
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info('{myOauthAuthenticater} processAccessToken: req:'.$this->token_request.' secret:'.$this->token_request_secret);
    }
  }
}

