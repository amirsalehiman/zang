<?php

/**
 * Meta Responsive
 */
add_action( 'wp_head', 'toko_wphead_responsive', 0);
function toko_wphead_responsive() {
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
}

/**
 * Search Form - Matched to WooCommerce Search Form
 */
add_filter( 'get_search_form', 'toko_get_search_form' );
if ( ! function_exists( 'toko_get_search_form' ) ) :
function toko_get_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form-box" action="' . esc_url( home_url( '/'  ) ) . '">
	<label class="screen-reader-text" for="s">' . esc_html__( 'Search for:', 'bookie-wp' ) . '</label>
	<input type="search" class="search-field" placeholder="' . esc_html__( 'Search&hellip;', 'bookie-wp' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_html__( 'Search for:', 'bookie-wp' ) . '" />
	<input type="submit" class="search-submit" value="' . esc_html__( 'Search', 'bookie-wp' ) . '" />
	</form>';
	return $form;
}
endif;

/**
 * Include Slider in Home Page
 */
if( function_exists( 'toko_vc_sliders' ) ) {
	add_action( 'before_main_content', 'toko_include_home_slider', 3 );
	function toko_include_home_slider() {
		if( is_front_page() || is_home() || is_page() ) {
			$page_id = get_queried_object_id();
			if ( $page_id ) {
				$header_slider = get_post_meta( $page_id, '_toko_header_slider', true );
				if ( $header_slider ) {
					echo do_shortcode( '[toko_slider id="' . $header_slider . '"]' );
				}
			}
		}
	}

	add_filter( 'body_class', 'toko_theme_has_slider' );
	function toko_theme_has_slider( $classes ) {
		if( is_front_page() || is_home() || is_page() ) {
			$page_id = get_queried_object_id();
			if ( $page_id ) {
				$header_slider = get_post_meta( $page_id, '_toko_header_slider', true );
				if ( $header_slider ) {
					$classes[] = 'has_slider';
				}
			}
		}
		return $classes;
	}

	add_filter( 'body_class', 'toko_theme_header_absolute' );
	function toko_theme_header_absolute( $classes ) {
		if( is_front_page() || is_home() || is_page() ) {
			$page_id = get_queried_object_id();
			if( $page_id ) {
				$header_absolute = get_post_meta( $page_id, '_toko_header_absolute', true );
				if( $header_absolute ) {
					$classes[] = 'header_absolute';
				}
			}
		}
		return $classes;
	}
}

/**
 * Include Site Title
 */
add_action( 'before_main_content', 'toko_site_title', 5 );
function toko_site_title() {
	if ( class_exists('woocommerce') && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		get_template_part( 'block-site-title', 'woocommerce' );
	}
	else {
		get_template_part( 'block-site-title' );
	}
}

/**
 * Include custom search
 */
add_action( 'before_main_content', 'toko_custom_book_search', 7 );
function toko_custom_book_search() {
	if( is_page_template() )
		return;
	
	get_template_part( 'block-book-search' );
}

/**
 * Get contact form.
 */
