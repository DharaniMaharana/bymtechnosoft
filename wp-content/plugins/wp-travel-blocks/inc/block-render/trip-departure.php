<?php
/**
 * 
 * Render Callback For Trip Departure
 * 
 */

function wptravel_block_trip_departure_render( ) {

	$trip_id = get_the_ID();

	ob_start();
	?>
		<?php 
			if( !get_the_ID() ){ ?>
				<div class="wptravel-blocks travel-info trip-fixed-departure">
					<span class="value">
						<?php echo "January 1, 2023"; // @phpcs:ignore ?>
					</span>
				</div>
			<?php } else {
				if ( get_post()->post_type == 'itineraries' ) {
					$dates = wptravel_get_fixed_departure_date( $trip_id );
					if ( $dates ) { ?>
						<div class="wptravel-blocks travel-info trip-fixed-departure">
							<span class="value">
								<?php echo $dates; // @phpcs:ignore ?>
							</span>
						</div>
					<?php }
				} else { ?>
					<div class="wptravel-blocks travel-info trip-fixed-departure">
						<span class="value">
							<?php echo "January 1, 2023"; // @phpcs:ignore ?>
						</span>
					</div>
				<?php }
			}
		?>
	<?php
	$html = ob_get_clean();

	return $html;
}
