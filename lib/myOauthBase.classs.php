<?php

class myOauthBase
{
  protected 
    $key,
    $secret,
    $url_request_token,
    $url_access_token,
    $url_authorize;
    
  public function __construct($array_config)
  {
    $this->secret = $array_config['secret'];
    $this->key = $array_config['key'];
    $this->url_request_token = $array_config['url']['request_token'];
    $this->url_access_token = $array_config['url']['access_token'];
    $this->url_authorize = $array_config['url']['authorize'];
        
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info('{myOauthAuthenticater} new: req:'.$this->url_request_token.' access:'.$this->url_access_token);
    }
  }
  
}