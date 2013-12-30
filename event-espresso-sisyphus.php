<?php 

/**
 * Plugin Name: Event Espresso - Sisyphus
 * Plugin URI: http://eran.sh
 * Description: Bring the convenience of Sisyphus's Local Storage based form persistence to Event Espresso!
 * Version: 0.0.1
 * Author: Eran Schoellhorn
 * Author URI: http://eran.sh
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Instantiate plugin on init
 */
add_action( 'init', 'call_EE_Sisphus' );
function call_EE_Sisphus() {
    new EE_Sisphus();
}

class EE_Sisphus{

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_sisphus' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'load_style' ));
		add_action( 'action_hook_espresso_registration_page_bottom', array( $this, 'initialize_sisyphus' ));
	}

	public function load_sisphus() {
		wp_enqueue_script(
			'sisphus',
			plugins_url( 'sisyphus/sisyphus.min.js' , __FILE__ ),
			array( 'jquery' )
		);
	}

	public function load_style(){
		wp_register_style( 'sisyphus-alert', plugins_url( 'css/sisyphus-alert.css' , __FILE__ ) );
		wp_enqueue_style( 'sisyphus-alert' );
	}

	public function initialize_sisyphus( $event_id, $event_meta, $all_meta ){
		?>
		<script type="text/javascript">
			(function($){
				$( "#registration_form" ).sisyphus({
					customKeySuffix : "T<?php echo $event_id; ?>", 
					excludeFields: $( "[type=\"hidden\"]" ), 
					onRestore: function() {
						$('body').prepend("<div class='sisyphus-alert'/>")
						var $sisyphusAlert = $('.sisyphus-alert');
						$sisyphusAlert.html("Your registration progress was restored! <a href='#' class='sisyphus-reset'>Click here to reset the form.</a>");
						$sisyphusAlert.find('a').on("click", function(){
							$('#registration_form').sisyphus().manuallyReleaseData();
							location.reload();
						});
						setTimeout( function(){$sisyphusAlert.fadeOut("slow")}, 10000 );
					}
				}); 
			})(jQuery);
		</script>

		<?php
	}

}

