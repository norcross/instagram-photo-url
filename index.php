<?php include( 'includes/process.php' ); ?>
<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase no-indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"><!--<![endif]-->
<head>
	<?php include( 'includes/html-head.php' ); ?>
</head>
<body lang="en">

	<?php
	include( 'includes/header.php' );

	// Show the fields or the reply, depending.
	if ( ! empty( $_GET['imgcheck'] ) ) {
		include( 'includes/results.php' );
	} else {
		include( 'includes/fields.php' );
	}

	include( 'includes/footer.php' );
	?>

</body>
</html>
