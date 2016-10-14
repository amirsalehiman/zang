<?php

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Persian_Wooommerce_Address' ) ) :

class Persian_Wooommerce_Address extends Persian_Wooommerce_Plugin {
	
	protected $states;
	private $fields = array();
	private $Country = 'IR';
	private $selected_city = array();
	private static $is_run;
	
	public function __construct() {
		
		global $PW;
			
		add_filter('woocommerce_get_country_locale',  				array($this, 'locales') );
		add_filter('woocommerce_localisation_address_formats',  	array($this, 'address_formats') );
		add_filter('woocommerce_states',							array($this, 'iran_states'), 10, 1 );

		if ( $PW->get_options('enable_iran_cities') == 'yes' ) {
		
			add_filter('woocommerce_checkout_fields',  					array( $this, 'checkout_fields_cities') );		
			add_filter('woocommerce_form_field_billing_iran_cities', 	array( $this, 'iran_cities_field') , 11, 4 );	
			add_filter('woocommerce_form_field_shipping_iran_cities', 	array( $this, 'iran_cities_field') , 11, 4 );			
			add_action('woocommerce_after_order_notes', 				array( $this, 'inline_js'));
			add_action('wp_footer', 									array( $this, 'inline_js'));
			add_action('wp_enqueue_scripts',							array( $this, 'external_js' ) );
		}

		$this->states = array(
			'AL'  => 'البرز',
			'AR'  => 'اردبیل',
			'AE'  => 'آذربایجان شرقی',
			'AW'  => 'آذربایجان غربی',
			'BU'  => 'بوشهر',
			'CM'  => 'چهارمحال و بختیاری',
			'FA'  => 'فارس',
			'GI'  => 'گیلان',
			'GO'  => 'گلستان',
			'HD'  => 'همدان',
			'HG'  => 'هرمزگان',
			'IL'  => 'ایلام',
			'IS'  => 'اصفهان',
			'KE'  => 'کرمان',
			'BK'  => 'کرمانشاه',
			'KS'  => 'خراسان شمالی',
			'KV'  => 'خراسان رضوی',
			'KJ'  => 'خراسان جنوبی',
			'KZ'  => 'خوزستان',
			'KB'  => 'کهگیلویه و بویراحمد',
			'KD'  => 'کردستان',
			'LO'  => 'لرستان',
			'MK'  => 'مرکزی',
			'MN'  => 'مازندران',
			'QZ'  => 'قزوین',
			'QM'  => 'قم',
			'SM'  => 'سمنان',
			'SB'  => 'سیستان و بلوچستان',
			'TE'  => 'تهران',
			'YA'  => 'یزد',
			'ZA'  => 'زنجان'
		);
	}

	public function locales( $locales ){
		$locales[$this->Country] = array(
			'state' => array( 'label' => __( 'Province', 'woocommerce') ),
			'postcode' => array( 'label' => __('Postcode', 'woocommerce') )
		);
		return $locales;
	}
		
	public function address_formats( $formats ){
		$formats[$this->Country] = "{company}\n{first_name} {last_name}\n{country}\n{state}\n{city}\n{address_1} - {address_2}\n{postcode}";
		return $formats;
	}
		
	public function iran_states( $states ) {
		
		global $PW;
		
		$states['IR'] = $this->states;
		
		if($PW->get_options("allowed_states") == "all")
			return $states;
		
		$selections = $PW->get_options('specific_allowed_states');

		if(is_array($selections))
			$states['IR'] = array_intersect_key($this->states, array_flip($selections));
		
		return $states;

	}

	public function checkout_fields_cities( $fields ) {
		
		global $recovery_fields;		
		$this->fields = $recovery_fields = $fields;
		
		$types = array('billing' , 'shipping');
		foreach ( $types as $type) {
			
			$city_classes = implode( ',', $fields[$type][$type . '_city']['class']);
			$city_classes = str_ireplace( 'form-row-wide', 'form-row-last', $city_classes );
			
			$fields[$type][$type . '_city']['class'] = explode( ',', $city_classes );
			$fields[$type][$type . '_city']['type'] = $type . '_iran_cities';
			$fields[$type][$type . '_city']['options'] = array( '' => '' );
		}
		
		return $fields;
	}
	
	public function iran_cities_field( $field, $key, $args, $value ) {

		if ( $args['type'] == 'billing_iran_cities' || $args['type'] == 'shipping_iran_cities' ) {	
			
			$type = explode( '_' , $args['type']);
			if ( !empty($value) )
				$this->selected_city[] = $value . '_vsh_' . $type[0];
			
			$required = $args['required'] ? ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>' : '';
			
			$args['label_class'] = array( );
			if ( is_string( $args['label_class'] ) )
				$args['label_class'] = array( $args['label_class'] );

			if ( is_null( $value ) )
				$value = !empty($args['default']) ? $args['default'] : '';

			$custom_attributes = array();
			if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) )
				foreach ( $args['custom_attributes'] as $attribute => $attribute_value )
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			
			if ( ! empty( $args['validate'] ) )
				foreach( $args['validate'] as $validate )
					$args['class'][] = 'validate-' . $validate;

			$args['placeholder'] = __( 'یک شهر انتخاب کنید', 'woocommerce' );
			
			$label_id = $args['id'];
			$field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';
			
			$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '">' .
						'<option value="">'.__( 'ابتدا یک استان انتخاب نمایید', 'woocommerce' ) .'</option>' .
					'</select>';
			
			$field_html = '';

			if ( $args['label'] && 'checkbox' != $args['type'] )
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';

