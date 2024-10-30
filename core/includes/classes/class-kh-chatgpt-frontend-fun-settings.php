<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Kh_Chatgpt_Frontend_Fun_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		KHCHATGPTF
 * @subpackage	Classes/Kh_Chatgpt_Frontend_Fun_Settings
 * @author		Halim
 * @since		1.0.0
 */
class Kh_Chatgpt_Frontend_Fun_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Kh_Chatgpt_Frontend_Fun_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = KHCHATGPTF_NAME;
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'KHCHATGPTF/settings/get_plugin_name', $this->plugin_name );
	}
}
