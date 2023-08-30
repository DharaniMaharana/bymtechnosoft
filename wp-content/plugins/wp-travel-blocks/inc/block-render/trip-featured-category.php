<?php

/**
 * 
 * Render Callback For Trip List
 * 
 */
function wptravel_block_trip_featured_category_render( $attributes, $content ){

    if( $attributes['tripTax'] == 'trip-destination' ){
        $tax_name = !empty( $attributes['query']['selectedTripDestinations'][0]['name'] ) ?  $attributes['query']['selectedTripDestinations'][0]['name']  : '';
        $tax_url = !empty( $attributes['query']['selectedTripDestinations'][0]['link'] ) ? $attributes['query']['selectedTripDestinations'][0]['link'] : '';
        $tax_count = !empty( $attributes['query']['selectedTripDestinations'][0]['count'] ) ? $attributes['query']['selectedTripDestinations'][0]['count'] : '';
        $tax_image = !empty( $attributes['query']['selectedTripDestinations'][0]['id'] ) ? get_term_meta( $attributes['query']['selectedTripDestinations'][0]['id'], 'wp_travel_trip_type_image_id', true) : '';
        $tax_image = ! empty( $tax_image ) ? wp_get_attachment_url( $tax_image ) : '';
    }

    if( $attributes['tripTax'] == 'trip-type' ){
        $tax_name = !empty( $attributes['query']['selectedTripTypes'][0]['name'] ) ?  $attributes['query']['selectedTripTypes'][0]['name']  : '';
        $tax_url = !empty( $attributes['query']['selectedTripTypes'][0]['link'] ) ? $attributes['query']['selectedTripTypes'][0]['link'] : '';
        $tax_count = !empty( $attributes['query']['selectedTripTypes'][0]['count'] ) ? $attributes['query']['selectedTripTypes'][0]['count'] : '';
        $tax_image = !empty( $attributes['query']['selectedTripTypes'][0]['id'] ) ? get_term_meta( $attributes['query']['selectedTripTypes'][0]['id'], 'wp_travel_trip_type_image_id', true) : '';
        $tax_image = ! empty( $tax_image ) ? wp_get_attachment_url( $tax_image ) : '';
    }

    if( $attributes['tripTax'] == 'trip-activity' ){
        $tax_name = !empty( $attributes['query']['selectedTripActivities'][0]['name'] ) ?  $attributes['query']['selectedTripActivities'][0]['name']  : '';
        $tax_url = !empty( $attributes['query']['selectedTripActivities'][0]['link'] ) ? $attributes['query']['selectedTripActivities'][0]['link'] : '';
        $tax_count = !empty( $attributes['query']['selectedTripActivities'][0]['count'] ) ? $attributes['query']['selectedTripActivities'][0]['count'] : '';
        $tax_image = !empty( $attributes['query']['selectedTripActivities'][0]['id'] ) ? get_term_meta( $attributes['query']['selectedTripActivities'][0]['id'], 'wp_travel_trip_type_image_id', true) : '';
        $tax_image = ! empty( $tax_image ) ? wp_get_attachment_url( $tax_image ) : '';
    }

    $layout = isset( $attributes['layout'] ) ? $attributes['layout'] : 'layout-one';

	ob_start();

    if ( $attributes['tripTax'] != '') {
        if ( $tax_name != '' && $tax_url != '' && $tax_count != '' ) {
            if( $layout == "layout-one" ) { ?>
                <div id="wp-travel-blocks-trip-featured-category" class="<?php echo $layout; ?>">
                    <a href="<?php echo esc_url($tax_url); ?>">
                        <div class="wp-travel-blocks-trip-featured-category-img-container">
                            <img src="<?php echo esc_url( $tax_image ); ?>" alt="">
                        </div>
                    </a>
                    <div class="wp-travel-blocks-trip-featured-category-footer">
                        
                            <div class="wp-travel-blocks-trip-featured-category-left-info">
                                <span><?php echo esc_html($tax_name); ?></span>
                                <i class="fa fa-arrow-right"></i>
                            </div>
                        
                        <div class="wp-travel-blocks-trip-featured-category-right-info">
                            <i class="fas fa-suitcase-rolling"></i>
                            <span><?php echo esc_html($tax_count) . ' ' . __( 'Trips Available') ?></span>
                        </div>
                    </div>
                </div>
            <?php } elseif ( $layout == "layout-two" ) { ?>
                <div id="wp-travel-blocks-trip-featured-category" class="<?php echo $layout; ?>">
                    <a href="<?php echo esc_url($tax_url); ?>">
                        <div class="wp-travel-blocks-trip-featured-category-img-container">
                            <img src="<?php echo esc_url( $tax_image ); ?>" alt="">
                            <div class="wp-travel-blocks-trip-featured-category-img-overlay-trip">
                                <i class="fas fa-suitcase-rolling"></i>
                                <span><?php echo esc_html($tax_count) . ' ' . __( 'Trips Available') ?></span>
                            </div>
                        </div>
                    </a>
                    <div class="wp-travel-blocks-trip-featured-category-footer">
                        <div class="wp-travel-blocks-trip-featured-category-left-info">
                            <span><?php echo esc_html($tax_name); ?></span>
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            <?php } elseif ( $layout == "layout-three" ) { ?>
                <div id="wp-travel-blocks-trip-featured-category" class="<?php echo $layout; ?>">
                    <a href="<?php echo esc_url($tax_url); ?>">
                        <div class="wp-travel-blocks-trip-featured-category-img-container">
                            <img src="<?php echo esc_url( $tax_image ); ?>" alt="">
                        </div>
                        <div class="wp-travel-blocks-trip-featured-category-img-overlay-trip">
                            <div class="wp-travel-blocks-trip-featured-category-footer">
                                <div class="wp-travel-blocks-trip-info-container">
                                    <div class="wp-travel-blocks-trip-featured-category-left-info">
                                        <div class="wp-travel-blocks-trip-destination">
                                            <span><?php echo esc_html($tax_name); ?></span>
                                        </div>
                                        <i class="fas fa-suitcase-rolling"></i>
                                        <span class="wp-travel-blocks-trip-count"><?php echo esc_html($tax_count) . ' ' . __( 'Trips Available') ?></span> 
                                    </div>
                                    <div class="wp-travel-blocks-trip-featured-category-right-info">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } elseif ( $layout == "layout-four" ) { ?>
                <div id="wp-travel-blocks-trip-featured-category" class="<?php echo $layout; ?>">
                    <a href="<?php echo esc_url($tax_url); ?>">
                        <div class="wp-travel-blocks-trip-featured-category-img-container">
                            <img src="<?php echo esc_url( $tax_image ); ?>" alt="">
                        </div>
                        <div class="wp-travel-blocks-trip-featured-category-img-overlay-trip">
                            <div class="wp-travel-blocks-trip-featured-category-footer">
                                <div class="wp-travel-blocks-trip-info-container">
                                    <div class="wp-travel-blocks-trip-featured-category-left-info">
                                        <div class="wp-travel-blocks-trip-destination">
                                            <span><?php echo esc_html($tax_name); ?></span>
                                        </div>
                                        <i class="fas fa-suitcase-rolling"></i>
                                        <span class="wp-travel-blocks-trip-count"><?php echo esc_html($tax_count) . ' ' . __( 'Trips Available') ?></span> 
                                    </div>
                                </div>
                                <div class="wp-travel-blocks-trip-featured-category-bottom">
                                    <div class="wp-travel-blocks-trip-featured-category-arrow">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }
        } else {
            return null;
        }
    } else {
        return null;
    } ?>

		<?php
	$html = ob_get_clean();

	return $html;
}