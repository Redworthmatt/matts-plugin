<?php
/**
* @package Matts Plugin
*/
namespace Inc\Base;

class Activate
{

	public static function activate() {
		flush_rewrite_rules();

		$default = array();


		if ( ! get_option( 'matts_plugin' ) ) {
			update_option( 'matts_plugin', $default );
			return;
		}

		if ( ! get_option( 'matts_plugin_cpt' ) ) {
			update_option( 'matts_plugin_cpt', $default );
			return;
		}

		if ( ! get_option( 'matts_plugin_tax' ) ) {
			update_option( 'matts_plugin_tax', $default );
			return;
		}
	}
}