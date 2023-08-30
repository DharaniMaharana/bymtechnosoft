<?php
/**
 * 
 * Render Callback For Trip Outline
 * 
 */

function wptravel_block_trip_outline_render( $attributes ) {
	ob_start();
	$tab_data = wptravel_get_frontend_tabs();
	$content = is_array( $tab_data ) && isset( $tab_data['trip_outline'] ) && isset( $tab_data['trip_outline']['content'] ) ? $tab_data['trip_outline']['content'] : '';
	$align = ! empty( $attributes['textAlign'] ) ? $attributes['textAlign'] : 'left';
	// echo "<pre>";
	// var_dump(WpTravel_Helpers_Trips::get_trip(get_the_id())['trip']['itineraries']);
	echo '<div id="wptravel-block-trip-outline" class="wptravel-block-wrapper wptravel-block-trip-outline">'; //@phpcs:ignore
	echo wpautop( do_shortcode( $content ) ); // @phpcs:ignore
	?>
	<div id="faq" class="wptravel-block-wrapper wptravel-block-trip-faqs">
		<div class="panel-group" id="accordion">
			<?php
			$faqs = WpTravel_Helpers_Trips::get_trip(get_the_id())['trip']['itineraries'];
			if ( is_array( $faqs ) && count( $faqs ) > 0 ) {
				?>
				<div class="wp-collapse-open clearfix">
					<a href="#" class="open-all-itinerary-link"><span class="open-all" id="open-all"><?php esc_html_e( 'Open All', 'wp-travel-blocks' ); ?></span></a>
					<a href="#" class="close-all-itinerary-link" style="display:none;"><span class="close-all" id="close-all"><?php esc_html_e( 'Close All', 'wp-travel-blocks' ); ?></span></a>
				</div>
				<?php foreach ( $faqs as $k => $faq ) : ?>
					<div class="panel panel-default">
						<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-itinerary<?php echo esc_attr( $k + 1 ); ?>">
							<?php echo esc_html( $faq['label'] ) . ' : ' . esc_html( $faq['title'] ); ?>
							<span class="collapse-icon"></span>
							</a>
						</h4>
						</div>
						<div id="collapse-itinerary<?php echo esc_attr( $k + 1 ); ?>" class="panel-collapse collapse">
						<div class="panel-body">
							<p class="itinerary-meta">
								<?php if( isset( $faq['date'] ) && !empty( $faq['date'] ) ):  ?>
									<p><b><?php echo __( 'Date : ' ) .'</b>'. $faq['date']; ?></p>
								<?php endif; ?>
								<?php if( isset( $faq['time'] ) && !empty( $faq['time'] ) ):  ?>
									<p><b><?php echo __( 'Time : ' ) .'</b>'. $faq['time']; ?></p>
								<?php endif; ?>
							</p>
							<p><?php echo wp_kses_post( wpautop( $faq['desc'] ) ); ?></p>
							<?php if( isset( $faq['image'] ) && !empty( $faq['image'] ) ):  ?>
								<img src="<?php echo esc_url( wp_get_attachment_url($faq['image']) ); ?>">
							<?php endif; ?>
						</div>
						</div>
					</div>
				<?php
			endforeach;
		} ?>
		</div>
	</div>
	<?php
	echo '</div>'; //@phpcs:ignore
	echo '<style>';
	echo '.wptravel-block-trip-outline p{';
	echo 'text-align: '.$align.';';
	echo '}';
	echo '.wptravel-block-trip-outline .tc-content p{';
	echo 'text-align: left;';
	echo '}';
	echo '</style>';

	return ob_get_clean();
}
