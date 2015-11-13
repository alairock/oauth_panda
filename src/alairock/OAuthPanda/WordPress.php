<?php namespace alairock\OAuthPanda;
use alairock\OAuthPanda\OAuth\OAuth;
class WordPress extends Oauth implements contracts\Adapters
{
	public $baseUrl;

	public function __construct($clientId, $clientSecret, $redirectUrl, $state = null) {
		parent::__construct($clientId, $clientSecret, $redirectUrl, $state);
	}
	public function setBaseUrl($baseUrl) {
		$this->baseUrl = $baseUrl;
	}
	public function buildAuthorizationUrl() {
		$wpAuthUrl = $this->baseUrl . '/oauth/authorize/';
		$parameters = [
			'client_id' => $this->clientId,
			'response_type' => 'code',
			'redirect_uri' => $this->redirectUrl,
			'state' => $this->state,
			'scope' => 'offline_access',
			'show_dialog' => false,
		];
		$parameters = http_build_query($parameters);
		return $wpAuthUrl .'?'. $parameters;
	}
	public function requestAccessToken($code) {
		$wpTokenUrl = $this->baseUrl . '/oauth/token';
		$parameters = [
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $this->redirectUrl,
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret
		];
		return $this->sendAccessTokenRequest($wpTokenUrl, $parameters);
	}
}