function toko_get_contact_form( $args = array() ){
	global $wp_query;

	$defaults = array(
		'title' => esc_html__( 'Leave a Message', 'bookie-wp' ),
		'email' => get_bloginfo('admin_email'),
		'subject' => esc_html__( 'Message via the contact form', 'bookie-wp' ),
		'sendcopy' => 'yes',
		'question' => '',
		'answer' => '',
		'button_text' => esc_html__( 'Submit', 'bookie-wp' )
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	if( trim($email) == '' )
		$email = get_bloginfo('admin_email');

	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	if ( strpos($email, $sitename) !== false ) {
		$from_email = $email;
	}
	else {
		$from_email = 'noreply@'.$sitename;
	}

	$html = '';
	$error_messages = array();
	$notification = false;
	$email_sent = false;
	if ( ( count( $_POST ) > 3 ) && isset( $_POST['submitted'] ) ) {
		if ( isset ( $_POST['checking'] ) && $_POST['checking'] != '' )
			$error_messages['checking'] = 1;
		if ( isset ( $_POST['contact-name'] ) && $_POST['contact-name'] != '' )
			$message_name = $_POST['contact-name'];
		else 
			$error_messages['contact-name'] = esc_html__( 'Please enter your name', 'bookie-wp' );
		if ( isset ( $_POST['contact-email'] ) && $_POST['contact-email'] != '' && is_email( $_POST['contact-email'] ) )
			$message_email = $_POST['contact-email'];
		else 
			$error_messages['contact-email'] = esc_html__( 'Please enter your email address (and please make sure it\'s valid)', 'bookie-wp' );
		if ( isset ( $_POST['contact-message'] ) && $_POST['contact-message'] != '' )
			$message_body = $_POST['contact-message'] . "\n\r\n\r";
		else 
			$error_messages['contact-message'] = esc_html__( 'Please enter your message', 'bookie-wp' );
		if ( $question && $answer ) {
			if ( isset ( $_POST['contact-quiz'] ) && $_POST['contact-quiz'] != '' ) {
				$message_quiz = $_POST['contact-quiz']; 
				if ( esc_attr( $message_quiz ) != esc_attr( $answer ) )
					$error_messages['contact-quiz'] = esc_html__( 'Your answer was wrong!', 'bookie-wp' );
			}
			else {
				$error_messages['contact-quiz'] = esc_html__( 'Please enter your answer', 'bookie-wp' );
			}
		}
		if ( count( $error_messages ) ) {
			$notification = '<p class="alert alert-danger">' . esc_html__( 'There were one or more errors while submitting the form.', 'bookie-wp' ) . '</p>';
		} 
		else {
			$ipaddress = '';
			if ( isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] )
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] )
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if( isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] )
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if( isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'] )
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if( isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] )
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] )
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$message_body = esc_html__( 'Email:', 'bookie-wp' ) . ' '. $message_email . "\r\n\r\n" . $message_body;
			$message_body = esc_html__( 'Name:', 'bookie-wp' ) . ' '. $message_name . "\r\n" . $message_body;
			$message_body = $message_body."\r\n\r\n".__( 'IP Address:', 'bookie-wp' ).$ipaddress . "\r\n" . esc_html__( 'User Agent:', 'bookie-wp' ).$useragent;
			
			$headers = array();
			$headers[] = 'From: '.$message_name.' <' . $from_email . '>';
			$headers[] = 'Reply-To: '.$message_email;
			$email_sent = wp_mail($email, $subject, $message_body, $headers);
			
			if ( $sendcopy == 'yes' ) {
				// Send a copy of the e-mail to the sender, if specified.
				if ( isset( $_POST['send-copy'] ) && $_POST['send-copy'] == 'true' ) {
					$subject = esc_html__( '[COPY]', 'bookie-wp' ) . ' ' . $subject;
					$headers = array();
					$headers[] = 'From: '.get_bloginfo('name').' <' . $from_email . '>';
					$headers[] = 'Reply-To: '.$email;
					$email_sent = wp_mail($message_email, $subject, $message_body, $headers);
				}
			}
			
			if( $email_sent == true ) {
				$notification = do_shortcode( '<p class="alert alert-success">' . esc_html__( 'Your email was successfully sent.', 'bookie-wp' ) . '</p>' );
			}
			else {
				$notification = '<p class="alert alert-danger">' . esc_html__( 'There were technical error while submitting the form. Sorry for the inconvenience.', 'bookie-wp' ) . '<p>';
			}
	
		}
	}

	if( $email_sent == true ) {
		return '<div class="section-contact-form">'.$notification.'</div>';
	}
	
	$html .= '<div class="section-contact-form">' . "\n";
	$html .= $notification;
	if ( $email == '' ) {
		$html .= do_shortcode( '<p class="alert alert-danger">' . esc_html__( 'E-mail has not been setup properly. Please add your contact e-mail!', 'bookie-wp' ) . '</p>' );
	} 
	else {
		$html .= '<form action="" class="contact-form" method="post">' . "\n";
		$contact_name = '';
		if( isset( $_POST['contact-name'] ) ) { $contact_name = $_POST['contact-name']; }
		$contact_email = '';
		if( isset( $_POST['contact-email'] ) ) { $contact_email = $_POST['contact-email']; }
		$contact_message = '';
		if( isset( $_POST['contact-message'] ) ) { $contact_message = stripslashes( $_POST['contact-message'] ); }
		
		$html .= '<div class="form-group '.( isset($error_messages['contact-name']) ? 'has-error' : '' ).'">' . "\n";
		$html .= '<input placeholder="' . esc_html__( 'Your Name', 'bookie-wp' ) . '" type="text" name="contact-name" id="contact-name" value="' . esc_attr( $contact_name ) . '" class="form-control" />' . "\n";
		if( isset($error_messages['contact-name']) ) {
			$html .= '<span class="help-block">' . $error_messages['contact-name'] . '</span>' . "\n";
		}
		$html .= '</div>' . "\n";

		$html .= '<div class="form-group '.( isset($error_messages['contact-email']) ? 'has-error' : '' ).'">' . "\n";
		$html .= '<input placeholder="' . esc_html__( 'Your Email', 'bookie-wp' ) . '" type="text" name="contact-email" id="contact-email" value="' . esc_attr( $contact_email ) . '" class="form-control" />' . "\n";
		if( isset($error_messages['contact-email']) ) {
			$html .= '<span class="help-block">' . $error_messages['contact-email'] . '</span>' . "\n";
		}
		$html .= '</div>' . "\n";

		$html .= '<div class="form-group '.( isset($error_messages['contact-message']) ? 'has-error' : '' ).'">' . "\n";
		$html .= '<textarea placeholder="' . esc_html__( 'Your Message', 'bookie-wp' ) . '" name="contact-message" id="contact-message" rows="10" cols="30" class="form-control">' . esc_textarea( $contact_message ) . '</textarea>' . "\n";
		if( isset($error_messages['contact-message']) ) {
			$html .= '<span class="help-block">' . $error_messages['contact-message'] . '</span>' . "\n";
		}
		$html .= '</div>' . "\n";

		if ( $question && $answer ) {
			$html .= '<div class="form-group">' . "\n";
			$html .= $question.'<br/>' . "\n";
			$html .= '<input placeholder="' . esc_html__( 'Your Answer', 'bookie-wp' ) . '" type="text" name="contact-quiz" id="contact-quiz" value="" class="form-control" />' . "\n";
			if( array_key_exists( 'contact-quiz', $error_messages ) ) {
				$html .= '<span class="contact-error">' . $error_messages['contact-quiz'] . '</span>' . "\n";
			}
			$html .= '</div>' . "\n";
		}
		
		if ( $sendcopy == 'yes' ) {
			$send_copy = '';
			if(isset($_POST['send-copy']) && $_POST['send-copy'] == true) {
				$send_copy = ' checked="checked"';
			}
			$html .= '<div class="checkbox">' . "\n";
			$html .= '<label for="send-copy"><input type="checkbox" name="send-copy" id="send-copy" value="true"' . $send_copy . ' />' . esc_html__( 'Send a copy of this email to you', 'bookie-wp' ) . '</label>' . "\n";
			$html .= '</div>' . "\n";
		}

		$checking = '';
		if(isset($_POST['checking'])) {
			$checking = $_POST['checking'];
		}

		$html .= '<p class="screen-reader-text" style="display:none;"><label for="checking" class="screen-reader-text">' . esc_html__( 'If you want to submit this form, do not enter anything in this field', 'bookie-wp' ) . '</label><input type="text" name="checking" id="checking" class="screen-reader-text" value="' . esc_attr( $checking ) . '" /></p>' . "\n";

		$html .= '<p class="buttons"><input type="hidden" name="submitted" id="submitted" value="true" /><input id="contactSubmit" class="btn btn-primary" type="submit" value="' . $button_text . '" /></p>';

		$html .= '</form>' . "\n";

		$html .= '</div>' . "\n";

	}
	
	return $html;
	
}

/**
 * Return formatted post meta.
 */
function toko_get_post_meta( $meta, $postid = '', $format = '' ) {
	if ( !$postid ) { 
		global $post;
		if ( null === $post ) 
			return false;
		else 
			$postid = $post->ID;
	}
	$meta_value = get_post_meta($postid, $meta, true);
	if ( !$meta_value ) 
		return false;
	$meta_value = wp_kses_stripslashes( wp_kses_decode_entities( $meta_value ) );
	if ( is_ssl() ) 
		$meta_value = str_replace("http://", "https://", $meta_value);
	if ( !$format ) 
		return $meta_value;
	else return str_replace("%meta%", $meta_value, $format);
}

/**
 * Echo formatted post meta.
 */
function toko_post_meta( $meta, $postid = '', $format = '' ) {
	echo toko_get_post_meta( $meta, $postid, $format );
}
