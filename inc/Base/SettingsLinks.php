<?php
/**
* @package Matts Plugin
*/
namespace Inc\Base;

use \Inc\Base\BaseController;

class SettingsLinks extends BaseController
{

	public function register() {
		// adds a link within my wordpress menu option
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ) {
		// add custom settings link
		$settings_link = '<a href="admin.php?page=matts_plugin">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}