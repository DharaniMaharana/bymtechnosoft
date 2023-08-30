<?php
/**
 * 
 * Render Callback For Trip Gallery
 * 
 */

function wptravel_block_trip_gallery_render( $attributes ) {
	ob_start();
	$tab_data = wptravel_get_frontend_tabs();

	$content = is_array( $tab_data ) && isset( $tab_data['gallery'] ) && isset( $tab_data['gallery']['content'] ) ? $tab_data['gallery']['content'] : '';
	
	$galleryDesign     = isset( $attributes['galleryDesign'] ) ? $attributes['galleryDesign'] : 'gridStyle';
	$sliderHeight     = isset( $attributes['sliderHeight'] ) ? $attributes['sliderHeight'] : 400;
	$sliderAutoplay     = isset( $attributes['sliderAutoplay'] ) ? $attributes['sliderAutoplay'] : false;
	$sliderArrow     = isset( $attributes['sliderArrow'] ) ? $attributes['sliderArrow'] : false;
	$sliderDots     = isset( $attributes['sliderDots'] ) ? $attributes['sliderDots'] : false;

	if( $galleryDesign == 'gridStyle' ){
		echo '<div id="wptravel-block-trip-gallery" class="wptravel-block-wrapper wptravel-block-trip-gallery">'; //@phpcs:ignore
		echo wpautop( do_shortcode( $content ) ); // @phpcs:ignore
		echo '</div>'; //@phpcs:ignore
	}else{
		$gallery_image = array();

		if( class_exists( 'WP_Travel_Pro' ) ){
			
			if( count( get_post_meta( get_the_id(), 'wp_travel_advanced_gallery' ) ) > 0 ){
				foreach(  get_post_meta( get_the_id(), 'wp_travel_advanced_gallery' )[0]['items'] as $data ){
					array_push( $gallery_image, $data['id'] );
				}
			}else{
				$gallery_image = get_post_meta( get_the_id(), 'wp_travel_itinerary_gallery_ids' )[0];
			}
		}else{
			$gallery_image = get_post_meta( get_the_id(), 'wp_travel_itinerary_gallery_ids' )[0];
		}
	?>
		<div id="wptravel-block-trip-gallery" class="wptravel-block-wrapper wptravel-block-trip-gallery slider">
			<div class="wp-travel-advanced-gallery-items-list">
				<?php foreach( $gallery_image as $image ): ?>
					<div class="item image">
						
							<span class="wptag__media-card">
								<span class="wptag__thumbnail">
									<img decoding="async" alt="" src="<?php echo esc_url( wp_get_attachment_image_url($image, 'full') ); ?>">
								</span>
							</span>
			
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<style>
			@media only screen and (min-width: 993px){
				.wptravel-block-trip-gallery .wp-travel-advanced-gallery-items-list.slick-slider .wptag__thumbnail img {
					max-height: <?php echo esc_attr($sliderHeight); ?>px;
				}
			}
		</style>
		<script>
			jQuery(document).ready(function(n) {
				n('.slider .wp-travel-advanced-gallery-items-list').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					<?php 
						if( $sliderAutoplay ){
						?>
						autoplay: true,
						<?php
						}else{
						?>
						autoplay: false,
						<?php
						}
					?>
					<?php 
						if( $sliderArrow ){
						?>
						arrows: true,
						<?php
						}else{
						?>
						arrows: false,
						<?php
						}
					?>
					<?php 
						if( $sliderDots ){
						?>
						dots: true,
						<?php
						}else{
						?>
						dots: false,
						<?php
						}
					?>
					infinite: true,
				});
			});
		</script>
	<?php
	}
	return ob_get_clean();
}


add_action( 'rest_api_init', 'wp_travel_blocks_pro_get_trips_meta' );

function wp_travel_blocks_pro_get_trips_meta(){
	register_rest_route(
		'wp-travel-block/v1',
		'/get-trip-gallery',
		array(
			'methods'             => 'get',
			'permission_callback' => '__return_true',
			'callback'            => 'wp_travel_blocks_pro_trip_gallery',
		)
	);
}

function wp_travel_blocks_pro_trip_gallery( WP_REST_Request $request ){

	$gallery_images = array();
	foreach( get_post_meta( $request->get_param('id'), 'wp_travel_itinerary_gallery_ids' )[0] as $image ){
		$gallery_image['url'] = wp_get_attachment_image_url($image, 'full', true);
		array_push( $gallery_images, $gallery_image );
	}
	return $gallery_images;
}