			$field_html .= $field;

			if ( $args['description'] )
				$field_html .= '<span class="description">' . esc_attr( $args['description'] ) . '</span>';

			$container_class = 'form-row ' . esc_attr( implode( ' ', $args['class'] ) );
			$container_id = esc_attr( $args['id'] ) . '_field';

			$after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

			$field = sprintf( $field_container, $container_class, $container_id, $field_html ) . $after;
			
		}
		
		return $field;
	}

	
	public function external_js() {
		
		global $PW;
		
		wp_dequeue_script('pw-iran-cities');
		wp_deregister_script('pw-iran-cities');
	
		wp_register_script('pw-iran-cities', $PW->plugin_url('include/assets/js/iran-cities-sorted.min.js'), array() , PERSIAN_WOOCOMMERCE_VERSION, true );
		wp_enqueue_script( 'pw-iran-cities' );
	}
	
	
	public function inline_js() { 
		
		if ( !empty( self::$is_run ) )
			return true;
		
		self::$is_run = 'applied';
		
		global $recovery_fields;		
		if ( !empty($this->fields) )
			$fields = $this->fields;
		else if ( !empty( $recovery_fields ) )
			$fields = $recovery_fields;
		else
			return false;
		
		$billing_value = $shipping_value = '';
		
		foreach ( $this->selected_city as $selected ) {
			
			list( $value , $type) = explode( '_vsh_', $selected );
			
			if ( !empty($type) &&  $type == 'billing' && !empty($value) )
				$billing_value = $value;
			
			if ( !empty($type) &&  $type == 'shipping' && !empty($value) )
				$shipping_value = $value;
			
		}
		
		$script = '';
		$types = array('billing' , 'shipping');
		foreach ( $types as $type) {
			
			$label 		 = $this->isset_index_city( $type, $fields, 'label' );
			$label_class = $this->isset_index_city( $type, $fields, 'label_class' );
			$class 		 = $this->isset_index_city( $type, $fields, 'class' );
			$required	 = $this->isset_index_city( $type, $fields, 'required' );
			$clear 		 = $this->isset_index_city( $type, $fields, 'clear' );
			$placeholder = $this->isset_index_city( $type, $fields, 'placeholder' );	
			?>
			
			<script type="text/javascript">
			
				var select_<?php echo $type; ?> = jQuery('p#<?php echo $type; ?>_city_field').html();
				var className_<?php echo $type; ?> = jQuery('#<?php echo $type; ?>_city_field').attr('class');
				
				(function($){<?php echo $type; ?>_iran_country_changed = function(){$('body').on('change', 'select#<?php echo $type; ?>_country, input#<?php echo $type; ?>_country',function(){
					if ($('#<?php echo $type; ?>_country').val() == '<?php echo $this->Country ?>' ) {
						$('p#<?php echo $type; ?>_city_field').html(select_<?php echo $type; ?>);
						$('#<?php echo $type; ?>_city_field').attr('class', className_<?php echo $type; ?>);
					}
					else {
						$('p#<?php echo $type; ?>_city_field').replaceWith('<p id="<?php echo $type; ?>_city_field" class="form-row <?php echo $class; ?>"><label class="<?php echo $label_class; ?>" for="<?php echo $type; ?>_city"><?php echo $label; ?><?php echo $required ? '<abbr class="required" title="'.esc_attr__( 'required', 'woocommerce'  ).'">*</abbr>' : ''; ?></label><input id="<?php echo $type; ?>_city" class="input-text " type="text" value="" placeholder="<?php echo $placeholder; ?>" name="<?php echo $type; ?>_city"></p><?php echo $clear ? '<div class="clear"></div>' : '' ?>');
					}
					if ( ! $( '#ship-to-different-address input' ).is( ':checked' ) ) {
						$( 'div.shipping_address' ).show(); 
						$( 'div.shipping_address' ).slideUp();
					}
				});}})(jQuery);
				jQuery(document).ready(function(jQuery){<?php echo $type; ?>_iran_country_changed();});
						
				(function($){<?php echo $type; ?>_iran_state_changed = function(){
					$('body').on('change', 'select#<?php echo $type; ?>_state, input#<?php echo $type; ?>_state',function(){
					$('select#<?php echo $type; ?>_city').empty();
					$('select#<?php echo $type; ?>_city').append(Persian_Woo_iranCities(jQuery('#<?php echo $type; ?>_state').val()));
					$('select#<?php echo $type; ?>_city').val('<?php echo ${$type . '_value'}; ?>');
					if ( $('#<?php echo $type; ?>_city').val() == 'undefined' || $('#<?php echo $type; ?>_city').val() == null ) {
						$('select#<?php echo $type; ?>_city').val('').trigger('change');
					}
					$('select#<?php echo $type; ?>_city').trigger("chosen:updated");					
				});}})(jQuery);
				jQuery(document).ready(function(jQuery){<?php echo $type; ?>_iran_state_changed();});
					
			</script>
			<style>
				.select2-container .select2-choice .select2-arrow b:after {
					margin-left: 0;
				}
			</style>
			<?php
		}
	}
	
	
		
	protected function isset_index_city( $type = 'billing' , $fields , $index ) {
		
		if ( isset( $fields[$type][ $type . '_city'][$index] ) ) {
			
			$var = $fields[$type][ $type . '_city'][$index];
			if ( is_array( $var ))
				return implode( ' ' , $var );
			return $var;
		}
		
		return '';
	}
	
}

endif;

$PW->iran_address = new Persian_Wooommerce_Address();

?>