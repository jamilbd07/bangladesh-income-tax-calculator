<?php
/**
 * Plugin Name:       Bangladesh Tax Calculator
 * Description:       A block for calculating personal income tax in Bangladesh for FY 2025-2026. Users enter their information (including age, gender, third gender/freedom fighter/disabled status, and income/investment details), and the block provides a full breakdown of tax due, including rebates and progressive slab calculations.
 * Version:           0.1.0
 * Author:            WordPress Telex
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bangladesh-tax-calculator-block-wp
 *
 * @package BangladeshTaxCalculator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function bangladesh_tax_calculator_block_init() {
	register_block_type( __DIR__ . '/build/' );
}
add_action( 'init', 'bangladesh_tax_calculator_block_init' );
	