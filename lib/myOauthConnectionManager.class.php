<?php


include(sfContext::getInstance()->getConfigCache()->checkConfig(sfConfig::get('sf_config_dir').'/oauth.yml'));
    
class myOauthConnectionManager
{
  static public function getConfigFor($service)
  {
    $array = sfConfig::get('oauth_services_'.$service);
    if( ! $array )
    {
      throw new Exception('{myOauthConnectionManager} getConfigFor failed, can not find config for service '.$service);
    }
    
    return $array;
  }
}