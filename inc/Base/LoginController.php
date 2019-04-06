<?php 
/*
	@package MattsPlugin
 */
namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;


/**
 * 
 */
class LoginController extends BaseController
	{
		public $callbacks;

		public $subpages = array();

		public function register()
		{

			if ( ! $this->activated( 'login_manager' ) ) return;

			$this->settings  = new SettingsApi();

			$this->callbacks = new AdminCallbacks();

			$this->setSubPages();

			$this->settings->addSubPages( $this->subpages )->register();

		}

		public function setSubPages()
		{
			$this->subpages = array(
				array(
// menu subpages to be stored within the plugin menu
					'parent_slug' => 'matts_plugin', 
					'page_title'  => 'Login', 
					'menu_title'  => 'Login Manager', 
					'capability'  => 'manage_options', 
					'menu_slug'   => 'matts_login', 
					'callback'    =>  array( $this->callbacks, 'adminLogin' )
				)
			);
		}

		public function activate()
		{
			register_post_type( 'matts_login', 
				array(
					'labels'        => array(
						'name'          => 'Logins',
						'singular_name' => 'Login'
						),
					'public'        => true,
					'has_archive'   => true,
				)
			);
		}
	}