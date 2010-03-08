<?php

class oauthConnectActions extends sfActions
{
  public function preExecute()
  {
    $this->oauthUser = new myUserOauth($this->getUser());
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->service = $request->getParameter('service');
    $this->config_service = myOauthConnectionManager::getConfigFor( $this->service );
    
    try
    {
      $mc = new myOauthAuthenticater( $this->config_service );
      $mc->init();
      $mc->processRequestToken();
      
      $this->oauthUser->setSecretToken( $mc->getRequestTokenSecret() ); 
      
      $this->url_oauth_access = $mc->getAuthorizeUrl();
      $this->redirect( $this->url_oauth_access );
    }
    catch(Exception $e)
    {
      
      // var_dump($mc->getLastDebugInfo());
      //      die;
      //      if (sfConfig::get('sf_logging_enabled'))
      //      {
      //        sfContext::getInstance()->getLogger()->err('{connectActions} executeIndex: debug info:'.var_export($mc->getLastDebugInfo(), true));
      //      }
      
      throw $e;
    }
  }  
   
  public function executeCallback(sfWebRequest $request)
  {
    $this->service = $request->getParameter('service');
    $this->config_service = myOauthConnectionManager::getConfigFor( $this->service );
    
    $req_token = $request->getParameter('oauth_token', false);
    $user_secret_token = $this->oauthUser->getSecretToken(false);
    
    if( ! $req_token || ! $user_secret_token )
    {
      $this->redirect('@signIn');
    }
    
    $mc = new myOauthAuthenticater( $this->config_service );
    $mc->init();
    $mc->processAccessToken($req_token, $user_secret_token);
    
    $this->oauthUser->setOauthTokens( $mc->getRequestToken(),  $mc->getRequestTokenSecret());
    unset($mc);
    
    call_user_func_array(
        array($this->config_service['connector_callback']['class'], $this->config_service['connector_callback']['method']),
        array(
            $this->service,
            $this->oauthUser,
            $this->config_service
          )
      );
  }
}
