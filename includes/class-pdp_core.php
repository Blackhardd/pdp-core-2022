<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PDP_Core
 * @subpackage PDP_Core/includes
 * @author     Alexander Piskun <djalexmurcer@gmail.com>
 */
class PDP_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pdp_core_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PDP_CORE_VERSION' ) ) {
			$this->version = PDP_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pdp_core';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
        $this->init_custom_post_types();
        $this->define_ajax_actions();
        $this->define_shortcodes();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pdp_core_Loader. Orchestrates the hooks of the plugin.
	 * - Pdp_core_i18n. Defines internationalization functionality.
	 * - Pdp_core_Admin. Defines all hooks for the admin area.
	 * - Pdp_core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-i18n.php';

        /**
         * The class responsible for customizing menu.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-menu-walker.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-mobile-menu-walker.php';

        /**
         * The classes responsible for template loading.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libs/class-gamajo-template-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-template-loader.php';

        /**
         * The class responsible for Google API.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-google.php';

        /**
         * The class responsible for defining custom post types.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-cpt.php';

        /**
         * The class responsible for salon functionality.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-salon.php';

		/**
		 * The class responsible for mailing functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-mailer.php';

        /**
         * The class responsible for shortcodes.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-shortcodes.php';

        /**
         * The class responsible for REST API.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-rest-controller.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pdp_core-admin.php';

        /**
         * The class responsible for defining AJAX actions.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdp_core-ajax.php';

		$this->loader = new PDP_Core_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pdp_core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new PDP_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new PDP_Core_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

    /**
     * Register all of the CPT and their taxonomies.
     *
     * @since    1.0.0
     * @access   private
     */
	private function init_custom_post_types(){
	    $plugin_cpt = new PDP_Core_CPT();

	    $this->loader->add_action( 'init', $plugin_cpt, 'init_post_types');
	    $this->loader->add_action( 'carbon_fields_register_fields', $plugin_cpt, 'init_post_types_meta');
    }

    /**
     * Register all AJAX actions.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_ajax_actions() {
        new PDP_Core_Ajax();
    }

    /**
     * Register all shortcodes.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_shortcodes() {
        new PDP_Core_Shortcodes();
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pdp_core_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
