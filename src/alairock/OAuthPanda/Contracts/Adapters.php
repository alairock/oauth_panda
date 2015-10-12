<?php namespace alairock\OAuthPanda\contracts;

interface Adapters{

  /**
   * This should return the authorization URL. This might vary depending on
   * API provider.
   **/
  public function buildAuthorizationUrl();

  public function requestAccessToken($code);

}
