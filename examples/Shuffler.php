<?php

use alairock\OAuthPanda\Spotify;

class Shuffler {

  /** @var $spotifyService Spotify */
  private $spotifyService;

  private $baseUrl = 'http://192.168.99.100:1233';

  private $oauth;

  private $token;
  private $tokenType;
  private $expires;
  private $refreshToken;

  public function __construct($serviceCredentials) {
    $this->oauth = new Spotify($serviceCredentials['spotify']['key'], $serviceCredentials['spotify']['secret'], $this->baseUrl);
  }

  public function loginAndGetTracks() {
    if (array_key_exists('access_token', $_COOKIE)) {
        $this->token = $_COOKIE['access_token'];
        $this->tokenType = $_COOKIE['token_type'];
        $this->expires = $_COOKIE['expires_in'];
        $this->refreshToken = $_COOKIE['refresh_token'];
        return true;
    }
    if (!empty($_GET['code'])) {
        if ($this->oauth->hasToken()) {
          header('Location: ' . $this->baseUrl);
        }
        $response = $this->oauth->requestAccessToken($_GET['code']);
        header('Location: ' . $this->baseUrl);
    } elseif (!empty($_GET['go']) && $_GET['go'] === 'go') {
        $redirectTo = $this->oauth->buildAuthorizationUrl();
        header('Location: ' . $redirectTo);
    } elseif(!empty($_GET['logout']) && $_GET['logout'] === 'true') {
        $this->oauth->logout('Spotify');
        header('Location: ' . $this->baseUrl);
    } else {
        $url = $this->baseUrl . '?go=go';
        if ($this->oauth->hasToken()) {
            $logoutUrl = $this->baseUrl . '/?logout=true';
            echo "<a href='$logoutUrl'>Logout!</a>";
            $this->getTracks();
        } else {
            echo "<a href='$url'>Login with Spotify!</a>";
        }
    }
  }

  public function getTracks() {
    $results = $this->oauth->get('/v1/me/tracks');
    dd($results);
  }

}
