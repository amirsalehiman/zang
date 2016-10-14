<?php
/**
 * Makes EDD compatible with WP-Parsidate plugin
 *
 * @package                 WP-Parsidate
 * @subpackage              Plugins/EDD
 * @author                  Ehsaan
 */

class WPP_EDD {
    public static $instance = null;

    /**
     * Returns an instance of class
     *
     * @return          WPP_WooCommerce
     */
    public static function getInstance() {
        if ( self::$instance == null )
            self::$instance = new WPP_EDD();

        return self::$instance;
    }

    /**
     * Hooks required tags
     */
    private function __construct() {
        global $wpp_settings;
        add_filter( 'wpp_plugins_compability_settings', array( $this, 'add_settings' ) );

        if ( isset( $wpp_settings['edd_prices'] ) && $wpp_settings['edd_prices'] != 'disable' ) {
            add_filter( 'edd_rial_currency_filter_after', 'per_number', 10, 2 );
        }

        if ( isset( $wpp_settings['edd_rial_fix'] ) && $wpp_settings['edd_rial_fix'] != 'disable' ) {
            add_filter( 'edd_rial_currency_filter_after', array( $this, 'rial_fix' ), 10, 2 );
        }
    }

    /**
     * RIAL fix for EDD
     */
    public function rial_fix( $price, $did ) {
    	return str_replace( 'RIAL', 'ریال', $price );
    }

    /**
     * Adds settings for toggle fixing
     *
     * @param           array $old_settings Old settings
     * @return          array New settings
     */
    public function add_settings( $old_settings ) {
        $options = array(
            'enable'		=>	__( 'Enable', 'wp-parsidate' ),
            'disable'		=>	__( 'Disable', 'wp-parsidate' )
        );
        $settings = array(
            'edd'       =>  array(
                'id'            =>  'edd',
                'name'          =>  __( 'Easy Digital Downloads', 'wp-parsidate' ),
                'type'          =>  'header'
            ),
            'edd_prices'     =>  array(
                'id'            =>  'edd_prices',
                'name'          =>  __( 'Fix prices', 'wp-parsidate' ),
                'type'          =>  'radio',
                'options'       =>  $options,
                'std'           =>  'disable'
            ),
            'edd_rial_fix'     =>  array(
                'id'            =>  'edd_rial_fix',
                'name'          =>  __( 'Replace ریال with RIAL', 'wp-parsidate' ),
                'type'          =>  'radio',
                'options'       =>  $options,
                'std'           =>  'disable'
            )
        );

        return array_merge( $old_settings, $settings );
    }
}

return WPP_EDD::getInstance();
