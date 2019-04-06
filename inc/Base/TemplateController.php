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
class TemplateController extends BaseController
	{
		public $callbacks;

		public $subpages = array();

		public function register()
		{

			if ( ! $this->activated( 'templates_manager' ) ) return;

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
					'page_title'  => 'Templates', 
					'menu_title'  => 'Template Manager', 
					'capability'  => 'manage_options', 
					'menu_slug'   => 'matts_template', 
					'callback'    =>  array( $this->callbacks, 'adminTemplate' )
				)
			);
		}

		public function activate()
		{
			register_post_type( 'matts_templates', 
				array(
					'labels'        => array(
						'name'          => 'Templates',
						'singular_name' => 'Template'
						),
					'public'        => true,
					'has_archive'   => true,
				)
			);
		}
	}