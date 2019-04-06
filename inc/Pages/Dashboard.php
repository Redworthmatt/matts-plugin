<?php
/**
* @package Matts Plugin
*/
namespace Inc\Pages;

use Inc\Base\BaseController;
use Inc\Api\SettingsApi; 
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

	/**
	* 
	*/
	class Dashboard extends BaseController
	{
// Declaring a variable
		public $settings;
		
		public $callbacks;

		public $callbacks_mngr;

//Declaring a variable that is an array
		
		public $pages    = array();
		
//		public $subpages = array();
		
		public function register() 
		{
// Storing a new instance inside the variable settings
			$this->settings  = new SettingsApi();

			$this->callbacks = new AdminCallbacks();

			$this->callbacks_mngr = new ManagerCallbacks();

			$this->setPages();

//			$this->setSubPages();

			$this->setSettings();

			$this->setSections();

			$this->setFields();

// Adds a option to my wordpress menu with the title Dashboard
			$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->register();
		}

		public function setPages() 
		{
			$this->pages = array(
// Repeat as many times as needed 
				array(
					'page_title' => 'Matts Plugin', 
					'menu_title' => 'Matts', 
					'capability' => 'manage_options', 
					'menu_slug'  => 'matts_plugin', 
					'callback'   => array( $this->callbacks, 'adminDashboard' ),
					'icon_url'   => 'dashicons-store', 
					'position'   => 10 
				)
			);
		}

		public function setSettings()
		{
			$args = array(
				array(
					'option_group' => 'matts_plugin_settings',
					'option_name'  => 'matts_plugin',
					'callback'     => array( $this->callbacks_mngr, 'checkboxSanitize' )
				)
			);

			$this->settings->setSettings( $args );
		}

		public function setSections()
		{
			$args = array(
				array(
					'id'       => 'matts_admin_index',
					'title'    => 'Settings Manager',
					'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
					'page'     => 'matts_plugin'
				)
			);

			$this->settings->setSections( $args );
		}

		public function setFields()
		{

			$args = array();
// Calls to both parts of the array first id then title
			foreach ( $this->managers as $key => $value ) {
				$args[] = array(
					'id'          => $key,
					'title'       => $value,
					'callback'    => array( $this->callbacks_mngr, 'checkboxField' ),
					'page'        => 'matts_plugin',
					'section'     => 'matts_admin_index',
					'args'        => array(
						'option_name' => 'matts_plugin',
						'label_for'   => $key,
						'class'       => 'ui-toggle'
					)
				);
			}

			$this->settings->setFields( $args );
		}
	}