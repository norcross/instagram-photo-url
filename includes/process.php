<?php

// Set our site version and URL.
define( 'IGURL_VERS', '0.0.2' );
define( 'IGURL_HOST', 'ig.norcrossadmin.com' );

// Load our helper file.
require_once 'helper.php';

// Actually load our request functions.
metafetch_check_request_types();

/**
 * Our loader-type function to look for various request types.
 *
 * @return void
 */
function metafetch_check_request_types() {

	// Set my base URL.
	$base   = metafetch_page_url() . '?imgcheck=1';

	// If we have our URL flag, run the POST checks.
	if ( ! empty( $_POST['igurl-flag'] ) ) {
		metafetch_check_post_request( $base );
	}

	// If we have our image ID or URL, run the GET checks.
	if ( ! empty( $_GET['image-id'] ) || ! empty( $_GET['image-url'] ) ) {

		// Check each variable.
		$image_id   = ! empty( $_GET['image-id'] ) ? $_GET['image-id'] : '';
		$image_url  = ! empty( $_GET['image-url'] ) ? $_GET['image-url'] : '';

		// Pass it to our request check.
		metafetch_check_get_request( $image_id, $image_url, $base );
	}
}

/**
 * Look for the post request.
 *
 * @param  string  $base  Our base URL to handle redirects.
 *
 * @return mixed
 */
