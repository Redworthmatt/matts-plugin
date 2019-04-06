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
class ChatController extends BaseController
	{
		public $callbacks;

		public $subpages = array();

		public function register()
		{

			if ( ! $this->activated( 'chat_manager' ) ) return;

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
					'page_title'  => 'Chat', 
					'menu_title'  => 'Chat Manager', 
					'capability'  => 'manage_options', 
					'menu_slug'   => 'matts_chat', 
					'callback'    =>  array( $this->callbacks, 'adminChat' )
				)
			);
		}

		public function activate()
		{
			register_post_type( 'matts_chats', 
				array(
					'labels'        => array(
						'name'          => 'Chats',
						'singular_name' => 'Chat'
						),
					'public'        => true,
					'has_archive'   => true,
				)
			);
		}
	}