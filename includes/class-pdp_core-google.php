<?php

class PDP_Core_Google {
    protected $client_id;
    protected $client_secret;
    protected $auth_code;
    protected $client;
    protected $redirect_url;

    public function __construct(){
        $this->client_id = carbon_get_theme_option( 'google_client_id' );
        $this->client_secret = carbon_get_theme_option( 'google_secret' );

        if( $this->client_id && $this->client_secret ){
	        $this->client = new Google_Client();

	        $this->auth_code = isset( $_GET['code'] ) ? $_GET['code'] : false;
	        $this->redirect_url = get_admin_url( null, 'admin.php?page=google-api-settings' );

	        $this->config_client();
        }
    }

    public function config_client(){
        $this->client->setClientId( $this->client_id );
        $this->client->setClientSecret( $this->client_secret );
        $this->client->addScope( Google_Service_Sheets::SPREADSHEETS_READONLY );
        $this->client->setAccessType( 'offline' );
        $this->client->setPrompt( 'select_account' );

	    $this->client->setRedirectUri( $this->redirect_url );

        if( $this->get_token() ){
            $this->client->setAccessToken( $this->get_token() );
        }

        if( $this->client->isAccessTokenExpired() ){
        	$new_token = $this->client->getRefreshToken();

        	if( $new_token ){
        		$this->client->fetchAccessTokenWithRefreshToken( $new_token );
	        }
        	else{
				$this->display_auth_message();
	        }

	        update_option( 'google_token', $this->client->getAccessToken() );
        }
    }

    public function get_client(){
    	return $this->client;
    }

    private function get_token(){
    	return get_option( 'google_token' );
    }

	public function display_auth_message(){
    	$auth_url = $this->client->createAuthUrl();

    	if( !$this->auth_code ){
    		echo '<div class="pdp-infobox alert"><div class="pdp-infobox__message">' . __( 'Для синхронизации цен нужно авторизоваться в Google', 'pdp_core' ) . '</div><div class="pdp-infobox__action"><a href="' . $auth_url . '" class="pdp-btn">' . __( 'Авторизоваться в Google', 'pdp_core' ) . '</a></div></div>';
    	}
    	else{
    		$this->client->setAccessToken( $this->client->fetchAccessTokenWithAuthCode( $this->auth_code ) );
    	}
	}
}