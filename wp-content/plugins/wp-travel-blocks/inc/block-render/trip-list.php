<?php

/**
 * 
 * Render Callback For Trip List
 * 
 */
function wptravel_block_trip_list_render( $attributes, $content ){
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
	$numberposts = isset( $query_args['numberOfItems'] ) && $query_args['numberOfItems'] ? $query_args['numberOfItems'] : 3;

	$layout_type = isset( $attributes['layoutType'] ) ? $attributes['layoutType'] : 'default-layout' ;
	$card_layout = isset( $attributes['cardLayout'] ) ? $attributes['cardLayout'] : 'grid-view' ;

	$args = array(
		'post_type'    => WP_TRAVEL_POST_TYPE,
		'post__not_in' => array( get_the_ID() ),
	);

	if($attributes['relatedTrip']){
		$args['posts_per_page'] = -1;
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

		// if( get_the_terms( get_the_id(), 'travel_locations' ) ){
		// 	$args['travel_locations'] = array();
		// 	$i = 0;
		// 	foreach( get_the_terms( get_the_id(), 'travel_locations' ) as $data ){
		// 		$args['travel_locations'][$i] = $data->slug;
		// 		$i++;
		// 	}
		// }

		if( get_the_terms( get_the_id(), 'itinerary_types' ) ){
			$args['itinerary_types'] = array();
			$i = 0;
			foreach( get_the_terms( get_the_id(), 'itinerary_types' ) as $data ){
				$args['itinerary_types'][$i] = $data->slug;
				$i++;
			}
		}

		// if( get_the_terms( get_the_id(), 'travel_keywords' ) ){
		// 	$args['travel_keywords'] = array();
		// 	$i = 0;
		// 	foreach( get_the_terms( get_the_id(), 'travel_keywords' ) as $data ){
		// 		$args['travel_keywords'][$i] = $data->slug;
		// 		$i++;
		// 	}
		// }
		// if( get_the_terms( get_the_id(), 'activity' ) ){
		// 	$args['activity'] = array();
		// 	$i = 0;
		// 	foreach( get_the_terms( get_the_id(), 'activity' ) as $data ){
		// 		$args['activity'][$i] = $data->slug;
		// 		$i++;
		// 	}
		// }
	}else{
		$args['posts_per_page'] = $numberposts;
		
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
	}

	ob_start();

	$trip_data = WpTravel_Helpers_Trips::filter_trips( $args );

	if ( is_array( $trip_data ) && isset( $trip_data['code'] ) && 'WP_TRAVEL_FILTER_RESULTS' === $trip_data['code'] ) {
		$trips          = $trip_data['trip'];
		$trip_ids       = wp_list_pluck( $trips, 'id' );
		$col_per_row    = 3;
		if ( $numberposts < 3 ) {
			$col_per_row = $numberposts;
		}
		$layout_version = 'v1';
		if ( function_exists( 'wptravel_layout_version' ) ) {
			$layout_version = wptravel_layout_version();
		}

		?>
		
		<div id="wptravel-block-trips-list" class="wptravel-block-wrapper wptravel-block-trips-list wptravel-block-preview <?php echo $layout_type; ?>">
			<div class="wp-travel-itinerary-items">
				<?php
					$query = new WP_Query($args);
			
					if( $query->have_posts() ) { ?>
					<div class="wp-travel-itinerary-items wptravel-archive-wrapper  <?php echo $layout_type == 'default-layout' ? 'grid-view' : $card_layout ?> ">
					<?php while( $query->have_posts() ) {
						$query->the_post();
						$trip_id = get_the_ID();
						$is_featured_trip = get_post_meta( $trip_id, 'wp_travel_featured', true );
						$is_fixed_departure = WP_Travel_Helpers_Trip_Dates::is_fixed_departure( $trip_id );
						$trip_locations = get_the_terms( $trip_id, 'travel_locations' );
						$group_size = wptravel_get_group_size( $trip_id );

						if ( $trip_locations && is_array( $trip_locations ) ) {
							$first_location = array_shift( $trip_locations );
							$location_name  = $first_location->name;
							$location_link  = get_term_link( $first_location->term_id, 'travel_locations' );
						}else{
							$location_name = '';
						}

						$args = $args_regular = array( 'trip_id' => $trip_id );
						$trip_price = WP_Travel_Helpers_Pricings::get_price( $args );
						$args_regular['is_regular_price'] = true;
						$regular_price = WP_Travel_Helpers_Pricings::get_price( $args_regular );
						$is_enable_sale = WP_Travel_Helpers_Trips::is_sale_enabled(
							array(
								'trip_id'                => $trip_id,
								'from_price_sale_enable' => true,
							)
						);

						if( $card_layout == 'grid-view' ) {
							if( $layout_type == 'default-layout' ) {
								wptravel_get_block_template_part( 'v2/content', 'archive-itineraries' );
							} elseif( $layout_type == 'layout-one' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay">
											<?php if( $is_featured_trip == 'yes' ) { ?>
											<div class="wptravel-blocks-trip-featured">
												<i class="fas fa-crown"></i> Featured
											</div>
											<?php } ?>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<div class="wptravel-blocks-floating-container">
												<div class="wptravel-blocks-trip-code">
													<span class="code-hash">#</span> <?php echo wptravel_get_trip_code( $trip_id ) ?>
												</div>
											</div>
											<div class="wptravel-blocks-card-body-header">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<h3 class="wptravel-blocks-card-title">
														<?php esc_html( the_title() ); ?>
													</h3>
												</a>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
											<div class="wptravel-blocks-card-content">
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if ( $trip_locations ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<div class="wptravel-blocks-trip-rating">
													<?php echo wptravel_single_trip_rating( $trip_id ); ?>
												</div>
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn">Explore</button>
													</a>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-two' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay-base">
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i> Featured
												</div>
												<?php } ?>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<?php if ( $trip_locations ) { ?>
											<div class="wptravel-blocks-floating-container">
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
											</div>
											<?php } ?>
											<div class="wptravel-blocks-card-body-header">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<h3 class="wptravel-blocks-card-title">
														<?php esc_html( the_title() ); ?>
													</h3>
												</a>
												<div class="wptravel-blocks-trip-rating">
													<i class="fas fa-star"></i> <?php echo wptravel_get_average_rating( $trip_id ); ?>
												</div>
											</div>
											<div class="wptravel-blocks-card-content">
												<div class="wptravel-blocks-trip-excerpt">
													<?php echo esc_html( the_excerpt() ) ?>
												</div>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn">Explore</button>
													</a>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-three' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay-base">
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i> Featured
												</div>
												<?php } ?>
											</div>
											<div class="wptravel-blocks-trip-meta-container">
												<?php if ( $trip_locations ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
												<?php } ?>
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<div class="wptravel-blocks-card-body-header">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<h3 class="wptravel-blocks-card-title">
														<?php esc_html( the_title() ); ?>
													</h3>
												</a>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
											<div class="wptravel-blocks-card-content">
												<div class="wptravel-blocks-trip-excerpt">
													<?php echo esc_html( the_excerpt() ); ?>
												</div>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<div class="wptravel-blocks-trip-rating">
													<?php echo wptravel_single_trip_rating( $trip_id ); ?>
												</div>
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn">Explore</button>
													</a>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-four' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay-base">
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<?php } 
												if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<div class="wptravel-blocks-card-body-header">
												<div class="wptravel-blocks-header-left">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<h3 class="wptravel-blocks-card-title">
															<?php esc_html( the_title() ); ?>
														</h3>
													</a>
													<div class="wptravel-blocks-trip-code">
														<span class="code-hash">#</span> <?php echo wptravel_get_trip_code( $trip_id ) ?>
													</div>
												</div>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
											<div class="wptravel-blocks-card-content">
												<div class="wptravel-blocks-trip-excerpt">
													<?php echo esc_html( the_excerpt() ) ?>
												</div>
												<div class="wptravel-blocks-trip-meta-container">
													<?php if( $is_fixed_departure ) { ?>
													<div class="wptravel-blocks-trip-meta">
														<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
													</div>
													<?php } else { ?>
														<div class="wptravel-blocks-trip-meta">
														<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
													</div>
													<?php } ?>
													<?php if ( $trip_locations ) { ?>
													<div class="wptravel-blocks-trip-meta">
														<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
													</div>
													<?php } ?>
													<?php if( $group_size ) { ?>
													<div class="wptravel-blocks-trip-meta">
														<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
													</div>
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<div class="wptravel-blocks-trip-rating">
													<?php echo wptravel_single_trip_rating( $trip_id ); ?>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } 
						}

						if( $card_layout == 'list-view' ) {
							if( $layout_type == 'default-layout' ) {
								wptravel_get_block_template_part( 'v2/content', 'archive-itineraries' );
							} elseif( $layout_type == 'layout-one' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay">
											<?php if( $is_featured_trip == 'yes' ) { ?>
											<div class="wptravel-blocks-trip-featured">
												<i class="fas fa-crown"></i>
											</div>
											<?php } ?>
											<div class="wptravel-blocks-floating-container">
												<div class="wptravel-blocks-trip-code">
													<span class="code-hash">#</span> <?php echo wptravel_get_trip_code( $trip_id ) ?>
												</div>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<div class="wptravel-blocks-card-body-header">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<h3 class="wptravel-blocks-card-title">
														<?php esc_html( the_title() ); ?>
													</h3>
												</a>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
											<div class="wptravel-blocks-card-content">
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if ( $trip_locations ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
											</div>
										</div>
										
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<div class="wptravel-blocks-trip-rating">
													<?php echo wptravel_single_trip_rating( $trip_id ); ?>
												</div>
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn">Explore</button>
													</a>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-two' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay-base">
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i> <?php echo __( 'Featured', 'wp-travel-blocks' ) ?>
												</div>
												<?php } ?>
												<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-header">
											<div class="wptravel-blocks-card-body-header-left">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<h3 class="wptravel-blocks-card-title">
														<?php esc_html( the_title() ); ?>
													</h3>
												</a>
												<?php if ( $trip_locations ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
												<?php } ?>
											</div>
											<div class="wptravel-blocks-trip-rating">
												<i class="fas fa-star"></i> <?php echo wptravel_get_average_rating( $trip_id ); ?>
											</div>
										</div>
										<div class="wptravel-blocks-card-content">
											<div class="wptravel-blocks-trip-excerpt">
												<?php echo esc_html( the_excerpt() ) ?>
											</div>
											<div class="wptravel-blocks-sep"></div>
											<div class="wptravel-blocks-content-right">
												<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
												<div class="wptravel-blocks-trip-offer">
													<?php
														$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
														$save = number_format( $save, 2, '.', ',' );
														echo "Save " . $save . "%";
													?>
												</div>
												<div class="wptravel-blocks-trip-original-price">
													<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
												</div>
												<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
												<?php } else { ?>
												<div class="wptravel-blocks-trip-offer-price">
													<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
												</div>
												<?php } ?>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
											</div>
											<div class="wptravel-blocks-footer-right">
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn"><?php echo __( 'Explore', 'wp-travel-blocks' ) ?></button>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-three' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-img-container">
										<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
										<div class="wptravel-blocks-img-overlay-base">
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-body">
										<div class="wptravel-blocks-card-body-top">
											<div class="wptravel-blocks-card-body-header">
													<div class="wptravel-blocks-header-left">
														<a href="<?php echo esc_url( the_permalink() ) ?>">
															<h3 class="wptravel-blocks-card-title">
																<?php esc_html( the_title() ); ?>
															</h3>
														</a>
														<div class="wptravel-blocks-trip-code">
															<span class="code-hash">#</span> <?php echo wptravel_get_trip_code( $trip_id ) ?>
														</div>
													</div>
													<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
												</div>
											<div class="wptravel-blocks-card-content">
												<div class="wptravel-blocks-content-left">
													<div class="wptravel-blocks-trip-excerpt">
														<?php echo esc_html( the_excerpt() ) ?>
													</div>
													<div class="wptravel-blocks-trip-meta-container">
														<?php if( $is_fixed_departure ) { ?>
														<div class="wptravel-blocks-trip-meta">
															<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
														</div>
														<?php } else { ?>
															<div class="wptravel-blocks-trip-meta">
															<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
														</div>
														<?php } ?>
														<?php if ( $trip_locations ) { ?>
														<div class="wptravel-blocks-trip-meta">
															<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
														</div>
														<?php } ?>
														<?php if( $group_size ) { ?>
														<div class="wptravel-blocks-trip-meta">
															<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
														</div>
														<?php } ?>
													</div>
												</div>
												<div class="wptravel-blocks-content-right">
													<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
													<div class="wptravel-blocks-trip-offer">
														<?php
															$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
															$save = number_format( $save, 2, '.', ',' );
															echo "Save " . $save . "%";
														?>
													</div>
													<div class="wptravel-blocks-trip-original-price">
														<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
													</div>
													<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
													<?php } else { ?>
													<div class="wptravel-blocks-trip-offer-price">
														<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
													</div>
													<?php } ?>
												</div>
											</div>
										</div>
										<div class="wptravel-blocks-card-footer">
											<div class="wptravel-blocks-footer-left">
												<div class="wptravel-blocks-trip-rating">
													<?php echo wptravel_single_trip_rating( $trip_id ); ?>
												</div>
											</div>
											<div class="wptravel-blocks-footer-right">
												<div class="wptravel-blocks-trip-explore">
													<a href="<?php echo esc_url( the_permalink() ) ?>">
														<button class="wptravel-blocks-explore-btn"><?php echo __( 'Explore', 'wp-travel-blocks' ) ?></button>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } elseif( $layout_type == 'layout-four' ) { ?>
								<div class="wptravel-blocks-trip-card">
									<div class="wptravel-blocks-trip-card-top">
										<div class="wptravel-blocks-trip-card-img-container">
											<?php echo wptravel_get_post_thumbnail( $trip_id, 'wp_travel_thumbnail' ); ?>
											<div class="wptravel-blocks-img-overlay">
												<?php if( $is_featured_trip == 'yes' ) { ?>
												<div class="wptravel-blocks-trip-featured">
													<i class="fas fa-crown"></i> <?php echo __( 'Featured', 'wp-travel-blocks' ) ?>
												</div>
												<?php } ?>
											</div>
										</div>
										<div class="wptravel-blocks-card-body">
											<div class="wptravel-blocks-card-body-top">
												<div class="wptravel-blocks-card-body-header">
														<div class="wptravel-blocks-header-left">
															<a href="<?php echo esc_url( the_permalink() ) ?>">
																<h3 class="wptravel-blocks-card-title">
																	<?php esc_html( the_title() ); ?>
																</h3>
															</a>
															<div class="wptravel-blocks-trip-rating">
																<?php echo wptravel_single_trip_rating( $trip_id ); ?>
															</div>
														</div>
														<?php do_action( 'wp_travel_after_archive_title', $trip_id ); ?>
													</div>
												<div class="wptravel-blocks-card-content">
													<div class="wptravel-blocks-trip-excerpt">
														<?php echo esc_html( the_excerpt() ) ?>
													</div>
													<div class="wptravel-blocks-trip-pricing">
														<?php if( $is_enable_sale && $regular_price > $trip_price ) { ?>
														<div class="wptravel-blocks-trip-offer">
															<?php
																$save = ( 1 - ( (int) $trip_price / (int) $regular_price ) ) * 100;
																$save = number_format( $save, 2, '.', ',' );
																echo "Save " . $save . "%";
															?>
														</div>
														<div class="wptravel-blocks-trip-original-price">
															<del><?php echo wptravel_get_formated_price_currency( $regular_price ); ?></del>
														</div>
														<div class="wptravel-blocks-trip-offer-price"><?php echo wptravel_get_formated_price_currency( $trip_price ); ?></div>
														<?php } else { ?>
														<div class="wptravel-blocks-trip-offer-price">
															<?php echo wptravel_get_formated_price_currency( $regular_price ); ?>
														</div>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="wptravel-blocks-card-footer">
										<div class="wptravel-blocks-footer-left">
											<div class="wptravel-blocks-trip-meta-container">
												<?php if( $is_fixed_departure ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class='far fa-calendar-alt'></i> <?php echo wptravel_get_fixed_departure_date( $trip_id ); ?>
												</div>
												<?php } else { ?>
													<div class="wptravel-blocks-trip-meta">
													<i class='far fa-clock'></i> <?php echo wp_travel_get_trip_durations( $trip_id ); ?>
												</div>
												<?php } ?>
												<?php if ( $trip_locations ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $location_name ); ?>
												</div>
												<?php } ?>
												<?php if( $group_size ) { ?>
												<div class="wptravel-blocks-trip-meta">
													<i class="fas fa-users"></i> <?php echo ( (int) $group_size && $group_size < 999 ) ?  wptravel_get_group_size( $trip_id ) : 'No Size Limit' ?>
												</div>
												<?php } ?>
											</div>
										</div>
										<div class="wptravel-blocks-footer-right">
											<div class="wptravel-blocks-trip-explore">
												<a href="<?php echo esc_url( the_permalink() ) ?>">
													<button class="wptravel-blocks-explore-btn"><?php echo __( 'Explore', 'wp-travel-blocks' ) ?></button>
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php }
						}
					} ?>
				</div>
				<?php }else{
					echo __( 'No related trips found..', 'wp-travel-blocks' );
				} ?>
			</div>
		</div>
		<?php
	} else {
		wptravel_get_block_template_part( 'shortcode/itinerary', 'item-none' );
	}

	$html = ob_get_clean();

	return $html;
}