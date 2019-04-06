<?php
/**
* @package Matts Plugin
*/
namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

/**
 * 
 */
class ManagerCallbacks extends BaseController
{
	public function checkboxSanitize( $input )
	{
		$output = array();
// Loop through managers in basecontroller calling ID with a title
		foreach ( $this->managers as $key => $value ) {
// check if the input has inside itself a key equal to the manager callback ID
			$output[$key] = isset( $input[$key] ) ? true : false ;
		}
// return full array with what checked and whats not
		return $output;		
	}

	public function adminSectionManager()
	{
// Echo a intro line for the Plugin settings
		echo 'Manage the Sections and Features of this Plugin by activating the checkboxes from the following list.';
	}

	public function checkboxField( $args )
	{

		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$checkbox = get_option( $option_name );

// if checkbox name is set then if checkbox name is true return true otherwise false 
		$checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

		echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';

	}
}