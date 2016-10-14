<?php
/**
 * Grab Post Formats media
 *
 * @package WordPress
 * @subpackage Toko
 */

/**
 * Grab Image at the Image post format
 * Credits: Justin Tadlock <justin@justintadlock.com>
 */
function toko_grab_image( $more_link_text = null, $strip_teaser = false) {
	$output = array(
		'image' => '',
		'content' => get_the_content( $more_link_text, $strip_teaser ),
	);

	/* Finds matches for shortcodes in the content. */
	preg_match_all( '/' . get_shortcode_regex() . '/s', $output['content'], $matches, PREG_SET_ORDER );

	if ( !empty( $matches ) ) {
		foreach ( $matches as $shortcode ) {
			if ( in_array( $shortcode[2], array( 'caption', 'wp_caption' ) ) ) {
				$output['image'] = $shortcode[0];
			}
		}
	}

	/* Pull a raw HTML image + link if it exists. */
	if ( !$output['image'] && preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)#is', $output['content'], $matches ) )
		$output['image'] = $matches[0];

	if ( $output['image'] ) {
		$output['content'] = str_replace( $output['image'], '', $output['content'] );
		$output['image'] = do_shortcode( $output['image'] );
	}

	$output['content'] = apply_filters( 'the_content', $output['content'] );
	$output['content'] = str_replace( ']]>', ']]&gt;', $output['content'] );

	return $output;
}

/**
 * Grab Gallery at the Gallery post format
 */
function toko_grab_gallery( $more_link_text = null, $strip_teaser = false) {
	$output = array(
		'gallery' => '',
		'content' => get_the_content( $more_link_text, $strip_teaser ),
	);

	/* Finds matches for shortcodes in the content. */
	preg_match_all( '/' . get_shortcode_regex() . '/s', $output['content'], $matches, PREG_SET_ORDER );

	if ( !empty( $matches ) ) {
		foreach ( $matches as $shortcode ) {
			if ( in_array( $shortcode[2], array( 'gallery' ) ) ) {
				$output['gallery'] = $shortcode[0];
			}
		}
	}

	if ( $output['gallery'] ) {
		$output['content'] = str_replace( $output['gallery'], '', $output['content'] );
		$output['gallery'] = do_shortcode( $output['gallery'] );
	}

	$output['content'] = apply_filters( 'the_content', $output['content'] );
	$output['content'] = str_replace( ']]>', ']]&gt;', $output['content'] );

	return $output;
}

/*
 * Wrapper for Hybrid Media Grabber
 */
function toko_media_grabber( $args = array() ) {

	return hybrid_media_grabber( $args );
}

/**
 * Grab Chat at the Chat post format
 */
function toko_grab_chat( $more_link_text = null, $strip_teaser = false) {
	$content = get_the_content( $more_link_text, $strip_teaser );

	// add_filter( 'hybrid_post_format_chat_text', 'wpautop' );
	$content = hybrid_chat_content( $content );

	$content = apply_filters( 'the_content', $content );
	$content = str_replace( '</p></div>', '</div>', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	return $content;
}

/**
 * This function filters the post content when viewing a post with the "chat" post format.  It formats 
 * the content with structured HTML markup to make it easy for theme developers to style chat posts. 
 * The advantage of this solution is that it allows for more than two speakers (like most solutions). 
 * You can have 100s of speakers in your chat post, each with their own, unique classes for styling.
 *
 * @author    David Chandra <david.warna@gmail.com>
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012
 * @link      http://justintadlock.com/archives/2012/08/21/post-formats-chat
 */
function hybrid_chat_content( $content ) {

	/* Open the chat transcript div and give it a unique ID based on the post ID. */
	$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'hybrid_post_format_chat_separator', ':' );

	/* Get the stanzas from the post content. */
	$stanzas = hybrid_get_the_post_format_chat( $content );

	/* Loop through the stanzas that were returned. */
	foreach ( $stanzas as $stanza ) {

		/* Loop through each row of the stanza and format. */
		foreach ( $stanza as $row ) {

			/* Get the chat author and message. */
			$chat_author = !empty( $row['author'] ) ? $row['author'] : '';
			$chat_text   = $row['message'];

			/* Get the speaker/row ID. */
			$speaker_id = hybrid_chat_row_id( $chat_author );

			/* Format the time if there was one given. */
			$time = empty( $row['time'] ) ? '' : '<time class="chat-timestamp">' . esc_html( $row['time'] ) . '</time> ';

			/* Open the chat row. */
			$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';

			/* Add the chat row author. */
			if ( !empty( $chat_author ) )
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard">' . $time . '<cite class="fn">' . apply_filters( 'hybrid_post_format_chat_author', $chat_author, $speaker_id ) . '</cite><span>:</span></div>';

			/* Add the chat row text. */
			$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text">' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'hybrid_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</div>';

			/* Close the chat row. */
			$chat_output .= "\n\t\t\t\t" . '</div>';
		}
	}

	/* Close the chat transcript div. */
	$chat_output .= "\n\t\t\t</div>\n";

	/* Return the chat content. */
	return $chat_output;
}

/**
 * Separates the post content into an array of arrays for further formatting of the chat content.
 */
function hybrid_get_the_post_format_chat( $content ) {

	/* Allow the separator (separator for speaker/text) to be filtered. */
	$separator = apply_filters( 'hybrid_post_format_chat_separator', ':' );

	/* Split the content to get individual chat rows. */
	$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );

	/* Loop through each row and format the output. */
	foreach ( $chat_rows as $chat_row ) {

		/* Set up a new, empty array of this stanza. */
		$stanza = array();

		/* If a speaker is found, create a new chat row with speaker and text. */
		if ( preg_match( '/(?<!http|https)' . $separator . '/', $chat_row ) ) {

			/* Set up a new, empty array for this row. */
			$row = array();

			/* Split the chat row into author/text. */
			$chat_row_split = explode( $separator, trim( $chat_row ), 2 );

			/* Get the chat author and strip tags. */
			$row['author'] = strip_tags( trim( $chat_row_split[0] ) );

			/* Get the chat text. */
			$row['message'] = trim( $chat_row_split[1] );

			/* Add the row to the stanza. */
			$stanza[] = $row;
		}

		/* If no speaker is found. */
		else {

			/* Make sure we have text. */
			if ( !empty( $chat_row ) ) {
				$stanza[] = array( 'message' => $chat_row );
			}
		}

		$stanzas[] = $stanza;
	}

	return $stanzas;
}

/**
 * This function returns an ID based on the provided chat author name.  It keeps these IDs in a global 
 * array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
 * that will be used in an HTML class for individual chat rows so they can be styled.  So, speaker "John" 
 * will always have the same class each time he speaks.  And, speaker "Mary" will have a different class 
 * from "John" but will have the same class each time she speaks.
 *
 * @author    David Chandra <david.warna@gmail.com>
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012
 * @link      http://justintadlock.com/archives/2012/08/21/post-formats-chat
 */
function hybrid_chat_row_id( $chat_author ) {
	global $_hybrid_post_chat_ids;

	/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
	$chat_author = strtolower( strip_tags( $chat_author ) );

	/* Add the chat author to the array. */
	$_hybrid_post_chat_ids[] = $chat_author;

	/* Make sure the array only holds unique values. */
	$_hybrid_post_chat_ids = array_unique( $_hybrid_post_chat_ids );

	/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
	return absint( array_search( $chat_author, $_hybrid_post_chat_ids ) ) + 1;
}

