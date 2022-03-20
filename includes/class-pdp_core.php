<?php

class PDP_Core {
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if( defined( 'PDP_CORE_VERSION' ) ){
			$this->version = PDP_CORE_VERSION;
		}
		else{
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'pdp_core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
        $this->init_custom_post_types();
        $this->init_instagram();
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-i18n.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-menu-walker.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-mobile-menu-walker.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libs/class-gamajo-template-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-template-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-google.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-instagram.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-cpt.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-salon.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-mailer.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-shortcodes.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-rest-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pdp_core-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-ajax.php';

		$this->loader = new PDP_Core_Loader();

		new PDP_Core_Ajax();
		new PDP_Core_Shortcodes();
	}

	private function set_locale() {
		$plugin_i18n = new PDP_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$plugin_admin = new PDP_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	private function init_custom_post_types(){
	    $plugin_cpt = new PDP_Core_CPT();

	    $this->loader->add_action( 'init', $plugin_cpt, 'init_post_types');
	    $this->loader->add_action( 'carbon_fields_register_fields', $plugin_cpt, 'init_post_types_meta');
    }

    private function init_instagram(){
		$instagram = new PDP_Core_Instagram();

		$this->loader->add_action( 'admin_head', $instagram, 'fetch_long_access_token' );
    }

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}
}
