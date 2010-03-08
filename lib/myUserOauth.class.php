<?php

//composite of sf_user to handle session storage

class myUserOauth
{
  const NS_OAUTH   = 'oauth';
  protected $user;
  
  public function __construct(sfUser $user)
  {
    $this->user = $user;
  }
  
  public function getUser()
  {
    return $this->user;
  }
  
  public function setOauthTokens($request_token, $request_token_secret)
  {
    $this->user->setAttribute('token', $request_token, self::NS_OAUTH);
    $this->user->setAttribute('token_secret', $request_token_secret, self::NS_OAUTH);
  }
  public function getOauthTokens()
  {
    return array(
              $this->user->getAttribute('token', null, self::NS_OAUTH),
              $this->user->getAttribute('token_secret', null, self::NS_OAUTH)
            );
  }
  
  public function setSecretToken($secret_token)
  {
    $this->user->setAttribute('token_secret', $secret_token, self::NS_OAUTH);
  }
  public function getSecretToken($default=null)
  {
    return $this->user->getAttribute('token_secret', $default, self::NS_OAUTH);
  }
}