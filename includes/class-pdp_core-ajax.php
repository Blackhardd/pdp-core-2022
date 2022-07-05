<?php

class PDP_Core_Ajax {
    private $template_loader;
    private $mailer;

    function __construct(){
        $this->template_loader = new PDP_Core_Template_Loader();
        $this->mailer = new PDP_Core_Mailer();

        $this->register_public_actions();
        $this->register_admin_actions();
    }

    private function response( $status, $message = '', $redirect = '', $action = '' ){
        wp_die( json_encode( array(
        	'status'	=> $status,
	        'message'	=> $message,
			'redirect'  => $redirect,
	        'action'    => $action
        ) ) );
    }

    private function check_nonce( $action_name = false ){
		if( $action_name ){
			if( empty( $_POST ) || !wp_verify_nonce( $_POST['pdp_nonce'], 'pdp_' . $action_name . '_nonce' ) ){
				return false;
			}

			return true;
		}

		return;
    }

    private function register_public_actions(){
        $actions = array(
	        'booking',
            'simple_booking',
            'category_booking',
			'service_booking',
	        'gift_card_order',
            'gift_box_order',
            'gifts_order',
            'salon_booking',
            'school_application',
            'vacancy_apply'
        );

        foreach( $actions as $action ){
            add_action( 'wp_ajax_' . $action, array( $this, $action ) );
            add_action( 'wp_ajax_nopriv_' . $action, array( $this, $action ) );
        }
    }

    private function register_admin_actions(){
    	$actions = array(
    		'sync_pricelist',
    		'sync_pricelists',
		    'instagram_feed_sync'
	    );

    	foreach( $actions as $action ){
		    add_action( 'wp_ajax_' . $action, array( $this, $action ) );
	    }
    }

	public function booking(){
    	$cart = json_decode( wp_unslash( $_POST['cart'] ), true );
		$data = array(
			'name'              => $_POST['name'],
			'phone'             => $_POST['phone'],
			'salon'             => $cart['salon'],
			'services'          => $cart['services'],
			'total'             => $cart['total']
		);

		$this->response(
			$this->mailer->booking_notification( $data ),
			sprintf( '%s<br>%s', __( 'Спасибо за запись!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ),
			pdp_get_thank_you_page_link(),
			'booking'
		);
	}

    public function simple_booking(){
    	if( $this->check_nonce( 'simple_booking' ) ){
		    $data = pdp_get_post_data();

		    $this->response(
		    	$this->mailer->simple_booking_notification( $data ),
			    sprintf( '%s<br>%s', __( 'Спасибо за запись!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ),
			    pdp_get_thank_you_page_link()
		    );
	    }
    }

	public function category_booking(){
		if( $this->check_nonce( 'category_booking' ) ) {
			$data = pdp_get_post_data();

			$this->response(
				$this->mailer->category_booking_notification( $data ),
				sprintf( '%s<br>%s', __( 'Спасибо за запись!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ),
				pdp_get_thank_you_page_link()
			);
		}
	}

	public function service_booking(){
		if( $this->check_nonce( 'service_booking' ) ) {
			$data = pdp_get_post_data();

			$this->response(
				$this->mailer->service_booking_notification( $data ),
				sprintf( '%s<br>%s', __( 'Спасибо за запись!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ),
				pdp_get_thank_you_page_link()
			);
		}
	}

	public function gift_card_order(){
		if( $this->check_nonce( 'gift_card_order' ) ){
			$data = pdp_get_post_data();

			$this->response(
				$this->mailer->gift_card_order_notification( $data ),
				sprintf( '%s<br>%s', __( 'Спасибо за заказ!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) )
			);
		}
	}

	public function gift_box_order(){
		if( $this->check_nonce( 'gift_box_order' ) ){
			$data = pdp_get_post_data();

			$this->response(
				$this->mailer->gift_box_order_notification( $data ),
				sprintf( '%s<br>%s', __( 'Спасибо за заказ!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) )
			);
		}
	}

	public function gifts_order(){
		if( $this->check_nonce( 'gifts_order' ) ){
			$data = pdp_get_post_data();

			$this->response(
				$this->mailer->gifts_order_notification( $data ),
				sprintf( '%s<br>%s', __( 'Спасибо за заказ!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) )
			);
		}
	}

	public function salon_booking(){
    	if( $this->check_nonce( 'salon_booking' ) ){
    		$data = pdp_get_post_data();
    		$this->response(
    			$this->mailer->salon_booking_notification( $data ),
			    sprintf( '%s<br>%s', __( 'Спасибо за заявку!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ),
			    pdp_get_thank_you_page_link()
		    );
	    }
	}

	public function school_application(){
		if( $this->check_nonce( 'school_application' ) ){
			$data = pdp_get_post_data();

			$this->response( $this->mailer->school_application_notification( $data ), sprintf( '%s<br>%s', __( 'Спасибо за заявку!', 'pdp_core' ), __( 'В ближайшее время с вами свяжется наш менеджер.', 'pdp_core' ) ) );
		}
	}

	public function vacancy_apply(){
		if( $this->check_nonce( 'vacancy_apply' ) ){
			$data = pdp_get_post_data();

			if( $_FILES['attachment']['size'] != 0 ){
				if( !function_exists( 'wp_handle_upload' ) ){
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}

				$attachment = wp_handle_upload( $_FILES['attachment'], array( 'test_form' => false ) );

				if( $attachment && empty( $attachment['error'] ) ){
					$this->response( $this->mailer->vacancy_apply_notification( $data, $attachment['file'] ), __( 'Ваша заявка была отправлена', 'pdp_core' ) );
				}

				$this->response( false, __( 'Файл не был загружен.', 'pdp_core' ) );
			}

			$this->response( $this->mailer->vacancy_apply_notification( $data, array() ), __( 'Ваша заявка была отправлена', 'pdp_core' ) );
		}
	}
	

	/**
	 *  ADMIN
	 */

	public function sync_pricelist(){
		pdp_fetch_pricelists( $_POST['id'] );

		$this->response( true, 'Sync single.' );
	}

	public function sync_pricelists(){
		pdp_fetch_pricelists();

		$this->response( true, 'Sync all.' );
	}

	public function instagram_feed_sync(){
		$instagram = new PDP_Core_Instagram();
		$instagram->fetch_user_media();
		$this->response( true, 'Лента Instagram подгружена.' );
	}
}