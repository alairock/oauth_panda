<?php namespace alairock\moodshuffle\OAuth;

class OAuth {

  protected $clientId;
  protected $redirectUrl;
  protected $sclientSecret;
  protected $state = 'asodifjsoidjf';
  protected $baseUrl = 'https://api.spotify.com';

  protected $token;
  protected $tokenType;
  protected $expires;
  protected $refreshToken;

  public function __construct($clientId, $clientSecret, $redirectUrl, $state = null) {
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->redirectUrl = $redirectUrl;
    if (!is_null($state)) {
      $this->state = $state;
    }
  }

  public function getClientId() {
    return $this->clientId;
  }

  public function verifyState($state) {
    if ($state != $this->state) {
      throw new Exception('State does not match. Terminating');
    }
  }

  public function sendAccessTokenRequest($requestTokenUrl, $params) {
    $this->verifyState($_GET['state']);
    $curl = new \Curl\Curl();
    $curl->verbose();
    $curl->post($requestTokenUrl, $params);
    if ($curl->error) {
      $errorcode = $curl->error_code;
      $curl->close();
      throw new \Exception("Something went wrong, unable to send request for token: " . $errorcode);
    }
    else {
      $token = $this->storeTokenData($curl->response);
      return $token;
    }
  }

  protected function storeTokenData($data) {
    $consumableData = json_decode($data);
    $this->token = $consumableData->access_token;
    setcookie("token_data", $data, time()+$consumableData->expires_in);
    return $this->token;
  }

  public function get($path) {
    $curl = new \Curl\Curl();
    $this->getToken();
    $curl->setHeader('Authorization', 'Bearer ' . $this->token);
    $curl->get($this->baseUrl . $path);
    dd($curl->response);
    if ($curl->error) {
      return $curl->error_code;
    }
    else {
      $response = json_decode($curl->response);
      return $response;
    }
  }

  private function getToken() {
    if (is_null($this->token) && array_key_exists('token_data', $_COOKIE)) {
      $this->token = json_decode($_COOKIE['token_data'])->access_token;
      return $this->token;
    }
  }

  public function hasToken() {
    if (empty($this->getToken())) {
      return false;
    }
    return true;
  }

  public function logout($adapterName) {
    if (isset($_COOKIE['token_data'])) {
        unset($_COOKIE['token_data']);
        setcookie('token_data', '', time() - 3600);
    }
  }

  public function setBaseUrl($baseUrl) {
    $this->baseUrl = $baseUrl;
  }

  public function getBaseUrl() {
    return $this->baseUrl;
  }
}
