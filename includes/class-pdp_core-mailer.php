<?php

class PDP_Core_Mailer {
	private $admin_emails;

	public function __construct(){
		$this->init();
	}

	private function init(){
		$additional_recipients = explode( ',' , get_option( '_email_recipients' ) );

		$this->admin_emails = array_merge( [get_option( 'admin_email' )], $additional_recipients );

		add_filter( 'wp_mail_content_type', function( $content_type ){
			return 'text/html';
		} );
	}

	private function send_to_admins( $subject, $message, $attachments = array(), $recipients = null ){
		return wp_mail( $recipients ? $recipients : $this->admin_emails, $subject, $message, '', $attachments );
	}

	private function get_template_base( $title, $content ){
		ob_start();
		pdp_get_template( 'emails/base.php', ['title' => $title, 'content' => $content] );
		return ob_get_clean();
	}

	private function get_template_booking( $data, $is_simple = false ){
		$salon_name = PDP_Core_Salon::get_by_id( $data['salon'] )->post_title;
		ob_start();
		pdp_get_template( 'emails/booking/body.php', ['data' => $data, 'salon_name' => $salon_name] );

		if( !$is_simple ){
			echo $this->get_template_cart( $data['services'], $data['total'] );
		}
		else{
			echo $this->get_template_simple_cart( $data['service'] );
		}

		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_simple_cart( $service ){
		$service = array_filter( pdp_get_service_categories(), function( $cat ) use ( $service ) {
			return $cat['slug'] === $service;
		} );

		$service = array_shift( $service )['name'][pdp_get_current_language()];

		ob_start();
		pdp_get_template( 'emails/booking/simple-cart.php', ['service' => $service] );
		return ob_get_clean();
	}

	private function get_template_cart( $services, $total ){
		ob_start();
		pdp_get_template( 'emails/booking/cart.php', ['services' => $services, 'total' => $total] );
		return ob_get_clean();
	}

	private function get_template_gift_card( $data ){
		ob_start();
		pdp_get_template( 'emails/gift-card.php', $data );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_gift_box( $data ){
		ob_start();
		pdp_get_template( 'emails/gift-box.php', $data );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_gifts( $data ){
		ob_start();
		pdp_get_template( 'emails/gifts.php', $data );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_salon_booking( $data ){
		$salon_name = PDP_Core_Salon::get_by_id( $data['salon'] )->post_title;
		ob_start();
		pdp_get_template( 'emails/salon-booking.php', ['data' => $data, 'salon_name' => $salon_name] );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_school_application( $data ){
		ob_start();
		pdp_get_template( 'emails/school-application.php', ['data' => $data] );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	private function get_template_vacancy_apply( $data ){
		$data = $data;
		ob_start();
		pdp_get_template( 'emails/vacancy-apply.php', ['data' => $data] );
		$template = ob_get_clean();

		return $this->get_template_base( '', $template );
	}

	public function booking_notification( $data ){
		$recipients = array_merge( $this->admin_emails, pdp_get_salon_recipients( $data['salon'] ) );
		return $this->send_to_admins( __( '?????????? ????????????', 'pdp_core' ) , $this->get_template_booking( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : $recipients );
	}

	public function simple_booking_notification( $data ){
		$recipients = array_merge( $this->admin_emails, pdp_get_salon_recipients( $data['salon'] ) );
		return $this->send_to_admins( __( '????????????', 'pdp_core' ) . " | {$data['page_title']}", $this->get_template_booking( $data, true ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : $recipients );
	}

	public function service_booking_notification( $data ){
		$recipients = array_merge( $this->admin_emails, pdp_get_salon_recipients( $data['salon'] ) );
		return $this->send_to_admins( __( '????????????', 'pdp_core' ) . " | {$data['page_title']}", $this->get_template_booking( $data, true ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : $recipients );
	}

	public function category_booking_notification( $data ){
		return $this->send_to_admins( __( '????????????', 'pdp_core' ) . " | {$data['page_title']}", $this->get_template_booking( $data, true ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}

	public function gift_card_order_notification( $data ){
		return $this->send_to_admins( __( '?????????? ?????????????????????? ??????????????????????', 'pdp_core' ), $this->get_template_gift_card( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}

	public function gift_box_order_notification( $data ){
		return $this->send_to_admins( __( '?????????? ?????????????????????? ??????????', 'pdp_core' ), $this->get_template_gift_box( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}

	public function gifts_order_notification( $data ){
		return $this->send_to_admins( __( '?????????? ?????????????????????? ????????????', 'pdp_core' ), $this->get_template_gifts( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}

	public function salon_booking_notification( $data ){
		$recipients = array_merge( $this->admin_emails, pdp_get_salon_recipients( $data['salon'] ) );
		return $this->send_to_admins( __( '???????????? ?? ??????????', 'pdp_core' ), $this->get_template_salon_booking( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : $recipients );
	}

	public function school_application_notification( $data ){
		return $this->send_to_admins( __( '???????????? ???? ????????????????', 'pdp_core' ), $this->get_template_school_application( $data ), array(), $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}

	public function vacancy_apply_notification( $data, $attachment ){
		return $this->send_to_admins( __( '???????????? ???? ????????????????', 'pdp_core' ), $this->get_template_vacancy_apply( $data ), $attachment, $data['name'] === 'blackhardd' ? get_option( 'admin_email' ) : null );
	}
}