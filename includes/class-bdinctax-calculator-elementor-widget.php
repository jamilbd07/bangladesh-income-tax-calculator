<?php
    /**
     * Tax Calculator Elementor Widget
     *
     * @package BangladeshTaxCalculator
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    /**
     * Tax Calculator Elementor Widget Class
     */
    class BDIncTax_Calculator_Elementor_Widget extends \Elementor\Widget_Base {

        /**
         * Get widget name
         *
         * @return string Widget name
         */
        public function get_name() {
            return 'bangladesh-income-tax-calculator';
        }

        /**
         * Get widget title
         *
         * @return string Widget title
         */
        public function get_title() {
            return esc_html__( 'Bangladesh Income Tax Calculator', 'bangladesh-income-tax-calculator' );
        }

        /**
         * Get widget icon
         *
         * @return string Widget icon
         */
        public function get_icon() {
            return 'eicon-calculator';
        }

        /**
         * Get widget categories
         *
         * @return array Widget categories
         */
        public function get_categories() {
            return array( 'general' );
        }

        /**
         * Get widget keywords
         *
         * @return array Widget keywords
         */
        public function get_keywords() {
            return array( 'tax', 'calculator', 'bangladesh', 'income', 'finance' );
        }

        /**
         * Register widget controls
         */
        protected function register_controls() {
            // Content Section
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => esc_html__( 'Content', 'bangladesh-income-tax-calculator' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT
                )
            );

            $this->add_control(
                'title',
                array(
                    'label' => esc_html__( 'Title', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Tax Calculator',
                    'placeholder' => esc_html__( 'Enter title...', 'bangladesh-income-tax-calculator' ),
                    'description' => esc_html__( 'Leave empty if you don\'t want to show the title.', 'bangladesh-income-tax-calculator' )
                )
            );

            $this->add_control(
                'theme',
                array(
                    'label' => esc_html__( 'Theme', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => array(
                        'default' => esc_html__( 'Default', 'bangladesh-income-tax-calculator' ),
                        'dark' => esc_html__( 'Dark', 'bangladesh-income-tax-calculator' ),
                        'light' => esc_html__( 'Light', 'bangladesh-income-tax-calculator' )
                    ),
                    'description' => esc_html__( 'Choose the visual theme for the tax calculator.', 'bangladesh-income-tax-calculator' )
                )
            );

            $this->end_controls_section();

            // Style Section
            $this->start_controls_section(
                'style_section',
                array(
                    'label' => esc_html__( 'Style', 'bangladesh-income-tax-calculator' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE
                )
            );

            $this->add_responsive_control(
                'alignment',
                array(
                    'label' => esc_html__( 'Alignment', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => array(
                        'left' => array(
                            'title' => esc_html__( 'Left', 'bangladesh-income-tax-calculator' ),
                            'icon' => 'eicon-text-align-left'
                        ),
                        'center' => array(
                            'title' => esc_html__( 'Center', 'bangladesh-income-tax-calculator' ),
                            'icon' => 'eicon-text-align-center'
                        ),
                        'right' => array(
                            'title' => esc_html__( 'Right', 'bangladesh-income-tax-calculator' ),
                            'icon' => 'eicon-text-align-right'
                        )
                    ),
                    'default' => 'center',
                    'selectors' => array(
                        '{{WRAPPER}} .bd-tax-calculator-wrapper' => 'text-align: {{VALUE}};'
                    )
                )
            );

            $this->add_responsive_control(
                'max_width',
                array(
                    'label' => esc_html__( 'Max Width', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => array( 'px', '%', 'em', 'rem' ),
                    'range' => array(
                        'px' => array(
                            'min' => 300,
                            'max' => 1200
                        ),
                        '%' => array(
                            'min' => 50,
                            'max' => 100
                        )
                    ),
                    'default' => array(
                        'unit' => 'px',
                        'size' => 800
                    ),
                    'selectors' => array(
                        '{{WRAPPER}} .bd-tax-calculator-wrapper' => 'max-width: {{SIZE}}{{UNIT}};'
                    )
                )
            );

            $this->add_responsive_control(
                'margin',
                array(
                    'label' => esc_html__( 'Margin', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors' => array(
                        '{{WRAPPER}} .bd-tax-calculator-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    )
                )
            );

            $this->add_responsive_control(
                'padding',
                array(
                    'label' => esc_html__( 'Padding', 'bangladesh-income-tax-calculator' ),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors' => array(
                        '{{WRAPPER}} .bd-tax-calculator-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    )
                )
            );

            $this->end_controls_section();
        }

        /**
         * Render widget output on the frontend
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            $theme    = isset( $settings[ 'theme' ] ) ? sanitize_text_field( $settings[ 'theme' ] ) : 'default';
            $title    = isset( $settings[ 'title' ] ) ? sanitize_text_field( $settings[ 'title' ] ) : '';

            // Validate theme
            $allowed_themes = array( 'default', 'dark', 'light' );
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
            echo do_shortcode( '[bangladesh_income_tax_calculator ' . $shortcode_atts . ']' );
        }

        /**
         * Render widget output in the editor
         */
        protected function content_template() {
        ?>
        <#
        var theme = settings.theme || 'default';
        var themeClass = 'tax-calc-' + theme;
        var themeStyles = {
            'default': {
                background: '#f8f9fa',
                color: '#2c3e50',
                border: '1px solid #dee2e6'
            },
            'dark': {
                background: '#2c3e50',
                color: '#ecf0f1',
                border: '1px solid #34495e'
            },
            'light': {
                background: '#ffffff',
                color: '#495057',
                border: '1px solid #e9ecef'
            }
        };
        var currentTheme = themeStyles[theme] || themeStyles['default'];
        #>
        <div class="bd-tax-calculator-wrapper {{ themeClass }}">
            <div class="bd-tax-form" style="padding: 20px; border-radius: 8px; background: {{ currentTheme.background }}; color: {{ currentTheme.color }}; border: {{ currentTheme.border }};">
                <div class="bd-tax-form-header">
                    <h3>{{ settings.title || 'Tax Calculator (Preview)' }}</h3>
                </div>
                <div class="bd-tax-form-body">
                    <p style="text-align: center; padding: 20px; background: rgba(0,0,0,0.05); border-radius: 6px; margin: 0;">
                        <?php esc_html_e( 'Tax Calculator Preview - Frontend functionality will be available on the published page.', 'bangladesh-income-tax-calculator' ); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
            }

                /**
                 * Render plain content (for export)
                 */
                public function render_plain_content() {
                    $settings = $this->get_settings_for_display();
                    $theme    = isset( $settings[ 'theme' ] ) ? sanitize_text_field( $settings[ 'theme' ] ) : 'default';
                    $title    = isset( $settings[ 'title' ] ) ? sanitize_text_field( $settings[ 'title' ] ) : '';

                    // Validate theme
                    $allowed_themes = array( 'default', 'dark', 'light' );
                    if ( ! in_array( $theme, $allowed_themes ) ) {
                        $theme = 'default';
                    }

                    // Build shortcode attributes
                    $shortcode_atts = 'theme="' . esc_attr( $theme ) . '"';
                    // Only add title attribute if it's not empty (including whitespace-only strings)
                    if ( ! empty( trim( $title ) ) ) {
                        $shortcode_atts .= ' title="' . esc_attr( $title ) . '"';
                    }

                    echo esc_html( '[bangladesh_income_tax_calculator ' . $shortcode_atts . ']' );
                }
        }
