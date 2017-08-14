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

	// Now build it and return it.
	return $parse['scheme'] . '://' . $parse['host'] . '/';
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

