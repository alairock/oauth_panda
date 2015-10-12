<?php namespace alairock\moodshuffle;

use alairock\moodshuffle\OAuth\OAuth;

class Spotify extends Oauth implements \alairock\moodshuffle\contracts\Adapters
{

  public function __construct($clientId, $clientSecret, $redirectUrl, $state = null) {
    parent::__construct($clientId, $clientSecret, $redirectUrl, $state);
  }

  public function buildAuthorizationUrl() {
      $spotifyAuthUrl = 'https://accounts.spotify.com/authorize/';
      $parameters = [
        'client_id' => $this->clientId,
        'response_type' => 'code',
        'redirect_uri' => $this->redirectUrl,
        'state' => $this->state,
        'scope' => 'playlist-read-private playlist-read-collaborative streaming user-library-read',
        'show_dialog' => false,
      ];
      $parameters = http_build_query($parameters);
      return $spotifyAuthUrl .'?'. $parameters;
  }

  public function requestAccessToken($code) {
    $spotifyTokenUrl = 'https://accounts.spotify.com/api/token';
    $parameters = [
      'grant_type' => 'authorization_code',
      'code' => $code,
      'redirect_uri' => $this->redirectUrl,
      'client_id' => $this->clientId,
      'client_secret' => $this->clientSecret
    ];

    return $this->sendAccessTokenRequest($spotifyTokenUrl, $parameters);
  }
}
