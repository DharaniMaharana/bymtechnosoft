<?php
/**
 * Title: Banner
 * Slug: travelvania/banner
 * Categories: travelvania-block-category
 *
 * @package travelvania
 * @since 1.0.0
 */

?>
<!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() );?>/assets/images/gallery-two.jpg","id":1532,"dimRatio":0,"minHeight":700,"contentPosition":"center center","isDark":false} -->
<div class="wp-block-cover is-light" style="min-height:700px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background wp-image-1532" alt="" src="<?php echo esc_url( get_template_directory_uri() );?>/assets/images/gallery-two.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"lineHeight":1.5}},"textColor":"bright","fontSize":"xx-large"} -->
<h2 class="wp-block-heading has-text-align-center has-bright-color has-text-color has-xx-large-font-size" style="line-height:1.5"><?php echo __( 'Start Your Amazing Holiday', 'travelvania' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"textColor":"bright"} -->
<p class="has-text-align-center has-bright-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--50)"><strong><?php echo __( 'Lorem Ipsum', 'travelvania' ); ?></strong>&nbsp;<?php echo __( 'is simply dummy text of the printing and typesetting industry.', 'travelvania' ); ?> </p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"1.3rem"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:1.3rem"><?php echo __( 'Explore Tour', 'travelvania' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->