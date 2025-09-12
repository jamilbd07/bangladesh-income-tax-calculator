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
$title = isset( $attributes[ 'title' ] ) ? sanitize_text_field( $attributes[ 'title' ] ) : '';

// Validate theme
$allowed_themes = [ 'default', 'dark', 'light' ];
if ( ! in_array( $theme, $allowed_themes ) ) {
    $theme = 'default';
}

// Build shortcode attributes
$shortcode_atts = 'theme="' . esc_attr( $theme ) . '"';
// Only add title attribute if it's not empty (including whitespace-only strings)
if ( ! empty( trim( $title ) ) ) {
    $shortcode_atts .= ' title="' . esc_attr( $title ) . '"';
}

// Render the shortcode
echo do_shortcode( '[tax_calculator ' . $shortcode_atts . ']' );
