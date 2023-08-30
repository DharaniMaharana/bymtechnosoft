<?php 

function wptravel_block_slider_render( $attr, $content ) {

    $align = isset( $attr['align'] ) ? $attr['align'] : '';
    
    $alignClass = ( 'full' === $align ) ? " alignfull" : (( 'wide' === $align ) ? " alignwide" : "");

    $block_id = isset( $attr['blockId'] ) ? $attr['blockId'] : '';
    
    $main_wrapper_class = "wp-travel-block-slider-wrapper {$alignClass}";
    
    if ( isset( $attr['dotPosition'] ) ) {
        $main_wrapper_class .= ' ' .  $attr['dotPosition'];
    }
    
    if ( isset( $attr['controls'] ) && $attr['controls'] ) {
        $main_wrapper_class .= ' navigation-enabled';
    }
    
    if ( isset( $attr['dot'] ) && $attr['dot'] ) {
        $main_wrapper_class .= ' pagination-enabled';
    }
    
    $content = str_replace( 'wp-block-wp-travel-block-slider', 'wp-block-wp-travel-block-slider swiper-wrapper', $content );
    $content = str_replace( 'wp-block-wp-travel-block-slides', 'wp-block-wp-travel-block-slides swiper-slide', $content );
    ob_start();
   ?>
    <div class="<?php echo esc_attr( $main_wrapper_class ); ?>" id="<?php echo esc_attr( $block_id ); ?>">
        <?php echo $content; ?>
        <?php if ( isset( $attr['controls'] ) && $attr['controls'] ) : ?>
            <div class="swiper-button swiper-button-prev"></div>
            <div class="swiper-button swiper-button-next"></div>
        <?php endif; ?>
            
            
        <?php if ( isset( $attr['dot'] ) && $attr['dot'] ) : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}