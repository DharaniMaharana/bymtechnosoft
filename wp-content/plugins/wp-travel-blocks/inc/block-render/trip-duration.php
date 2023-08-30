<?php
/**
 * 
 * Render Callback For Trip Duration
 * 
 */

function wptravel_block_trip_duration_render( $attributes ) {

	$trip_id = get_the_ID();

	$trip_duration       = get_post_meta( $trip_id, 'wp_travel_trip_duration', true );
	$trip_duration       = ( $trip_duration ) ? $trip_duration : 0;
	$trip_duration_night = get_post_meta( $trip_id, 'wp_travel_trip_duration_night', true );
	$trip_duration_night = ( $trip_duration_night ) ? $trip_duration_night : 0;

	$align = ! empty( $attributes['textAlign'] ) ? $attributes['textAlign'] : 'left';
	$duration_format = isset( $attributes['durationFormat'] ) ? $attributes['durationFormat'] : 'day-night';
	$days_placeholder_text = ! empty( $attributes['daysPlaceholderText'] ) ? $attributes['daysPlaceholderText'] : 'Days';
	$nights_placeholder_text = ! empty( $attributes['nightsPlaceholderText'] ) ? $attributes['nightsPlaceholderText'] : 'Nights';
	$hours_placeholder_text = ! empty( $attributes['hourPlaceholderText'] ) ? $attributes['hourPlaceholderText'] : 'Hours';
	$minutes_placeholder_text = ! empty( $attributes['minutePlaceholderText'] ) ? $attributes['minutePlaceholderText'] : 'Minutes';
	$class = sprintf( ' has-text-align-%s', $align );
	$extra_class = $attributes['extraClass'];

	// echo "<pre>"; print_r( $trip ); die;
	ob_start();
	
	if( !empty( $attributes['textColor'] )
	&& ( ( $duration_format == 'day_night' && ( isset( $trip['trip_duration']['days'] ) && ! empty( $trip['trip_duration']['days'] ) ) || ( isset( $trip['trip_duration']['nights'] ) && ! empty( $trip['trip_duration']['nights'] ) ) )
		|| ( $duration_format == 'hour_minute' && ( isset( $trip['trip_duration']['hours'] ) && ! empty( $trip['trip_duration']['hours'] ) ) || ( isset( $trip['trip_duration']['minutes'] ) && ! empty( $trip['trip_duration']['minutes'] ) ) ) ) ): ?>
		<style>
			.wptravel-block-<?php echo esc_attr( $extra_class ); ?> .dropbtn,
			.wptravel-block-<?php echo esc_attr( $extra_class ); ?>{
				<?php if( $attributes['textColor'] ): ?>
					color: <?php echo esc_attr( $attributes['textColor'] ); ?>!important;
				<?php endif; ?>
			}
			.wptravel-block-<?php echo esc_attr( $extra_class ); ?> .fixed-date-dropdown .dropbtn::after {
				color: <?php echo esc_attr( $attributes['textColor'] ); ?>!important;
			}
		</style>
	<?php
	endif;
	
	if( !get_the_ID() ){ ?>
		<div id="wptravel-block-trip-duration" class="wptravel-block-wrapper wptravel-block-trip-duration-date">
			<div class="travel-info trip-duration">
				<span class="value">
					<?php printf( __( '%1$s Day(s) %2$s Night(s)', 'wp-travel-blocks' ), 3, 2 ); ?>
				</span>
			</div>
		</div>
	<?php } else {
		if( get_post()->post_type == 'itineraries' ) {
			$duration_output = null;
			$trip = WpTravel_Helpers_Trips::get_trip( $trip_id )['trip'];
			if( $duration_format == 'day_night' ) {
				if( ( isset( $trip['trip_duration']['days'] ) || isset( $trip['trip_duration']['nights'] ) )
				&& ( ! empty( $trip['trip_duration']['days'] ) || ! empty( $trip['trip_duration']['nights'] ) ) ) {
					if( isset( $trip['trip_duration']['days'] ) && ! empty( $trip['trip_duration']['days'] ) && empty( $trip['trip_duration']['nights'] ) ) {
						$duration_output = $trip['trip_duration']['days'] . ' ' . $days_placeholder_text;
					} elseif( isset( $trip['trip_duration']['nights'] ) && ! empty( $trip['trip_duration']['nights'] ) && empty( $trip['trip_duration']['days'] ) ) {
						$duration_output = $trip['trip_duration']['nights'] . ' ' . $nights_placeholder_text;
					} elseif( ! empty( $trip['trip_duration']['nights'] ) && ! empty( $trip['trip_duration']['days'] ) ) {
						$duration_output = $trip['trip_duration']['days'] . ' ' . $days_placeholder_text . ' ' . $trip['trip_duration']['nights'] . ' ' . $nights_placeholder_text ;
					} ?>
					<div id="wptravel-block-trip-duration" class="wptravel-block-wrapper wptravel-block-<?php echo $extra_class; ?> wptravel-block-trip-duration-date <?php echo $class; ?>">
						<span class="value">
							<?php echo $duration_output; ?>
						</span>						
					</div>
				<?php }
			} elseif( $duration_format == 'hour_minute' ) {
				$trip = WpTravel_Helpers_Trips::get_trip( $trip_id )['trip'];
				if( ( isset( $trip['trip_duration']['hours'] ) || isset( $trip['trip_duration']['minutes'] ) )
				&& ( ! empty( $trip['trip_duration']['hours'] ) || ! empty( $trip['trip_duration']['minutes'] ) ) ) {
					if( isset( $trip['trip_duration']['hours'] ) && ! empty( $trip['trip_duration']['hours'] ) && empty( $trip['trip_duration']['minutes'] ) ) {
						$duration_output = $trip['trip_duration']['hours'] . ' ' . $hours_placeholder_text;
					} elseif( isset( $trip['trip_duration']['minutes'] ) && ! empty( $trip['trip_duration']['minutes'] ) && empty( $trip['trip_duration']['hours'] ) ) {
						$duration_output = $trip['trip_duration']['minutes'] . ' ' . $minutes_placeholder_text;
					} elseif( ! empty( $trip['trip_duration']['hours'] ) && ! empty( $trip['trip_duration']['minutes'] ) ) {
						$duration_output = $trip['trip_duration']['hours'] . ' ' . $hours_placeholder_text . ' ' . $trip['trip_duration']['minutes'] . ' ' . $minutes_placeholder_text ;
					} ?>
					<div id="wptravel-block-trip-duration" class="wptravel-block-wrapper wptravel-block-<?php echo $extra_class; ?> wptravel-block-trip-duration-date <?php echo $class; ?>">
						<span class="value">
							<?php echo $duration_output; ?>
						</span>
					</div>
				<?php }
			}
		} else { ?>
			<div class="travel-info trip-duration">
				<span class="value">
					<?php printf( __( '%1$s Day(s) %2$s Night(s)', 'wp-travel-blocks' ), 3, 2 ); ?>
				</span>
			</div>
		<?php }
	}
	
	$html = ob_get_clean();

	return $html;
}