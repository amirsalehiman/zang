<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'PW_Price_Tools' ) ) :

class PW_Price_Tools {
	
	public $options;
	
	public function __construct() {
		global $PW;
		$this->options = $PW->get_options();
		
		if($this->options['enable_call_for_price'] == 'yes'){
			add_filter('woocommerce_empty_price_html', array( $this, 'on_empty_price' ), PHP_INT_MAX - 1 );
			add_filter('woocommerce_sale_flash', array( $this, 'hide_sales_flash' ), PHP_INT_MAX, 3 );	
		}
		
		if($this->options['persian_price'] == 'yes'){
			add_filter('woocommerce_sale_price_html', array( $this, 'persian_number') );
			add_filter('woocommerce_price_html', array( $this, 'persian_number') );
		}
		
	}

	public function hide_sales_flash( $onsale_html, $post, $product ) {
		return ('yes' == $this->options['call_for_price_hide_sale_sign'] && '' == $product->get_price() ) ? "" : $onsale_html;
	}
	
	public function is_related(){
		global $post;
		return is_singular() !== is_single($post->ID);
	}
	
	public function on_empty_price( $price ) {
		if(is_archive())
			return $this->options['call_for_price_text_on_archive'];
		elseif($this->is_related())
			return $this->options['call_for_price_text_on_related'];
		elseif(is_single())
			return $this->options['call_for_price_text'];
		elseif(is_home())
			return $this->options['call_for_price_text_on_home'];
	}
	
	public function persian_number($price){
		return str_replace(range(0, 9), array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'), $price);
	}
}

endif;

return new PW_Price_Tools();

?>