function metafetch_check_post_request( $base = '' ) {

	// Redirect without a URL.
	if ( empty( $_POST['image-url'] ) ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=nolink';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// Get our link and sanitize it.
	$link   = filter_var( $link, FILTER_SANITIZE_URL );

	// Now handle the request.
	metafetch_process_request( $link, $base );

	// And finish up.
	return;
}

/**
 * Look for the get request.
 *
 * @param  string  $image_id  The ID for the image URL.
 * @param  string  $image_url The entire image URL.
 * @param  string  $base      Our base URL to handle redirects.
 *
 * @return mixed
 */
function metafetch_check_get_request( $image_id = '', $image_url = '', $base = '' ) {

	// Bail without our data.
	if ( empty( $image_id ) && empty( $image_url ) ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=noinfo';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// Set my link based on what we have.
	$link   = ! empty( $image_url ) ? $image_url : 'https://www.instagram.com/p/' . $image_id . '/';

	// Get our link and sanitize it.
	$link   = filter_var( $link, FILTER_SANITIZE_URL );

	// Now handle the request.
	metafetch_process_request( $link, $base );

	// And finish up.
	return;
}

/**
 * Process the request with a given URL.
 *
 * @param  string  $link  The URL to get data from.
 * @param  string  $base  Our base URL to handle redirects.
 *
 * @return void
 */
function metafetch_process_request( $link = '', $base = '' ) {

	// Make sure the link itself is valid.
	if ( filter_var( $link, FILTER_VALIDATE_URL ) === false ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=badlink';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// Make sure we have an actual instagram URL.
	if ( strpos( $link, 'instagram.com' ) === false ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=wronglink';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// Now attempt to get our data.
	if ( false === $data = metafetch_get_tag_values( $link ) ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=nodata';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// If we don't have an actual image URL.
	if ( empty( $data ) || empty( $data['og:image'] ) ) {

		// Create my URL.
		$redir  = $base . '&success=0&error=noimage';

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// We have an image. Do that.
	if ( ! empty( $data['og:image'] ) ) {

		// Create my URL.
		$redir  = $base . '&success=1&imageurl=' . urlencode( $data['og:image'] );

		// And do the redirect.
		header( 'Location: ' . $redir, true, 302 );
		die();
	}

	// Create my URL.
	$redir  = $base . '&success=0&error=unknown';

	// And do the redirect.
	header( 'Location: ' . $redir, true, 302 );
	die();
}

/**
 * Look for the post request.
 *
 * @return mixed
 */
function metafetch_display_request_response() {

	// Bail without our flag.
	if ( empty( $_GET['imgcheck'] ) ) {
		return;
	}

	// Set my empty text.
	$text   = '';

	// First handle the errors.
	if ( empty( $_GET['success'] ) ) {

		// Get my error code.
		$error  = ! empty( $_GET['error'] ) ? $_GET['error'] : 'unknown';

		// Now handle my different error types.
		switch ( $error ) {

			case 'noinfo' :
				$text   = 'Please provide a valid URL or image ID.';
				break;

			case 'nolink' :
				$text   = 'Please enter a URL.';
				break;

			case 'badlink' :
				$text   = 'Please enter a valid URL.';
				break;

			case 'wronglink' :
				$text   = 'Please enter a valid Instagram URL.';
				break;

			case 'nodata' :
				$text   = 'No data could be retrieved for that URL.';
				break;

			case 'noimage' :
				$text   = 'No image data could be retrieved for that URL.';
				break;

			default:
				$text   = 'There was an unknown error. Please try again later.';
		}

		// Handle the message.
		echo '<p class="message message-error">' . $text . '</p>';
	}

	// Handle the success.
	if ( ! empty( $_GET['success'] ) ) {

		// Fetch my image.
		$image  = urldecode( $_GET['imageurl'] );

		// Show the "click here" link.
		echo '<p class="image-link">Raw Image URL: <a target="_blank" href="' . $image . '">Click Here</a></p>';

		// Show the actual image.
		echo '<p class="image-wrap"><img src="' . $image . '"></p>';
	}

	// Add my "return home" link.
	echo '<p class="return-home"><a href="' . metafetch_page_url() . '">Return Home</a></p>';

	// And be done.
	return;
}

/**
 * Get the contents of the URL.
 *
 * @param  string  $link       The URL to get data from.
 * @param  integer $maxdirect  Total allowed redirects.
 * @param  integer $curdirect  Current count of redirects.
 *
 * @return array
 */
function metafetch_get_url_contents( $link = '', $maxdirect = null, $curdirect = 0 ) {

	// Bail without a URL.
	if ( empty( $link ) ) {
		return false;
	}

	// Set my result to false.
	$result = false;

	// Get my items.
	$items  = @file_get_contents( $link );

	// Assuming we have items, and it's a string, start the work.
	if ( isset( $items ) && is_string( $items ) ) {

		// Do all our preg matching
		preg_match_all( '/<[\s]*meta[\s]*http-equiv="?REFRESH"?' . '[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?' . '[\s]*[\/]?[\s]*>/si', $items, $match );

		// If we have all our things, do it.
		if ( isset( $match ) && is_array( $match ) && count( $match ) === 2 && count( $match[1] ) === 1 ) {

			// Check our redirect setups.
			if ( ! isset( $maxdirect ) || $curdirect < $maxdirect ) {
				return metafetch_get_url_contents( $match[1][0], $maxdirect, ++$curdirect );
			}

			// Set the result as false.
			$result = false;
		} else {
			$result = $items;
		}
	}

	// Some general cleanup.
	$result = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $result );
	$result = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $result );
	$result = preg_replace( '#<body(.*?)>(.*?)</body>#is', '', $result );
	$result = preg_replace( '/^\s+|\s+$/m', '', $result );

	// Return our items.
	return $result;
}

/**
 * Get the various data from passing a URL.
 *
 * @param  string  $link  The URL we want to get our data from.
 *
 * @return array
 */
function metafetch_get_url_data( $link = '' ) {

	// Bail without a URL.
	if ( empty( $link ) ) {
		return false;
	}

	// Set my result to false.
	$result = false;

	// Get my page markup.
	$markup = metafetch_get_url_contents( $link );

	// Assuming we have markup, and it's a string, start the work.
	if ( isset( $markup ) && is_string( $markup ) ) {

		// Set my variables.
		$title  = null;
		$links  = null;
		$mtags  = null;
		$mprops = null;

		// Run our preg match for a title.
		preg_match( '/<title>([^>]*)<\/title>/si', $markup, $match );

		// If we have a match, set our title.
		if ( isset( $match ) && is_array( $match ) && count( $match ) > 0 ) {
			$title  = strip_tags( $match[1] );
		}

		// Now run our match all for meta tags.
		preg_match_all( '/<[\s]*meta[\s]*(name|property)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $markup, $match );

		// We have them. Go forth.
		if ( isset( $match ) && is_array( $match ) && count( $match ) === 4 ) {

			// Parse out each portion.
			$orgs   = $match[0];
			$types  = $match[1];
			$names  = $match[2];
			$values = $match[3];

			// We have some stuff to filter through.
			if ( count( $orgs ) === count( $names ) && count( $names ) === count( $values ) ) {

				// Set our variables here.
				$mtags  = array();
				$mprops = $mtags;

				// Set our flag.
				$flags  = version_compare( PHP_VERSION, '5.4.0' ) == -1 ? ENT_COMPAT : ENT_COMPAT | ENT_HTML401;

				// Now loop my tag items.
				for ( $i = 0, $limit = count( $names ); $i < $limit; $i++ ) {

					// Set my meta type.
					$mtype  = 'name' === $types[ $i ] ? 'mtags' : 'mprops';
					$nmkey  = $names[ $i ];

					// Get my two values
					$dhtml  = htmlentities( $orgs[ $i ], $flags, 'UTF-8' );
					$dvalue = $values[ $i ];

					// Set my array.
					${ $mtype }[ $nmkey ] = array (
						'html'  => trim( $dhtml ),
						'value' => trim( $dvalue ),
					);
				}
			}
		}

		// Set my result array.
		$result = array (
			'title'       => trim( $title ),
			'tags'        => $mtags,
			'properties'  => $mprops,
		);
	}

	// Return my result array.
	return $result;
}

/**
 * Get just the tag values from a URL.
 *
 * @param  string $link   The URL we're fetching from.
 * @param  string $group  Which set of tags we want to get.
 *
 * @return array
 */
function metafetch_get_tag_values( $link = '', $group = 'properties' ) {

	// Bail without a URL.
	if ( empty( $link ) ) {
		return false;
	}

	// Get my URL data.
	if ( false === $data = metafetch_get_url_data( $link ) ) {
		return false;
	}

	// Make my blank setup.
	$setup  = array();

	// Now loop my array and parse out each item.
	foreach ( $data as $type => $items ) {

		// If it's just a string, do this.
		if ( ! is_array( $items ) ) {
			$setup[ $type ] = $items;
		}

		// If it's an array, do this.
		if ( is_array( $items ) ) {

			// Set an empty for the values.
			$values = array();

			// Loop through the second array.
			foreach ( $items as $name => $tags ) {
				$values[ $name ] = $tags['value'];
			}

			// And add the values to the setup array.
			$setup[ $type ] = $values;
		}
	}

	// Bail without any sort of setup.
	if ( empty( $setup ) ) {
		return false;
	}

	// Handle my key based return.
	if ( ! empty( $group ) ) {

		// If the key doesn't actually exist, just go false.
		if ( ! isset( $setup[ $group ] ) ) {
			return false;
		}

		// Now sort it alphabetical by keys.
		ksort( $setup[ $group ], SORT_STRING );

		// And return the single section.
		return $setup[ $group ];
	}

	// Return my entire setup.
	return $setup;
}
