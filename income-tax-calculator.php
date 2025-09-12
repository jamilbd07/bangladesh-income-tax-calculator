<?php
/**
 * Plugin Name:       Bangladesh Income Tax Calculator
 * Description:       A comprehensive tax calculator for Bangladesh with shortcode, block editor, and Elementor widget support. Features multiple themes and responsive design.
 * Version:           1.0.0
 * Author:            MD Jamil Uddin
 * License:           GPLv3
 * License URI:       https://opensource.org/licenses/GPL-3.0
 * Text Domain:       income-tax-calculator
 *
 * @package TaxCalculatorBangladesh
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
}

// Initialize the plugin
new Bangladesh_Tax_Calculator();
