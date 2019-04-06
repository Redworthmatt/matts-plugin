<?php
/**
* @package Matts Plugin
*/
namespace Inc;

	// Public
		// can be accessed everywhere

	// Protected
		// can be accessed only within the class itself or extensions of that class

	// Private
		// can be accessed only within the class itself

	// Static
		// can be accessed without initailizing the class

	// Final
		// Can not extend the class

final class Init
{
	/*
	Store all the clases inside an array
	*/
	public static function get_services() {
// return an array full list of classes
		return [
			Pages\Dashboard::class,
			Base\Enqueue::class,
			Base\SettingsLinks::class,
			Base\CustomPostTypeController::class,
			Base\TaxonomyController::class,
			Base\MediaWidgetController::class,
			Base\GalleryController::class,
			Base\TestimonialController::class,
			Base\TemplateController::class,
			Base\LoginController::class,
			Base\MembershipController::class,
			Base\ChatController::class
		];
	}

/*
Loop through the classes, initialize them,
and call the register() method if it exists
*/
public static function register_services() {
// For each class in the get_services of this file loop
	foreach ( self::get_services() as $class ) {
// Put each instant of a class in this file sotre as variable services
		$service = self::instantiate( $class );
// If the register method is within the service variable 
		if ( method_exists( $service, 'register') ) {
// Call the register method
			$service->register();
		}
	}
}
/*
	initialize the class
	@param class $class  class from the services array
	@return class instance  new instance of the class
*/
// Pass the Class variable through this method
	private static function instantiate( $class )
	{
// Store serivce variable as a new class
		$service = new $class();
// Then return the service
		return $service;
	}
}





/*
// Calls the folder than the file
use Inc\Activate;
use Inc\Deactivate;
use Inc\Admin\AdminPages;

if ( !class_exists( 'MattsPlugin' ) ) {

	class MattsPlugin 
	{

	// Public
		// can be accessed everywhere

	// Protected
		// can be accessed only within the class itself or extensions of that class

	// Private
		// can be accessed only within the class itself

	// Static
		// can be accessed without initailizing the class

	// Final
		// Can not extend the class

		public $plugin;

		function __construct() {
			// pulls in the name of the file/plugin
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			// calls my scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
			// adds a option to my wordpress menu
			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
			// adds a link within my wordpress menu option
			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		public function settings_link( $links ) {
			// add custom settings link
			$settings_link = '<a href="admin.php?page=matts_plugin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			// option for wordpress menu
			add_menu_page( 'Matts Plugin', 'Matts', 'manage_options', 'matts_plugin', array( $this, 'admin_index' ), 'dashicons-store', 110 );	
		}

		public function admin_index() {
			// Template
			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
		}

		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}

		function custom_post_type() {
			register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
		}

		function enqueue() {
		// enqueue all our scripts
			wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__ ) );
			wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__ ) );
		}

		function activate() {
			// calls the activate method from the activation file for the plugin
			//require_once plugin_dir_path( __FILE__ ) . 'inc/matts-plugin-activate.php';
			Activate::activate();
		}
	}

	$mattsPlugin = new MattsPlugin();	
	$mattsPlugin->register();

// activation
	register_activation_hook( __FILE__, array( $mattsPlugin, 'activate' ) );

// deactivation
	//require_once plugin_dir_path( __FILE__ ) . 'inc/matts-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'Deactivate', 'deactivate' ) );
}
*/