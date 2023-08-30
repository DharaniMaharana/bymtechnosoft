<?php

/**
 * 
 * Render Callback For Trip List
 * 
 */
function wptravel_block_get_trip_lists( $attributes ){
	$query_args = isset( $attributes['query'] ) ? $attributes['query'] : array();

	// Legacy Block Compatibility & fixed conflict with yoast.
	if ( isset( $attributes['location'] ) ) {
		$filter_term = get_term( $attributes['location'], 'travel_locations' );
		if ( is_object( $filter_term ) && isset( $filter_term->term_id ) ) {
			$selected_term                          = array(
				'count'       => $filter_term->count,
				'id'          => $filter_term->term_id,
				'description' => $filter_term->description,
				'taxonomy'    => $filter_term->taxonomy,
				'name'        => $filter_term->name,
				'slug'        => $filter_term->slug,
			);
			$query_args['selectedTripDestinations'] = array( $selected_term );
		}
	}
	if ( isset( $attributes['tripType'] ) ) {
		$filter_term = get_term( $attributes['tripType'], 'itinerary_types' );
		if ( is_object( $filter_term ) && isset( $filter_term->term_id ) ) {
			$selected_term                   = array(
				'count'       => $filter_term->count,
				'id'          => $filter_term->term_id,
				'description' => $filter_term->description,
				'taxonomy'    => $filter_term->taxonomy,
				'name'        => $filter_term->name,
				'slug'        => $filter_term->slug,
			);
			$query_args['selectedTripTypes'] = array( $selected_term );
		}
	}

	// Options / Attributes.
	$numberposts = 3;

	$args = array(
		'post_type'    => WP_TRAVEL_POST_TYPE,
		'posts_per_page'  => $numberposts
	);

	if ( isset( $query_args['orderBy'] ) ) {
		switch ( $query_args['orderBy'] ) {
			case 'title':
				$args['orderby'] = 'post_title';
				break;
			case 'date':
				$args['orderby'] = 'post_date';
				break;
		}
		$args['order'] = $query_args['order'];
	}
	if ( isset( $query_args['selectedTripTypes'] ) && ! empty( $query_args['selectedTripTypes'] ) ) {
		$args['itinerary_types'] = wp_list_pluck( $query_args['selectedTripTypes'], 'slug' );
	}
	if ( isset( $query_args['selectedTripDestinations'] ) && ! empty( $query_args['selectedTripDestinations'] ) ) {
		$args['travel_locations'] = wp_list_pluck( $query_args['selectedTripDestinations'], 'slug' );
	}

	if ( isset( $query_args['selectedTripActivities'] ) && ! empty( $query_args['selectedTripActivities'] ) ) {
		$args['activity'] = wp_list_pluck( $query_args['selectedTripActivities'], 'slug' );
	}

	if ( isset( $query_args['selectedTripKeywords'] ) && ! empty( $query_args['selectedTripKeywords'] ) ) {
		$args['travel_keywords'] = wp_list_pluck( $query_args['selectedTripKeywords'], 'slug' );
	}

	// Meta Query.
	$sale_trip     = isset( $attributes['saleTrip'] ) ? $attributes['saleTrip'] : false;
	$featured_trip = isset( $attributes['featuredTrip'] ) ? $attributes['featuredTrip'] : false;
	if ( $sale_trip ) {
		$args['sale_trip'] = $sale_trip;
	}
	if ( $featured_trip ) {
		$args['featured_trip'] = $featured_trip;
	}
	$trip_data = WpTravel_Helpers_Trips::filter_trips( $args );

	if ( is_array( $trip_data ) && isset( $trip_data['code'] ) && 'WP_TRAVEL_FILTER_RESULTS' === $trip_data['code'] ) {
		$trips          = $trip_data['trip'];
		$trip_ids       = wp_list_pluck( $trips, 'id' );

		if( $sale_trip || $featured_trip ){
			$args  = array(
				'post_type' => WP_TRAVEL_POST_TYPE,
				'post__in'  => $trip_ids,
				'posts_per_page'  => $numberposts
			);
		}

		$query = new WP_Query( $args );	
		$block_content = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();
				$trip_data['id'] = get_the_id();
				$trip_data['title'] = get_the_title();
				$trip_data['image'] = get_the_post_thumbnail_url();
				$trip_data['excerpt'] = get_the_excerpt();
				$trip_data['url'] = get_the_permalink();

				array_push($block_content, $trip_data );
			endwhile;
			wp_reset_postdata();
		}
		return $block_content;
	}
	return "";
	
}

function wptravel_block_trip_slider_render( $attributes ){

	$sliderHeight     = isset( $attributes['sliderHeight'] ) ? $attributes['sliderHeight'] : 400;
	$block_content = wptravel_block_get_trip_lists( $attributes );

	$strings = WpTravel_Helpers_Strings::get();

	ob_start();

	?>
		<div id="wptravel-block-trip-slider" class="wptravel-block-trip-slider">			
			<div class="wp-travel-trip-slider">
				<?php foreach( $block_content as $content ){ ?>
					<div class="item">
						<div class="overlay"></div>
						<img src="<?php echo esc_url($content['image']) ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
						<div class="wp-travel-entry-content">
							<div class="trip-title">
								<h2><?php echo $content['title'] ?></h2>
							</div>
							<div class="trip-price">
								<span class="price-from">
									<?php echo esc_html( $strings['from'] ); ?>
								</span>
								<span class="person-count">
									<ins>
										<span><?php echo wptravel_get_formated_price_currency( $content['id'] ); ?></span>
									</ins>
								</span>
							</div>
							<div class="trip-excerpt">
								<p><?php echo $content['excerpt'] ?></p>
							</div>
							<div class="read-more">
								<a href="" class="btn">Book Now</a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>				
		</div>
		<style>
			@media only screen and (min-width: 993px){
				#wptravel-block-trip-slider .wp-travel-trip-slider .item img {
					max-height: <?php echo esc_attr($sliderHeight); ?>px;
				}
			}
		</style>
		<script>
			jQuery(document).ready(function(n) {
				n('#wptravel-block-trip-slider .wp-travel-trip-slider').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: true,
					autoplay: false,
					dots: true,
					infinite: true,
				});
			});
		</script>
	<?php

	$html = ob_get_clean();

	return $html;
}

add_action( 'rest_api_init', 'wp_travel_slider_trips' );

function wp_travel_slider_trips(){
	register_rest_route(
		'wptravel/v1',
		'/get-slider-trips',
		array(
			'methods'             => 'get',
			'permission_callback' => '__return_true',
			'callback'            => 'wp_travel_get_slider_trips',
		)
	);
}

function wp_travel_get_slider_trips( WP_REST_Request $request ){
	$slider_param = array();

	$get_slider_param = ( array )json_decode( $request->get_param( 'param' ) );

	foreach( ( ( array )json_decode( $request->get_param( 'param' ) )->query ) as $key => $data ){
		$slider_param['query'][$key] = $data;
	}
	$slider_param['saleTrip'] = $get_slider_param['saleTrip'];
	$slider_param['featuredTrip'] = $get_slider_param['featuredTrip'];
	$slider_param['sliderHeight'] = $get_slider_param['sliderHeight'];
	$slider_param['sliderAutoplay'] = $get_slider_param['sliderAutoplay'];
	$slider_param['sliderArrow'] = $get_slider_param['sliderArrow'];
	$slider_param['sliderDots'] = $get_slider_param['sliderDots'];
	

	return ( wptravel_block_get_trip_lists( $slider_param ) );
}