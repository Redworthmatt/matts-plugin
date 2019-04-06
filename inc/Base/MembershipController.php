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
class MembershipController extends BaseController
	{
		public $callbacks;

		public $subpages = array();

		public function register()
		{

			if ( ! $this->activated( 'membership_manager' ) ) return;

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
					'page_title'  => 'Membership', 
					'menu_title'  => 'Membership Manager', 
					'capability'  => 'manage_options', 
					'menu_slug'   => 'matts_membership', 
					'callback'    =>  array( $this->callbacks, 'adminMembership' )
				)
			);
		}

		public function activate()
		{
			register_post_type( 'matts_memberships', 
				array(
					'labels'        => array(
						'name'          => 'Memberships',
						'singular_name' => 'Membership'
						),
					'public'        => true,
					'has_archive'   => true,
				)
			);
		}
	}