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
class GalleryController extends BaseController
	{
		public $callbacks;

		public $subpages = array();

		public function register()
		{

			if ( ! $this->activated( 'gallery_manager' ) ) return;

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
					'page_title'  => 'Gallery', 
					'menu_title'  => 'Gallery Manager', 
					'capability'  => 'manage_options', 
					'menu_slug'   => 'matts_gallery', 
					'callback'    =>  array( $this->callbacks, 'adminGallery' )
				)
			);
		}

		public function activate()
		{
			register_post_type( 'matts_galleries', 
				array(
					'labels'        => array(
						'name'          => 'Galleries',
						'singular_name' => 'Gallery'
						),
					'public'        => true,
					'has_archive'   => true,
				)
			);
		}
	}