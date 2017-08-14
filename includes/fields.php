<section class="site-section upload-section">

	<h3>Enter the URL of the Instagram post</h3>

	<form class="" method="post" action="<?php echo metafetch_page_url(); ?>" autocomplete="off">

		<p class="field-input-wrap">
			<input class="link-input" type="url" name="image-url" value="" autocomplete="off" />
		</p>

		<p class="field-input-wrap submit-button">
			<button class="button" type="submit">Get My Image</button>
		</p>

		<input type="hidden" name="igurl-flag" value="1">

	</form>

</section>