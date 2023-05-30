<?php
/**
 * Plugin Name: SafeOpt Tags
 * Plugin URI: https://www.safeopt.com/
 * Description: Tags needed for SafeOpt widget and conversion tracking
 * Version: 1.0.0
 * Author: Addshoppers
 * Author URI: https://www.addshoppers.com/
 * Description: SafeOpt Tags plugin is used to add the SafeOpt widget and conversion tracking to your site.
 * License: GPLv2 or later
 * Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 * Text Domain: safeopt-tags
 * Domain Path: /languages
 */

/*
 * If this file is called directly, abort.
 */
if ( !defined( 'ABSPATH' ) ) {
    die;
}

if (!class_exists('SafetoptTags')){

    class SafetoptTags {
        public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

        public function register(){
            
            add_action('admin_menu', array($this, 'add_admin_pages'));
            add_filter( "plugin_action_links_$this->plugin", array( $this, 'add_settings_link' ) );
            add_action('admin_init', array( $this, 'setup_sections'));
            add_action('admin_init', array( $this, 'setup_fields' ));
            add_action('wp_footer', array( $this, 'add_global_site_tag' ));
            add_action('woocommerce_thankyou', array( $this, 'add_conversion_tracking' ));

            if( empty( get_option( 'site_id' ) ) ) {
                add_action( 'admin_notices',array($this, 'admin_notice_site_id_error' ));
              }
        }
        
        public function add_admin_pages(){
            add_menu_page('SafeOpt Tags', 'SafeOpt Tags', 'manage_options', 'safeopt-tags', array($this, 'admin_index'), 'dashicons-admin-generic', 110);
        }
        
        public function admin_index(){
            ?>
            <div class="wrap">
                <h2>SafeOpt Tags Settings Page</h2>
                <form method="post" action="options.php">
                    <?php
                        settings_errors();
                        settings_fields( 'safeopt-tags' );
                        do_settings_sections( 'safeopt-tags' );
                        submit_button();
                    ?>
                </form>
            </div> 
            <?php
        }

        public function add_settings_link($links){
            $settings_link = '<a href="admin.php?page=safeopt-tags">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
        }

        public function setup_sections() {
            add_settings_section( 'setting_section', 'SafeOpt Tags Settings', array($this, 'section_callback'), 'safeopt-tags' );
        }
        
        public function section_callback( $arguments ) {
            echo 'SafeOpt Site ID is required for tags setup. Please contact help@addshoppers.com or check the get started page in the dashboard to get your Site ID.';
        }

        public function setup_fields() {
            $fields = array(
                array(
                    'uid' => 'site_id',
                    'label' => 'Site ID',
                    'section' => 'setting_section',
                    'type' => 'text',
                    'options' => false,
                    'sanitize_callback' => 'verify_site_id'
                )
            );
            foreach( $fields as $field ){
                register_setting( 'safeopt-tags', $field['uid'], array($this, 'verify_site_id') );
                add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'safeopt-tags', $field['section'], $field );
            }
        }
        
        public function field_callback( $arguments ) {
            $value = get_option( $arguments['uid'] ); // Get the current value, if there is one
        
            // Check which type of field we want
            switch( $arguments['type'] ){
                case 'text': // If it is a text field
                    printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" size="26" />', $arguments['uid'], $arguments['type'], $value );
                    break;
            }
        }

        public function verify_site_id( $input ){
            if( strlen($input) == 24 ){
                if ($this->site_id_api_check($input)){
                    add_settings_error( 'site_id', 'site_id_success', 'Site ID set and SafeOpt Tags installed.', 'success' );
                    return $input;
                }
                else{
                    add_settings_error( 'site_id', 'site_id_error', 'Site ID: ' . $input . ', is invalid or does not exist, please verify and try again.', 'error' );
                }  
            }
            else{
                add_settings_error( 'site_id', 'site_id_error', 'Site ID: ' . $input . ', is invalid or does not exist, please verify and try again.', 'error' );
            }
        }

        public function admin_notice_site_id_error() {
            global $pagenow;
            if ( $pagenow == 'plugins.php' or $pagenow == 'index.php' ) {
                $class = 'notice notice-error is-dismissible';
                $message = __( 'SafeOpt Tags Plugin: Site ID is not set, it must be 24 characters long.  Please set Site ID for the tags to work.', 'sample-text-domain' );
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
            }
        }

        public function admin_notice_site_id_success() {
            global $pagenow;
            if ( $pagenow == 'plugins.php') {
                $class = 'notice notice-success is-dismissible';
                $message = __( 'Site ID set and SafeOpt Tags installed.', 'sample-text-domain' );
                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
            }
        }
        

        public function add_global_site_tag(){
            $site_id = get_option('site_id');
            if (!empty($site_id) and strlen($site_id) == 24) {
                ?>
                <script>
                    var AddShoppersWidgetOptions = { 'loadCss': false, 'pushResponse': false };
                        (!function(){
                            var t=document.createElement("script");
                            t.type="text/javascript",
                            t.async=!0,
                            t.id="AddShoppers",
                            t.src="https://shop.pe/widget/widget_async.js#<?php echo $site_id; ?>",
                            document.getElementsByTagName("head")[0].appendChild(t)
                        }());
                </script>
                <?php
            }
        }

        public function add_conversion_tracking($order_id){
            $order = wc_get_order( $order_id );
            $order_subtotal = $order->get_subtotal();
            $order_discount_total = $order->get_discount_total();
            $order_subtotal_after_discount = $order_subtotal - $order_discount_total;
            $currency = $order->get_currency();
            $email = $order->get_billing_email();
            $offers = $order->get_coupon_codes(); 
            $offer_code = '';
            if (!empty($offers)){
                $offer_code = $offers[0];
            }
            $site_id = get_option('site_id');
            if (!empty($site_id) and strlen($site_id) == 24) {
                ?>
                <script type='text/javascript'>
                    var AddShoppersWidgetOptions = { 'loadCss': false, 'pushResponse': false };
                    AddShoppersConversion = {
                        order_id: '<?php echo $order_id; ?>',
                        value: '<?php echo $order_subtotal_after_discount; ?>',
                        currency: '<?php echo $currency; ?>',
                        email: '<?php echo $email; ?>',
                        offer_code: '<?php echo $offer_code; ?>'
                    };
                    var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true; js.id = 'AddShoppers';
                    js.src = ('https:' == document.location.protocol ? 'https://shop.pe/widget/' : 'http://d3rr3d0n31t48m.cloudfront.net/widget/') + 'widget_async.js#<?php echo $site_id; ?>';
                    document.getElementsByTagName('head')[0].appendChild(js);
                </script>
                <?php
            }
        }

        public function site_id_api_check($id){
            $url = 'https://app.shop.pe/app/site/verify?site=' . $id;
            $api_response = wp_remote_get( $url );
            $body = wp_remote_retrieve_body( $api_response );
            $data = json_decode( $body );
            if ($data->success == true){
                return true;
            }
            else{
                return false;
            }
        }
        
    }

    $safetopt_tags = new SafetoptTags();
    $safetopt_tags->register();

}
