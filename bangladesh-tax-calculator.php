<?php
/**
 * Plugin Name:       Bangladesh Tax Calculator
 * Description:       A comprehensive tax calculator for Bangladesh FY 2025-2026 with shortcode, Gutenberg block, and Elementor widget support. Features multiple themes and responsive design.
 * Version:           1.0.0
 * Author:            WordPress Telex
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bangladesh-tax-calculator
 *
 * @package BangladeshTaxCalculator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'BD_TAX_CALC_VERSION', '1.0.0' );
define( 'BD_TAX_CALC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BD_TAX_CALC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class
 */
class Bangladesh_Tax_Calculator
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
    }

    /**
     * Initialize the plugin
     */
    public function init()
    {
        // Load required files
        $this->load_dependencies();

        // Initialize components
        $this->init_shortcode();
        $this->init_gutenberg_block();
        $this->init_elementor_widget();
    }

    /**
     * Load plugin dependencies
     */
    private function load_dependencies()
    {
        require_once BD_TAX_CALC_PLUGIN_DIR . 'includes/class-tax-calculator-shortcode.php';

        // Load Elementor widget only if Elementor is active
        if ( did_action( 'elementor/loaded' ) ) {
            require_once BD_TAX_CALC_PLUGIN_DIR . 'includes/class-tax-calculator-elementor-widget.php';
        }
    }

    /**
     * Initialize shortcode
     */
    private function init_shortcode()
    {
        new Tax_Calculator_Shortcode();
    }

    /**
     * Initialize Gutenberg block
     */
    private function init_gutenberg_block()
    {
        register_block_type( BD_TAX_CALC_PLUGIN_DIR . 'block/' );
    }

    /**
     * Initialize Elementor widget
     */
    private function init_elementor_widget()
    {
        if ( did_action( 'elementor/loaded' ) ) {
            add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_elementor_widget' ] );
        }
    }

    /**
     * Register Elementor widget
     */
    public function register_elementor_widget()
    {
        if ( class_exists( 'Tax_Calculator_Elementor_Widget' ) ) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Tax_Calculator_Elementor_Widget() );
        }
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain()
    {
        load_plugin_textdomain( 'bangladesh-tax-calculator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
}

// Initialize the plugin
new Bangladesh_Tax_Calculator();
