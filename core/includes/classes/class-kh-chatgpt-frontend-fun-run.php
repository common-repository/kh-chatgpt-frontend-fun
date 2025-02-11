<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Kh_Chatgpt_Frontend_Fun_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		KHCHATGPTF
 * @subpackage	Classes/Kh_Chatgpt_Frontend_Fun_Run
 * @author		Halim
 * @since		1.0.0
 */
class Kh_Chatgpt_Frontend_Fun_Run{

	/**
	 * Our Kh_Chatgpt_Frontend_Fun_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . KHCHATGPTF_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	1.0.0
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*/
	public function add_plugin_action_link( $links ) {

		$links['our_shop'] = sprintf( '<a href="%s" title="Custom Link" style="font-weight:700;">%s</a>', 'https://knowhalim.com', __( 'Contact Halim', 'kh-chatgpt-frontend-fun' ) );

		return $links;
	}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_style( 'khchatgptf-backend-styles', KHCHATGPTF_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), KHCHATGPTF_VERSION, 'all' );
		wp_enqueue_script( 'khchatgptf-backend-scripts', KHCHATGPTF_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), KHCHATGPTF_VERSION, false );
		wp_localize_script( 'khchatgptf-backend-scripts', 'khchatgptf', array(
			'plugin_name'   	=> __( KHCHATGPTF_NAME, 'kh-chatgpt-frontend-fun' ),
		));
	}

}
