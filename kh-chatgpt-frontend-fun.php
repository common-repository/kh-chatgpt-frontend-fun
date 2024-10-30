<?php
/**
 * ChatGPT Simple Playground
 *
 * @package       KHCHATGPTF
 * @author        Halim
 * @license       gplv2
 * @version       1.1.0
 *
 * @wordpress-plugin
 * Plugin Name:   ChatGPT Simple Playground
 * Plugin URI:    https://knowhalim.com/app/chatgpt-play
 * Description:   This is a simple chatgpt frontend to let user play, you can specify how many times you want to allow user to request per day.
 * Version:       1.1.0
 * Author:        Halim
 * Author URI:    https://knowhalim.com
 * Text Domain:   kh-chatgpt-frontend-fun
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with ChatGPT Simple Playground. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'KHCHATGPTF_NAME',			'KH ChatGPT Frontend Fun' );

// Plugin version
define( 'KHCHATGPTF_VERSION',		'1.0.0' );

// Plugin Root File
define( 'KHCHATGPTF_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'KHCHATGPTF_PLUGIN_BASE',	plugin_basename( KHCHATGPTF_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'KHCHATGPTF_PLUGIN_DIR',	plugin_dir_path( KHCHATGPTF_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'KHCHATGPTF_PLUGIN_URL',	plugin_dir_url( KHCHATGPTF_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once KHCHATGPTF_PLUGIN_DIR . 'core/class-kh-chatgpt-frontend-fun.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Halim
 * @since   1.0.0
 * @return  object|Kh_Chatgpt_Frontend_Fun
 */
function KHCHATGPTF() {
	return Kh_Chatgpt_Frontend_Fun::instance();
}

KHCHATGPTF();
