<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Kh_Chatgpt_Frontend_Fun' ) ) :

	/**
	 * Main Kh_Chatgpt_Frontend_Fun Class.
	 *
	 * @package		KHCHATGPTF
	 * @subpackage	Classes/Kh_Chatgpt_Frontend_Fun
	 * @since		1.0.0
	 * @author		Halim
	 */
	final class Kh_Chatgpt_Frontend_Fun {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Kh_Chatgpt_Frontend_Fun
		 */
		private static $instance;

		/**
		 * KHCHATGPTF helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Kh_Chatgpt_Frontend_Fun_Helpers
		 */
		public $helpers;

		/**
		 * KHCHATGPTF settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Kh_Chatgpt_Frontend_Fun_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'kh-chatgpt-frontend-fun' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'kh-chatgpt-frontend-fun' ), '1.0.0' );
		}

		/**
		 * Main Kh_Chatgpt_Frontend_Fun Instance.
		 *
		 * Insures that only one instance of Kh_Chatgpt_Frontend_Fun exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Kh_Chatgpt_Frontend_Fun	The one true Kh_Chatgpt_Frontend_Fun
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Kh_Chatgpt_Frontend_Fun ) ) {
				self::$instance					= new Kh_Chatgpt_Frontend_Fun;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Kh_Chatgpt_Frontend_Fun_Helpers();
				self::$instance->settings		= new Kh_Chatgpt_Frontend_Fun_Settings();

				//Fire the plugin logic
				new Kh_Chatgpt_Frontend_Fun_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'KHCHATGPTF/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once KHCHATGPTF_PLUGIN_DIR . 'core/includes/classes/class-kh-chatgpt-frontend-fun-helpers.php';
			require_once KHCHATGPTF_PLUGIN_DIR . 'core/includes/classes/class-kh-chatgpt-frontend-fun-settings.php';

			require_once KHCHATGPTF_PLUGIN_DIR . 'core/includes/classes/class-kh-chatgpt-frontend-fun-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'kh-chatgpt-frontend-fun', FALSE, dirname( plugin_basename( KHCHATGPTF_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.


add_action('wp_ajax_nopriv_kh_ask_chat', 'khplugin_do_chat_run');
add_action('wp_ajax_kh_ask_chat', 'khplugin_do_chat_run');

add_shortcode('khplugin_fun_chatgpt','khplugin_generate_chatform_sample');
function khplugin_generate_chatform_sample(){
	$fun_chatgpt_frontend_options = get_option( 'fun_chatgpt_frontend_option_name' ); // Array of All Options
	$show_created_by_knowhalim = $fun_chatgpt_frontend_options['show_created_by_knowhalim_2'];
	
	
    $credit='';
    if ($show_created_by_knowhalim=="yes"){
        $credit='<center><small><a href="https://knowhalim.com" target="_blank">Created by Knowhalim</a></small></center>';
    }
	add_action('wp_footer','khplugin_ajax_chat_start');
	$form ='<div class="sample_chatgpt">'.khplugin_get_today_limit().'<p>To start, enter your question in the field below and click on \'Ask ChatGPT\' button.</p><textarea id="ask" ></textarea><br><div class="chatbtn">Ask ChatGPT</div></div>
	<div class="preloader_kh">'.khplugin_preloader().'</div>
	<div class="response_msg"></div>'.$credit;
	return $form;
}



function khplugin_ajax_chat_start(){
	$fun_chatgpt_frontend_options = get_option( 'fun_chatgpt_frontend_option_name' ); // Array of All Options
	$use_external_jquery = $fun_chatgpt_frontend_options['use_external_jquery'];
	if ($use_external_jquery=="yes"){
		?>
<script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script>
<?php
	}
?>
<style>
.sample_chatgpt .chatbtn {
    background-color: #8b2626;
    display: inline-block;
    color: #fff;
    padding: 10px 20px;
    margin: 0 auto;
    cursor:pointer;
    margin-top:20px;
}
	
	#ask{
		min-width:300px;
		min-height:100px;
		width:100%;
	}
</style>
<script>
var score_count=1;

jQuery('.preloader_kh').hide();
jQuery(".chatbtn").click(function(){
	var chatask = jQuery('#ask').val();
    jQuery(".chatbtn").hide();
	jQuery('.preloader_kh').show();


	var data = { 'action':'kh_ask_chat', 'chatask':chatask}
	jQuery.ajax({
		url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
		type: "POST",
	  	data,
		dataType: "json",
		success: function(response) {
			jQuery('.preloader_kh').hide();
			
			
			if (response.status=="success"){
                jQuery('#ask').attr('disabled',true);
				jQuery(".response_msg").html('<br><span style="color:#8b2626;"><h4>ChatGPT Response:</h4></span>' +response.message+'<br><br><p><button onClick="location.reload();">Refresh page to ask ChatGPT again</button>');
			}else{
				jQuery(".response_msg").html('failed! ');
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			jQuery('.preloader_kh').hide();
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
        	alert("There seem to be an error."); // this will be "timeout"
    	},
		timeout: 925000 
		
    });
	
});
	
</script>
<?php
}


function khplugin_preloader(){
	return '<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="252px" height="57px" viewBox="0 0 128 29" xml:space="preserve"><g><path fill="#b25936" d="M-75.617,2.175h9.956l-8.533,24.65H-84.15ZM-64.1,4.322h8.345L-62.9,24.678h-8.345Zm11.48,1.527h6.867L-51.636,22.9H-58.5Zm11.386,2.29h4.982l-4.27,12.468H-45.5Zm11.846,2.036h3.634l-3.115,8.906H-32.5Zm11.23,1.781H-16l-1.847,5.089H-20ZM-88.346,4.322H-80l-7.153,20.356H-95.5Zm-12.271,1.527h6.868L-99.636,22.9H-106.5Zm-12.613,2.29h4.982l-4.271,12.468H-117.5Zm-12.155,2.036h3.634l-3.114,8.906H-128.5ZM-6.6,12.973H-5.25L-6.4,16.281H-7.75Zm72.98-10.8h9.956l-8.533,24.65H57.85ZM77.9,4.322h8.345L79.1,24.678H70.751Zm11.48,1.527h6.867L90.364,22.9H83.5Zm11.387,2.29h4.982l-4.271,12.468H96.5Zm11.845,2.036h3.634l-3.114,8.906H109.5Zm11.231,1.781H126l-1.846,5.089H122ZM53.654,4.322H62L54.846,24.678H46.5ZM41.383,5.849h6.867L42.364,22.9H35.5ZM28.77,8.139h4.982l-4.27,12.468H24.5ZM16.616,10.174h3.634L17.135,19.08H13.5Zm118.789,2.8h1.346L135.6,16.281H134.25ZM5.346,11.956H7.5L5.654,17.045H3.5Z"/><animateTransform attributeName="transform" type="translate" values="12 0;24 0;36 0;48 0;60 0;72 0;84 0;96 0;108 0;120 0;132 0;144 0" calcMode="discrete" dur="1800ms" repeatCount="indefinite"/></g></svg>
';

}

function khplugin_do_chat_run(){
	$ask = sanitize_text_field($_POST['chatask']);
	$answer = khplugin_create_ai_content($ask);
	
	$res = array(
	"status"=>"success",
	"message"=>$answer
	);
	echo json_encode($res);
	die();
}


function khplugin_get_today_limit(){
	$totalrequest = get_option( 'fun_chatgpt_check_requests_limit' ) ? get_option( 'fun_chatgpt_check_requests_limit' ):array();
    //return print_r($totalrequest,true);
	$fun_chatgpt_frontend_options = get_option( 'fun_chatgpt_frontend_option_name' ); // Array of All Options
	$daily_request_per_day =  $fun_chatgpt_frontend_options['daily_request_per_day_1']; 
	if (count($totalrequest)>0){
		$today=strval(date('d-m-Y'));
		if (array_key_exists($today,$totalrequest)){
            //$request=$totalrequest[$today];
			$count = count($totalrequest[$today]);
            //return $count;
            //return print_r($request,true);
			return "Welcome to our website! We have integrated ChatGPT on this page. It is free to use but we limit to ".$daily_request_per_day." requests per day so that it does not incur too much cost for us. Currently, there are <span id='credits_left'><strong><u>".($daily_request_per_day-$count)."</u></strong></span> requests left. ".$count." out of ".$daily_request_per_day." requests has been used.";
		}
		else{
			$count=0;
            //return $count;
			return "Welcome to our website! We have integrated ChatGPT on this page. It is free to use but we limit to ".$daily_request_per_day." requests per day so that it does not incur too much cost for us. Currently, there are <span id='credits_left'><strong><u>".($daily_request_per_day-$count)."</u></strong></span> requests left. ".$count." out of ".$daily_request_per_day." requests has been used.";
		}
        return ;
	}else{
        return;
    }
}

function khplugin_create_ai_content($content){
	$totalrequest = get_option( 'fun_chatgpt_check_requests_limit' )  ? get_option( 'fun_chatgpt_check_requests_limit' ):array();
	$fun_chatgpt_frontend_options = get_option( 'fun_chatgpt_frontend_option_name' ); // Array of All Options
	$openai_api_key = $fun_chatgpt_frontend_options['openai_api_key_0']; // OpenAI API Key
	$daily_request_per_day = (int) $fun_chatgpt_frontend_options['daily_request_per_day_1']; // Daily Request Per Day
	$show_created_by_knowhalim = $fun_chatgpt_frontend_options['show_created_by_knowhalim_2'];
	$api_type = $fun_chatgpt_frontend_options['protocol_3'];
	
	$no_allow=0;
	if ($totalrequest){
		$today=date('d-m-Y');
		if (array_key_exists($today,$totalrequest)){

			if (count($totalrequest[$today])>=$daily_request_per_day){
				$no_allow=1;
			}
			else{
				$totalrequest[$today][]=date("h:i:s A");
				update_option( 'fun_chatgpt_check_requests_limit',$totalrequest);
			}
		}
		else{
			$totalrequest[$today][]=date("h:i:s A");
			update_option( 'fun_chatgpt_check_requests_limit',$totalrequest);
		}
	}else{
		
		$today=date('d-m-Y');
		$arr = array();
		$arr[$today][]=date("h:i:s A");
		update_option( 'fun_chatgpt_check_requests_limit',$arr);
		
	}
	if ($no_allow==0){
		$array = array();
		$array['model']= "gpt-3.5-turbo";
		$params = array(
			"role"=>"user",
			"content"=> esc_attr(str_replace(PHP_EOL,'\n',$content))
		);
		$array['messages']= array($params);
		//Addded for Azure Open AI
		if($api_type=="Azure OpenAI"){
		$array['max_tokens']= 8000;
		$array['temperature']= 0.7;
		$array['frequency_penalty']= 0;
		$array['presence_penalty']= 0;
		$array['top_p']= 0.95;
		}
		//End Addded
	
		$apikey= $openai_api_key;

		$params_json = json_encode($array);

		$args_post = array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.1',
		'blocking' => true,
		'body'        => $params_json,
		'sslverify' => false,
		'headers'     => array(
			'Content-type' => 'application/json',
			'Authorization'=> 'Bearer '.$apikey,
			'api-key'=> $apikey
		  ),
		  'cookies' => array() 
		);
		
		$endpoint_selected ='https://api.openai.com/v1/chat/completions';
		if($api_type=="Azure OpenAI"){
			$endpoint_selected = 'https://openai-exp-001-a.openai.azure.com/openai/deployments/gpt-35-turbo/chat/completions?api-version=2023-03-15-preview';
		}
		
		
		
		$response = wp_remote_post( $endpoint_selected, $args_post );

		$res = $response['body'];

		$returnvalue = json_decode($res,true);

		$fulltext = str_replace("\n","<br>",trim($returnvalue['choices'][0]['message']['content']));

		return $fulltext;
	}else{
		return "Limit for today has reached! We limit to ".$daily_request_per_day.' requests per day in order not to incur high usage cost.';
	}
	
}



class FunChatGPTFrontend {
	private $fun_chatgpt_frontend_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'fun_chatgpt_frontend_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'fun_chatgpt_frontend_page_init' ) );
	}

	public function fun_chatgpt_frontend_add_plugin_page() {
		add_menu_page(
			'ChatGPT Playground', // page_title
			'ChatGPT Playground', // menu_title
			'manage_options', // capability
			'fun-chatgpt-frontend', // menu_slug
			array( $this, 'fun_chatgpt_frontend_create_admin_page' ), // function
			'dashicons-format-chat', // icon_url
			2 // position
		);
	}

	public function fun_chatgpt_frontend_create_admin_page() {
		$this->fun_chatgpt_frontend_options = get_option( 'fun_chatgpt_frontend_option_name' ); ?>

		<div class="wrap">
			<h2>ChatGPT Simple Playground</h2>
			<p>Enter your settings in order to use the tool. To show the chat form in the frontend use shortcode [khplugin_fun_chatgpt].<br>
            <?php echo khplugin_get_today_limit(); ?></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'fun_chatgpt_frontend_option_group' );
					do_settings_sections( 'fun-chatgpt-frontend-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function fun_chatgpt_frontend_page_init() {
		register_setting(
			'fun_chatgpt_frontend_option_group', // option_group
			'fun_chatgpt_frontend_option_name', // option_name
			array( $this, 'fun_chatgpt_frontend_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'fun_chatgpt_frontend_setting_section', // id
			'Settings', // title
			array( $this, 'fun_chatgpt_frontend_section_info' ), // callback
			'fun-chatgpt-frontend-admin' // page
		);
		
		add_settings_field(
			'protocol_3', // id
			'Select service used', // title
			array( $this, 'protocol_3_callback' ), // callback
			'fun-chatgpt-frontend-admin', // page
			'fun_chatgpt_frontend_setting_section' // section
		);

		add_settings_field(
			'openai_api_key_0', // id
			'Azure/OpenAI API Key', // title
			array( $this, 'openai_api_key_0_callback' ), // callback
			'fun-chatgpt-frontend-admin', // page
			'fun_chatgpt_frontend_setting_section' // section
		);
		
		

		add_settings_field(
			'daily_request_per_day_1', // id
			'Daily Request Per Day', // title
			array( $this, 'daily_request_per_day_1_callback' ), // callback
			'fun-chatgpt-frontend-admin', // page
			'fun_chatgpt_frontend_setting_section' // section
		);

		add_settings_field(
			'show_created_by_knowhalim_2', // id
			'Show created by Knowhalim', // title
			array( $this, 'show_created_by_knowhalim_2_callback' ), // callback
			'fun-chatgpt-frontend-admin', // page
			'fun_chatgpt_frontend_setting_section' // section
		);
		
		
		add_settings_field(
			'use_external_jquery', // id
			'Use external jQuery?', // title
			array( $this, 'use_external_jquery_callback' ), // callback
			'fun-chatgpt-frontend-admin', // page
			'fun_chatgpt_frontend_setting_section' // section
		);
		
		
	}

	
	public function protocol_3_callback() {
		?> <select name="fun_chatgpt_frontend_option_name[protocol_3]" id="protocol_3">
			<?php $selected = (isset( $this->fun_chatgpt_frontend_options['protocol_3'] ) && $this->fun_chatgpt_frontend_options['protocol_3'] === 'OpenAI') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>OpenAI</option>
			<?php $selected = (isset( $this->fun_chatgpt_frontend_options['protocol_3'] ) && $this->fun_chatgpt_frontend_options['protocol_3'] === 'Azure OpenAI') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>Azure OpenAI</option>
		</select> <?php
	}
	
	
	public function fun_chatgpt_frontend_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['openai_api_key_0'] ) ) {
			$sanitary_values['openai_api_key_0'] = sanitize_text_field( $input['openai_api_key_0'] );
		}

		if ( isset( $input['daily_request_per_day_1'] ) ) {
			$sanitary_values['daily_request_per_day_1'] = sanitize_text_field( $input['daily_request_per_day_1'] );
		}

		if ( isset( $input['show_created_by_knowhalim_2'] ) ) {
			$sanitary_values['show_created_by_knowhalim_2'] = $input['show_created_by_knowhalim_2'];
		}
		
		if ( isset( $input['protocol_3'] ) ) {
			$sanitary_values['protocol_3'] = $input['protocol_3'];
		}
		
		if (isset($input['use_external_jquery'])){
			$sanitary_values['use_external_jquery'] = $input['use_external_jquery'];
		}


		return $sanitary_values;
	}

	public function fun_chatgpt_frontend_section_info() {
		
	}

	public function openai_api_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="fun_chatgpt_frontend_option_name[openai_api_key_0]" id="openai_api_key_0" value="%s">',
			isset( $this->fun_chatgpt_frontend_options['openai_api_key_0'] ) ? esc_attr( $this->fun_chatgpt_frontend_options['openai_api_key_0']) : ''
		);
	}

	public function daily_request_per_day_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="fun_chatgpt_frontend_option_name[daily_request_per_day_1]" id="daily_request_per_day_1" value="%s">',
			isset( $this->fun_chatgpt_frontend_options['daily_request_per_day_1'] ) ? esc_attr( $this->fun_chatgpt_frontend_options['daily_request_per_day_1']) : ''
		);
	}

	public function use_external_jquery_callback() {
		?> <fieldset><?php $checked = ( isset( $this->fun_chatgpt_frontend_options['use_external_jquery'] ) && $this->fun_chatgpt_frontend_options['use_external_jquery'] === 'yes' ) ? 'checked' : '' ; ?>
		<label for="use_external_jquery-0"><input type="radio" name="fun_chatgpt_frontend_option_name[use_external_jquery]" id="show_created_by_knowhalim_2-0" value="yes" <?php echo $checked; ?>> Yes, use external jQuery</label><br>
		<?php $checked = ( isset( $this->fun_chatgpt_frontend_options['use_external_jquery'] ) && $this->fun_chatgpt_frontend_options['use_external_jquery'] === 'no' ) ? 'checked' : '' ; ?>
		<label for="use_external_jquery-1"><input type="radio" name="fun_chatgpt_frontend_option_name[use_external_jquery]" id="use_external_jquery-1" value="no" <?php echo $checked; ?>> No, my theme comes with jQuery</label></fieldset> <?php
	}
	
	public function show_created_by_knowhalim_2_callback() {
		?> <fieldset><?php $checked = ( isset( $this->fun_chatgpt_frontend_options['show_created_by_knowhalim_2'] ) && $this->fun_chatgpt_frontend_options['show_created_by_knowhalim_2'] === 'yes' ) ? 'checked' : '' ; ?>
		<label for="show_created_by_knowhalim_2-0"><input type="radio" name="fun_chatgpt_frontend_option_name[show_created_by_knowhalim_2]" id="show_created_by_knowhalim_2-0" value="yes" <?php echo $checked; ?>> Sure! I will credit the developer</label><br>
		<?php $checked = ( isset( $this->fun_chatgpt_frontend_options['show_created_by_knowhalim_2'] ) && $this->fun_chatgpt_frontend_options['show_created_by_knowhalim_2'] === 'no' ) ? 'checked' : '' ; ?>
		<label for="show_created_by_knowhalim_2-1"><input type="radio" name="fun_chatgpt_frontend_option_name[show_created_by_knowhalim_2]" id="show_created_by_knowhalim_2-1" value="no" <?php echo $checked; ?>> No, maybe next time</label></fieldset> <?php
	}

}
if ( is_admin() )
	$fun_chatgpt_frontend = new FunChatGPTFrontend();

