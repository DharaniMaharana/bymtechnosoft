<?php
/**
 * 
 * Render Callback For Trip Code
 * 
 */

function wptravel_block_trip_button_render( $attributes, $content, $block ) {
	
	ob_start();
    ?>
	
	<a id="wptravel-block-trip-button" href="<?php echo esc_url( get_the_permalink( get_the_id() ) ); ?>">
		<?php echo esc_html( 'Book Now', 'wp-travel-blocks' ); ?>
    </a>
	<?php
	return ob_get_clean();
}
