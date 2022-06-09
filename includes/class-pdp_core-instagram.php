<?php

class PDP_Core_Instagram {
	private $app_id;
	private $app_secret;

	private $access_token;
	private $token_expires_in;

	private $redirect_url;
	private $api_url;
	private $graph_url;

	public function __construct(){
		$this->app_id = get_option( '_instagram_app_id' );
		$this->app_secret = get_option( '_instagram_app_secret' );

		$this->access_token = get_option( 'instagram_token' );
		$this->token_expires_in = get_option( 'instagram_token_expires_in' );

		$this->redirect_url = admin_url( '', 'https' );
		$this->api_url = 'https://api.instagram.com/';
		$this->graph_url = 'https://graph.instagram.com/';
	}

	private function api_call( $params ){
		$ch = curl_init();
		$endpoint = $params['endpoint_url'];

		if( 'POST' === $params['type'] ){
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params['url_params'] ) );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		}
		else if( 'GET' === $params['type'] ){
			$endpoint .= '?' . http_build_query( $params['url_params'] );
		}

		curl_setopt( $ch, CURLOPT_URL, $endpoint );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$response = curl_exec( $ch );
		curl_close( $ch );

		$response_array = json_decode( $response, true );

		if( isset( $response_array['error_type'] ) ){
			var_dump( $response_array );
			die();
		}
		else{
			return $response_array;
		}
	}

	private function graph_call( $type = '', $fields = array( 'username', 'media_url', 'media_type', 'permalink' ) ){
		if( !$this->access_token ) {
			return null;
		}

		$fields = implode( ',', $fields );

		$instagram_connection = curl_init();

		if( $type === 'media' ){
			$type = '/media';
		}

		curl_setopt( $instagram_connection, CURLOPT_URL, "{$this->graph_url}me{$type}?fields={$fields}&access_token={$this->access_token}" );
		curl_setopt( $instagram_connection, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $instagram_connection, CURLOPT_TIMEOUT, 9 );
		curl_setopt( $instagram_connection, CURLOPT_CONNECTTIMEOUT, 9 );

		$response = json_decode( curl_exec( $instagram_connection ), true );

		curl_close( $instagram_connection );

		return $response;
	}

	public function get_login_url(){
		if( $this->app_id ){
			return $this->api_url . 'oauth/authorize?' . http_build_query( array(
				'app_id'        => $this->app_id,
				'redirect_uri'  => $this->redirect_url,
				'scope'         => 'user_profile,user_media',
				'response_type' => 'code',
				'state'         => 'pdp_instagram'
			) );
		}

		return null;
	}

	public function fetch_long_access_token(){
		if( !defined( 'DOING_AJAX' ) || !DOING_AJAX ){
			if( isset( $_GET['code'] ) && isset( $_GET['state'] ) && $_GET['state'] === 'pdp_instagram' ){
				if( $this->app_id && $this->app_secret ){
					$short_access_token = array(
						'endpoint_url'  => $this->api_url . 'oauth/access_token',
						'type'          => 'POST',
						'url_params'    => array(
							'app_id'        => $this->app_id,
							'app_secret'    => $this->app_secret,
							'grant_type'    => 'authorization_code',
							'redirect_uri'  => $this->redirect_url,
							'code'          => $_GET['code']
						)
					);

					$long_access_token = array(
						'endpoint_url'  => $this->graph_url . 'access_token',
						'type'          => 'GET',
						'url_params'    => array(
							'client_secret' => $this->app_secret,
							'grant_type'    => 'ig_exchange_token'
						)
					);

					$short_access_token_response = $this->api_call( $short_access_token );
					$short_token = $short_access_token_response['access_token'];
					$long_access_token['url_params']['access_token'] = $short_token;
					$long_access_token_response = $this->api_call( $long_access_token );

					if( isset( $long_access_token_response['access_token'] ) ){
						delete_option( 'instagram_feed_uploads' );
						update_option( 'instagram_token', $long_access_token_response['access_token'] );
						update_option( 'instagram_token_expires_in', time() + $long_access_token_response['expires_in'] );

						wp_redirect( admin_url( 'admin.php?page=pdp-instagram-sync', 'https' ) );
						exit();
					}
				}
			}
		}
	}

	public function fetch_user_media(){
		$media = $this->get_user_media();

		if( $media && count( $media['data'] ) > 0 ){
			if( !function_exists( 'media_sideload_image' ) ){
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$media_items = array_reverse( array_slice( $media['data'], 0, 9 ) );
			$uploaded = get_option( 'instagram_feed_uploads', array() );

			foreach( $media_items as $key => $item ){
				if( $key < 9 && !array_key_exists( $item['id'], $uploaded ) ){
					$url = '';

					switch( $item['media_type'] ){
						case "VIDEO":
							$url = $item['thumbnail_url'];
							break;
						default:
							$url = $item['media_url'];
					}

					$id = media_sideload_image( $url, 0, null, 'id' );
					update_post_meta( $id, 'instagram_id', $item['id'] );

					$uploaded = array( $item['id'] => array( 'attachment_id' => $id, 'url' => $item['permalink'], 'type' => $item['media_type'] ) ) + $uploaded;
				}
			}

			update_option( 'instagram_feed_uploads', $uploaded );
		}
	}

	public function is_token_expired(){
		return $this->token_expires_in ? $this->token_expires_in < time() : true;
	}

	public function get_user_profile(){
		return $this->graph_call( '', array( 'username' ) );
	}

	public function get_user_media(){
		return $this->graph_call( 'media', array( 'id', 'username', 'media_url', 'media_type', 'thumbnail_url', 'permalink' ) );
	}
}