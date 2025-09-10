<?php
/**
 * Server-side rendering for the Bangladesh Tax Calculator block
 *
 * @package BangladeshTaxCalculator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Get block attributes
$theme = isset( $attributes[ 'theme' ] ) ? sanitize_text_field( $attributes[ 'theme' ] ) : 'default';

// Validate theme
$allowed_themes = [ 'default', 'dark', 'light' ];
if ( ! in_array( $theme, $allowed_themes ) ) {
    $theme = 'default';
}

// Render the shortcode
echo do_shortcode( '[tax_calculator theme="' . esc_attr( $theme ) . '"]' );
