<?php

/**
 * Figure out the URL I am on.
 *
 * @return string
 */
function metafetch_page_url() {

	// Set my base URL.
	$base   = 'http' . ( ( $_SERVER['SERVER_PORT'] == 443 ) ? 's://' : '://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Now parse it.
	$parse  = parse_url( $base );

	// Set the build URL.
	$build  = $parse['scheme'] . '://' . $parse['host'];

	// Check for a path (subfolder) and append it before returning.
	return ! empty( $parse['path'] ) ? $build . $parse['path'] : $build;
}

/**
 * Handle a redirect for result display.
 *
 * @param  boolean $success   Whether or not the result was a success.
 * @param  string  $error     The error code.
 * @param  string  $imageurl  The image URL (which is only for successful requests).
 * @param  integer $status    The redirect status code.
 *
 * @return void
 */
function metafetch_location_redirect( $success = false, $error = '', $imageurl = '', $status = 302 ) {

	// Set my base URL.
	$link   = metafetch_page_url() . '?imgcheck=1';

	// Build my error redirect link.
	if ( empty( $success ) ) {

		// Check for the error code.
		$error  = ! empty( $error ) ? $error : 'unknown';

		// And make the link.
		$link  .= '&success=0&error=' . trim( $error );
	}

	// Build my success redirect link.
	if ( ! empty( $success ) && ! empty( $imageurl ) ) {
		$link  .= '&success=1&imageurl=' . urlencode( $imageurl );
	}

	// And do the redirect.
	header( 'Location: ' . $link, true, $status );
	die();
}

/**
 * Determine if I'm on a local environment and load CSS.
 *
 * @return string
 */
function metafetch_css_file() {

	// Fetch my site URL.
	$site = metafetch_page_url();

	// Set my file.
	$file   = ! empty( $site ) && IGURL_HOST === parse_url( $site, PHP_URL_HOST ) ? 'style.min.css' : 'style.css';

	// And echo it out.
	echo '<link type="text/css" rel="stylesheet" media="screen" href="css/' . $file . '?ver=' . IGURL_VERS . '">' . "\n";
}

/**
 * Display array results in a readable fashion.
 *
 * @param  mixed   $display  The output we want to display.
 * @param  boolean $die      Whether or not to die as soon as output is generated.
 * @param  boolean $return   Whether to return the output or show it.
 *
 * @return mixed             Our printed (or returned) output.
 */
function metafetch_preprint( $display, $die = false, $return = false ) {

	// Set an empty.
	$code   = '';

	// Add some CSS to make it a bit more readable.
	$style  = 'background-color: #fff; color: #000; font-size: 16px; line-height: 22px; padding: 5px; white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;';

	// Generate the actual output.
	$code  .= '<pre style="' . $style . '">';
	$code  .= print_r( $display, 1 );
	$code  .= '</pre>';

	// Return if requested.
	if ( $return ) {
		return $code;
	}

	// Print if requested (the default).
	if ( ! $return ) {
		print $code;
	}

	// Die if you want to die.
	if ( $die ) {
		die();
	}
}

