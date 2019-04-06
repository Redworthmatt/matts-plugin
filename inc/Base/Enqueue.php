<?php
/**
* @package Matts Plugin
*/
namespace Inc\Base;

use \Inc\Base\BaseController;

/**
 * 
 */
class Enqueue extends BaseController
{
	
	public function register() {
// calls my scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	function enqueue() {
// enqueue all our scripts
		wp_enqueue_script( 'media_upload' );
		wp_enqueue_media();
		wp_enqueue_style( 'mypluginstyle', $this->plugin_url . '/assets/mystyle.css' );
		wp_enqueue_script( 'mypluginscript', $this->plugin_url . '/assets/myscript.js' );
	}

}