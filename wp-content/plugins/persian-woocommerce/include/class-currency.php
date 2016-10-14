<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Persian_Wooommerce_Currencies' ) ) :

class Persian_Wooommerce_Currencies extends Persian_Wooommerce_Plugin {

	public function __construct() {
		add_filter('woocommerce_currencies', array($this, 'iran_currencies') );
		add_filter('woocommerce_currency_symbol', array($this, 'iran_currencies_symbol'), 10, 2);
	}

	public function iran_currencies( $currencies ) {

		$currencies += array(
			'IRR' => __( 'ریال', 'woocommerce' ),
			'IRHR' => __( 'هزار ریال', 'woocommerce' ),
			'IRT' => __( 'تومان', 'woocommerce' ),
			'IRHT' => __( 'هزار تومان', 'woocommerce' )
		);

		return $currencies;

	}

	public function iran_currencies_symbol( $currency_symbol, $currency ) {

		switch( $currency ) {

			case 'IRR': return 'ریال';
			case 'IRHR': return 'هزار ریال';
			case 'IRT': return 'تومان';
			case 'IRHT': return 'هزار تومان';

		}

		return $currency_symbol;

	}

}

endif;

$PW->currencies = new Persian_Wooommerce_Currencies();

?>