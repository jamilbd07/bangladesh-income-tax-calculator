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
$bdinctax_selected_theme = isset( $attributes[ 'theme' ] ) ? sanitize_text_field( $attributes[ 'theme' ] ) : 'default';
$title                   = isset( $attributes[ 'title' ] ) ? sanitize_text_field( $attributes[ 'title' ] ) : '';

// Validate theme
$bdinctax_allowed_themes = [ 'default', 'dark', 'light' ];
if ( ! in_array( $bdinctax_selected_theme, $bdinctax_allowed_themes ) ) {
    $bdinctax_selected_theme = 'default';
}

// Build shortcode attributes
$bdinctax_shortcode_atts = 'theme="' . esc_attr( $bdinctax_selected_theme ) . '"';
// Only add title attribute if it's not empty (including whitespace-only strings)
if ( ! empty( trim( $title ) ) ) {
    $bdinctax_shortcode_atts .= ' title="' . esc_attr( $title ) . '"';
}

// Render the shortcode
echo do_shortcode( '[bd_tax_calculator ' . $bdinctax_shortcode_atts . ']' );
