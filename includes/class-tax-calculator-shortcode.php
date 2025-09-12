<?php
    /**
     * Tax Calculator Shortcode Class
     *
     * @package BangladeshTaxCalculator
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    class Tax_Calculator_Shortcode
    {

        /**
         * Constructor
         */
        public function __construct()
        {
            add_shortcode( 'tax_calculator', [ $this, 'render_shortcode' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        }

        /**
         * Render the tax calculator shortcode
         *
         * @param array $atts Shortcode attributes
         * @return string HTML output
         */
        public function render_shortcode( $atts )
        {
            // Parse shortcode attributes
            $atts = shortcode_atts( [
                'theme' => 'default',
                'title' => ''
             ], $atts, 'tax_calculator' );

            // Sanitize attributes
            $theme          = sanitize_text_field( $atts[ 'theme' ] );
            $title          = sanitize_text_field( $atts[ 'title' ] );
            $allowed_themes = [ 'default', 'dark', 'light' ];
            if ( ! in_array( $theme, $allowed_themes ) ) {
                $theme = 'default';
            }

            // Enqueue assets
            $this->enqueue_shortcode_assets();

            // Generate unique ID for this instance
            $instance_id = 'tax-calc-' . uniqid();

            // Build the HTML
            ob_start();
        ?>
        <div class="bd-tax-calculator-wrapper tax-calc-<?php echo esc_attr( $theme ); ?>" id="<?php echo esc_attr( $instance_id ); ?>">
            <?php
                $allowed_html = $this->kses_allowed_html();
                        echo wp_kses( $this->get_form_html( $title ), $allowed_html );
                        echo wp_kses( $this->get_result_html(), $allowed_html );
                    ?>
        </div>
        <?php
            return ob_get_clean();
                }

                /**
                 * Get the form HTML
                 *
                 * @param string $title The title to display
                 * @return string Form HTML
                 */
                private function get_form_html( $title = 'Tax Calculator' )
                {
                    ob_start();
                ?>
        <form class="bd-tax-form" method="post">
            <?php if ( ! empty( $title ) ): ?>
            <div class="bd-tax-form-header">
                <h3><?php echo esc_html( $title ); ?></h3>
            </div>
            <?php endif; ?>

            <div class="bd-tax-form-body">
                <div class="bd-tax-field">
                    <label for="taxYear">
                        <?php esc_html_e( 'Tax Year', 'tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <select name="taxYear" id="taxYear" required>
                        <option value=""><?php esc_html_e( '-- Select Tax Year --', 'tax-calculator' ); ?></option>
                        <option value="2024-25"><?php esc_html_e( '2024-25 or Before', 'tax-calculator' ); ?></option>
                        <option value="2025-26"><?php esc_html_e( '2025-26 or After', 'tax-calculator' ); ?></option>
                    </select>
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="gender">
                        <?php esc_html_e( 'Gender', 'tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <select name="gender" id="gender" required>
                        <option value=""><?php esc_html_e( '-- Select --', 'tax-calculator' ); ?></option>
                        <option value="male"><?php esc_html_e( 'Male', 'tax-calculator' ); ?></option>
                        <option value="female"><?php esc_html_e( 'Female', 'tax-calculator' ); ?></option>
                        <option value="third_gender"><?php esc_html_e( 'Third Gender', 'tax-calculator' ); ?></option>
                    </select>
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-checkboxes">
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="above65" />
                        <?php esc_html_e( 'Above 65 Age', 'tax-calculator' ); ?>
                    </label>
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="freedomFighter" />
                        <?php esc_html_e( 'Is Freedom Fighter', 'tax-calculator' ); ?>
                    </label>
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="disabled" />
                        <?php esc_html_e( 'Is Disabled', 'tax-calculator' ); ?>
                    </label>
                </div>

                <div class="bd-tax-field">
                    <label for="grossIncome">
                        <?php esc_html_e( 'Gross Income (Yearly, Taka)', 'tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="number" name="grossIncome" id="grossIncome" min="0" step="0.01" required />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="totalInvestment">
                        <?php esc_html_e( 'Total Investment (Yearly, Taka)', 'tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="number" name="totalInvestment" id="totalInvestment" min="0" step="0.01" required />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="paidTax">
                        <?php esc_html_e( 'Paid Tax (Optional, Taka)', 'tax-calculator' ); ?>
                    </label>
                    <input type="number" name="paidTax" id="paidTax" min="0" step="0.01" placeholder="0" />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>
            </div>

            <div class="bd-tax-form-footer">
                <button type="submit" class="bd-tax-submit-btn">
                    <?php esc_html_e( 'Calculate Tax', 'tax-calculator' ); ?>
                </button>
            </div>
        </form>
        <?php
            return ob_get_clean();
                }

                /**
                 * Get the result HTML template
                 *
                 * @return string Result HTML
                 */
                private function get_result_html()
                {
                    ob_start();
                ?>
        <div class="bd-tax-result" style="display: none;">
            <div class="bd-tax-result-header">
                <h4><?php esc_html_e( 'Tax Calculation Result', 'tax-calculator' ); ?></h4>
            </div>

            <div class="bd-tax-result-body">
                <div class="bd-tax-summary-section">
                    <table class="bd-tax-summary-table">
                        <tbody>
                            <tr>
                                <th><?php esc_html_e( 'Gross Income', 'tax-calculator' ); ?></th>
                                <td class="gross-income-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Deduction (Gross Income/3 or ৳4,50,000)', 'tax-calculator' ); ?></th>
                                <td class="deduction-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Taxable Income (After Deduction)', 'tax-calculator' ); ?></th>
                                <td class="taxable-income-display">৳0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bd-tax-slab-section">
                    <h5><?php esc_html_e( 'Tax Calculation (Slab by Slab)', 'tax-calculator' ); ?></h5>
                    <table class="bd-tax-slab-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Slab Range (৳)', 'tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Rate (%)', 'tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Taxable Amount', 'tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Tax', 'tax-calculator' ); ?></th>
                            </tr>
                        </thead>
                        <tbody class="slab-breakdown">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>
                </div>

                <div class="bd-tax-final-section">
                    <table class="bd-tax-final-table">
                        <tbody>
                            <tr>
                                <th><?php esc_html_e( 'Total Tax (Before Rebate)', 'tax-calculator' ); ?></th>
                                <td class="total-tax-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Allowable Investment Used', 'tax-calculator' ); ?></th>
                                <td class="allowable-investment-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Rebate A (3% of taxable income)', 'tax-calculator' ); ?></th>
                                <td class="rebate-a-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Rebate B (15% of applicable investment)', 'tax-calculator' ); ?></th>
                                <td class="rebate-b-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Applied Rebate', 'tax-calculator' ); ?></th>
                                <td class="applied-rebate-display">৳0</td>
                            </tr>
                            <tr class="final-tax-row">
                                <th><?php esc_html_e( 'Final Tax After Rebate', 'tax-calculator' ); ?></th>
                                <td class="final-tax-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Paid Tax', 'tax-calculator' ); ?></th>
                                <td class="paid-tax-display">৳0</td>
                            </tr>
                            <tr class="payable-tax-row">
                                <th><?php esc_html_e( 'Payable Tax', 'tax-calculator' ); ?></th>
                                <td class="payable-tax-display">৳0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
            return ob_get_clean();
                }

                /**
                 * Enqueue shortcode-specific assets
                 */
                private function enqueue_shortcode_assets()
                {
                    // This will be called when shortcode is rendered
                    wp_enqueue_script( 'bd-tax-calculator-frontend' );
                    wp_enqueue_style( 'bd-tax-calculator-frontend' );
                }

                /**
                 * Enqueue frontend assets
                 */
                public function enqueue_frontend_assets()
                {
                    // Only register assets, enqueue when shortcode is used
                    wp_register_script(
                        'bd-tax-calculator-frontend',
                        BD_TAX_CALC_PLUGIN_URL . 'assets/js/frontend.js',
                        [  ],
                        BD_TAX_CALC_VERSION,
                        true
                    );

                    wp_register_style(
                        'bd-tax-calculator-frontend',
                        BD_TAX_CALC_PLUGIN_URL . 'assets/css/frontend.css',
                        [  ],
                        BD_TAX_CALC_VERSION
                    );
                }

                /**
                 * Enqueue admin assets
                 */
                public function enqueue_admin_assets()
                {
                    // Register for admin use (Gutenberg editor)
                    wp_register_script(
                        'bd-tax-calculator-frontend',
                        BD_TAX_CALC_PLUGIN_URL . 'assets/js/frontend.js',
                        [  ],
                        BD_TAX_CALC_VERSION,
                        true
                    );

                    wp_register_style(
                        'bd-tax-calculator-frontend',
                        BD_TAX_CALC_PLUGIN_URL . 'assets/css/frontend.css',
                        [  ],
                        BD_TAX_CALC_VERSION
                    );
                }

                /**
                 * Custom function for KSES HTML List
                 * Allows all necessary HTML elements for the tax calculator form
                 *
                 * @return array Allowed HTML Array
                 */
                private function kses_allowed_html()
                {
                    return [
                        // Basic HTML elements
                        'div'      => [
                            'class' => [  ],
                            'id'    => [  ],
                            'style' => [  ]
                         ],
                        'span'     => [
                            'class' => [  ],
                            'id'    => [  ]
                         ],
                        'p'        => [
                            'class' => [  ]
                         ],
                        'h1'       => [
                            'class' => [  ]
                         ],
                        'h2'       => [
                            'class' => [  ]
                         ],
                        'h3'       => [
                            'class' => [  ]
                         ],
                        'h4'       => [
                            'class' => [  ]
                         ],
                        'h5'       => [
                            'class' => [  ]
                         ],
                        'h6'       => [
                            'class' => [  ]
                         ],
                        'strong'   => [  ],
                        'em'       => [  ],
                        'br'       => [  ],
                        'hr'       => [  ],

                        // Form elements
                        'form'     => [
                            'class'  => [  ],
                            'id'     => [  ],
                            'method' => [  ],
                            'action' => [  ]
                         ],
                        'input'    => [
                            'type'        => [  ],
                            'name'        => [  ],
                            'id'          => [  ],
                            'class'       => [  ],
                            'value'       => [  ],
                            'placeholder' => [  ],
                            'required'    => [  ],
                            'min'         => [  ],
                            'max'         => [  ],
                            'step'        => [  ],
                            'checked'     => [  ],
                            'disabled'    => [  ],
                            'readonly'    => [  ]
                         ],
                        'select'   => [
                            'name'     => [  ],
                            'id'       => [  ],
                            'class'    => [  ],
                            'required' => [  ],
                            'disabled' => [  ]
                         ],
                        'option'   => [
                            'value'    => [  ],
                            'selected' => [  ]
                         ],
                        'textarea' => [
                            'name'        => [  ],
                            'id'          => [  ],
                            'class'       => [  ],
                            'rows'        => [  ],
                            'cols'        => [  ],
                            'placeholder' => [  ],
                            'required'    => [  ],
                            'disabled'    => [  ],
                            'readonly'    => [  ]
                         ],
                        'label'    => [
                            'for'   => [  ],
                            'class' => [  ]
                         ],
                        'button'   => [
                            'type'     => [  ],
                            'class'    => [  ],
                            'id'       => [  ],
                            'disabled' => [  ]
                         ],
                        'fieldset' => [
                            'class' => [  ]
                         ],
                        'legend'   => [
                            'class' => [  ]
                         ],

                        // List elements
                        'ul'       => [
                            'class' => [  ]
                         ],
                        'ol'       => [
                            'class' => [  ]
                         ],
                        'li'       => [
                            'class' => [  ]
                         ],

                        // Table elements (for result display)
                        'table'    => [
                            'class' => [  ]
                         ],
                        'thead'    => [
                            'class' => [  ]
                         ],
                        'tbody'    => [
                            'class' => [  ]
                         ],
                        'tfoot'    => [  ],
                        'tr'       => [
                            'class' => [  ]
                         ],
                        'th'       => [
                            'class'   => [  ],
                            'colspan' => [  ],
                            'rowspan' => [  ]
                         ],
                        'td'       => [
                            'class'   => [  ],
                            'colspan' => [  ],
                            'rowspan' => [  ]
                         ],

                        // Links
                        'a'        => [
                            'href'   => [  ],
                            'class'  => [  ],
                            'id'     => [  ],
                            'target' => [  ],
                            'rel'    => [  ]
                         ],

                        // Images
                        'img'      => [
                            'src'    => [  ],
                            'alt'    => [  ],
                            'class'  => [  ],
                            'width'  => [  ],
                            'height' => [  ]
                         ]
                     ];
                }
        }
