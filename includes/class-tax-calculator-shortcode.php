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
                'theme' => 'default'
             ], $atts, 'tax_calculator' );

            // Sanitize theme attribute
            $theme          = sanitize_text_field( $atts[ 'theme' ] );
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
            <?php echo $this->get_form_html(); ?>
            <?php echo $this->get_result_html(); ?>
        </div>
        <?php
            return ob_get_clean();
                }

                /**
                 * Get the form HTML
                 *
                 * @return string Form HTML
                 */
                private function get_form_html()
                {
                    ob_start();
                ?>
        <form class="bd-tax-form" method="post">
            <div class="bd-tax-form-header">
                <h3><?php esc_html_e( 'Bangladesh Tax Calculator', 'bangladesh-tax-calculator' ); ?></h3>
            </div>

            <div class="bd-tax-form-body">
                <div class="bd-tax-field">
                    <label for="taxYear">
                        <?php esc_html_e( 'Tax Year', 'bangladesh-tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <select name="taxYear" id="taxYear" required>
                        <option value=""><?php esc_html_e( '-- Select Tax Year --', 'bangladesh-tax-calculator' ); ?></option>
                        <option value="2024-25"><?php esc_html_e( '2024-25 or Before', 'bangladesh-tax-calculator' ); ?></option>
                        <option value="2025-26"><?php esc_html_e( '2025-26 or After', 'bangladesh-tax-calculator' ); ?></option>
                    </select>
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="gender">
                        <?php esc_html_e( 'Gender', 'bangladesh-tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <select name="gender" id="gender" required>
                        <option value=""><?php esc_html_e( '-- Select --', 'bangladesh-tax-calculator' ); ?></option>
                        <option value="male"><?php esc_html_e( 'Male', 'bangladesh-tax-calculator' ); ?></option>
                        <option value="female"><?php esc_html_e( 'Female', 'bangladesh-tax-calculator' ); ?></option>
                        <option value="third_gender"><?php esc_html_e( 'Third Gender', 'bangladesh-tax-calculator' ); ?></option>
                    </select>
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-checkboxes">
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="above65" />
                        <?php esc_html_e( 'Above 65 Age', 'bangladesh-tax-calculator' ); ?>
                    </label>
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="freedomFighter" />
                        <?php esc_html_e( 'Is Freedom Fighter', 'bangladesh-tax-calculator' ); ?>
                    </label>
                    <label class="bd-tax-checkbox">
                        <input type="checkbox" name="disabled" />
                        <?php esc_html_e( 'Is Disabled', 'bangladesh-tax-calculator' ); ?>
                    </label>
                </div>

                <div class="bd-tax-field">
                    <label for="grossIncome">
                        <?php esc_html_e( 'Gross Income (Yearly, Taka)', 'bangladesh-tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="number" name="grossIncome" id="grossIncome" min="0" step="0.01" required />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="totalInvestment">
                        <?php esc_html_e( 'Total Investment (Yearly, Taka)', 'bangladesh-tax-calculator' ); ?>
                        <span class="required">*</span>
                    </label>
                    <input type="number" name="totalInvestment" id="totalInvestment" min="0" step="0.01" required />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>

                <div class="bd-tax-field">
                    <label for="paidTax">
                        <?php esc_html_e( 'Paid Tax (Optional, Taka)', 'bangladesh-tax-calculator' ); ?>
                    </label>
                    <input type="number" name="paidTax" id="paidTax" min="0" step="0.01" placeholder="0" />
                    <div class="bd-tax-error" style="display: none;"></div>
                </div>
            </div>

            <div class="bd-tax-form-footer">
                <button type="submit" class="bd-tax-submit-btn">
                    <?php esc_html_e( 'Calculate Tax', 'bangladesh-tax-calculator' ); ?>
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
                <h4><?php esc_html_e( 'Tax Calculation Result', 'bangladesh-tax-calculator' ); ?></h4>
            </div>

            <div class="bd-tax-result-body">
                <div class="bd-tax-summary-section">
                    <table class="bd-tax-summary-table">
                        <tbody>
                            <tr>
                                <th><?php esc_html_e( 'Gross Income', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="gross-income-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Deduction (Gross Income/3 or ৳4,50,000)', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="deduction-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Taxable Income (After Deduction)', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="taxable-income-display">৳0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bd-tax-slab-section">
                    <h5><?php esc_html_e( 'Tax Calculation (Slab by Slab)', 'bangladesh-tax-calculator' ); ?></h5>
                    <table class="bd-tax-slab-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e( 'Slab Range (৳)', 'bangladesh-tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Rate (%)', 'bangladesh-tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Taxable Amount', 'bangladesh-tax-calculator' ); ?></th>
                                <th><?php esc_html_e( 'Tax', 'bangladesh-tax-calculator' ); ?></th>
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
                                <th><?php esc_html_e( 'Total Tax (Before Rebate)', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="total-tax-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Allowable Investment Used', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="allowable-investment-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Rebate A (3% of taxable income)', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="rebate-a-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Rebate B (15% of applicable investment)', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="rebate-b-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Applied Rebate', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="applied-rebate-display">৳0</td>
                            </tr>
                            <tr class="final-tax-row">
                                <th><?php esc_html_e( 'Final Tax After Rebate', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="final-tax-display">৳0</td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Paid Tax', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="paid-tax-display">৳0</td>
                            </tr>
                            <tr class="payable-tax-row">
                                <th><?php esc_html_e( 'Payable Tax', 'bangladesh-tax-calculator' ); ?></th>
                                <td class="payable-tax-display">৳0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bd-tax-disclaimer">
                    <p><?php esc_html_e( 'The calculation is for informational purposes only. Check with an official source for compliance.', 'bangladesh-tax-calculator' ); ?></p>
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
        }
