# Bangladesh Tax Calculator - WordPress Plugin

A comprehensive WordPress plugin for calculating personal income tax in Bangladesh. This plugin provides multiple integration options including **Shortcode**, **Block Editor**, and **Elementor Widget** with support for both current and previous tax regimes.

## 🚀 Key Features

### Multiple Integration Options
- **📝 Shortcode**: `[tax_calculator]` - Use anywhere in posts, pages, or widgets
- **🧱 Block Editor**: "Tax Calculator Bangladesh" - Visual block editor integration
- **🎨 Elementor Widget**: "Tax Calculator Bangladesh" - Drag-and-drop page builder support

### Tax Law Support
- **New Tax Law**: FY 2025-2026 and subsequent years
- **Previous Tax Law**: Up to FY 2024-2025 (final year of old regime)
- **Automatic Year Detection**: Smart selection based on current financial year

### Calculation Features
- **Complete Tax Calculation**: All standard tax slabs for both regimes
- **Dynamic Exemption Limits**: Based on gender, age, special status
- **Investment Rebates**: Rebate A and B calculations with proper caps
- **Progressive Tax Slabs**: Accurate slab-wise calculations
- **Real-time Results**: Instant calculations without page reload

### Design & Usability
- **Multiple Themes**: Default, Dark, and Light themes
- **Responsive Design**: Mobile, tablet, and desktop optimized
- **Bengali Currency**: Displays amounts in Bangladeshi Taka (৳)
- **Form Validation**: Client-side validation with error messages
- **Accessibility**: WCAG compliant design

## 📋 Usage Guide

### 1. Shortcode Usage

The most flexible way to add the tax calculator anywhere on your site:

```php
// Basic usage
[tax_calculator]

// With custom title
[tax_calculator title="Calculate Your Tax"]

// With theme selection
[tax_calculator theme="dark"]

// Combined parameters
[tax_calculator title="Income Tax Calculator 2025" theme="light"]
```

#### Shortcode Parameters
- **`title`** (optional): Custom heading for the calculator
- **`theme`** (optional): Visual theme - `default`, `dark`, or `light`

### 2. Block Editor

1. In the WordPress editor, click the **+** button
2. Search for "Tax Calculator Bangladesh"
3. Add the block to your content
4. Configure options in the block settings:
   - **Title**: Custom heading text
   - **Theme**: Choose from Default, Dark, or Light

### 3. Elementor Widget

1. Edit your page with Elementor
2. Search for "Tax Calculator Bangladesh" in the widget panel
3. Drag and drop to your desired location
4. Configure in the widget settings:
   - **Title**: Custom heading text
   - **Theme**: Visual theme selection

## 🎨 Theme Options

### Default Theme
- Clean, professional appearance
- Light background with subtle borders
- Suitable for most websites

### Dark Theme
- Dark background with light text
- Modern, sleek appearance
- Perfect for dark-themed websites

### Light Theme
- Bright, minimal design
- High contrast for accessibility
- Clean and simple appearance

## 📊 Tax Rules Implemented

### New Tax Law (FY 2025-2026 onwards)

#### Exemption Limits
- **Third Gender**: ৳5,25,000
- **Freedom Fighter/Disabled**: ৳5,00,000
- **Female/Senior Citizen (65+)**: ৳4,25,000
- **General Male**: ৳3,75,000

#### Tax Slabs
- First ৳3,75,000: 0%
- Next ৳3,00,000 (৳3,75,001 - ৳6,75,000): 10%
- Next ৳4,00,000 (৳6,75,001 - ৳10,75,000): 15%
- Next ৳5,00,000 (৳10,75,001 - ৳15,75,000): 20%
- Next ৳20,00,000 (৳15,75,001 - ৳35,75,000): 25%
- Above ৳35,75,000: 30%

### Previous Tax Law (Up to FY 2024-2025)

#### Exemption Limits
- **Third Gender**: ৳4,50,000
- **Freedom Fighter/Disabled**: ৳4,75,000
- **Female/Senior Citizen (65+)**: ৳3,50,000
- **General Male**: ৳3,00,000

#### Tax Slabs
- First ৳3,00,000: 0%
- Next ৳4,00,000 (৳3,00,001 - ৳7,00,000): 10%
- Next ৳5,00,000 (৳7,00,001 - ৳12,00,000): 15%
- Next ৳6,00,000 (৳12,00,001 - ৳18,00,000): 20%
- Next ৳12,00,000 (৳18,00,001 - ৳30,00,000): 25%
- Above ৳30,00,000: 30%

#### Investment Rebates (Both Regimes)
- **Rebate A**: 3% of taxable income
- **Rebate B**: 15% of allowable investment (max 25% of total income)
- **Maximum Rebate Cap**: ৳10,00,000

> **Note**: FY 2024-2025 is the final year for the previous tax law. Starting from FY 2025-2026, the new tax law applies.

## 🔧 Installation

### Method 1: WordPress Admin (Recommended)
1. Download the plugin zip file
2. Go to **Plugins > Add New** in WordPress admin
3. Click **Upload Plugin** and select the zip file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Download and extract the plugin files
2. Upload the `tax-calculator` folder to `/wp-content/plugins/`
3. Go to **Plugins** in WordPress admin and activate "Bangladesh Tax Calculator"

### After Installation
- **Shortcode**: Use `[tax_calculator]` anywhere
- **Block Editor**: Find "Tax Calculator Bangladesh" in the Widgets category
- **Elementor Widget**: Available in the General category (requires Elementor)

## 💡 How It Works

### User Input Fields
- **Financial Year**: Select applicable tax year (2024-25 or 2025-26+)
- **Personal Information**:
  - Age (for senior citizen benefits)
  - Gender (Male/Female)
  - Special Status (Third Gender, Freedom Fighter, Disabled)
- **Income Details**:
  - Total Annual Income
  - Investment Amount (for rebate calculation)

### Calculation Process
1. **Exemption Calculation**: Based on personal profile and selected year
2. **Taxable Income**: Total income minus applicable exemption
3. **Slab-wise Tax**: Progressive tax calculation across all slabs
4. **Rebate Application**: Investment-based rebate calculation
5. **Final Tax**: Net tax after all rebates and deductions

### Result Display
- Complete breakdown of calculations
- Slab-wise tax details
- Exemption and rebate amounts
- Final tax liability
- Formatted in Bengali currency (৳)

## 🛠️ Development

### Prerequisites
- Node.js (v14 or higher)
- npm or yarn
- WordPress development environment

### Setup
```bash
# Install dependencies
npm install

# Start development server
npm start

# Build for production
npm run build
```

### Available Scripts
- `npm start` - Development server with hot reload
- `npm run build` - Production build
- `npm run format` - Code formatting (WordPress standards)
- `npm run lint:css` - CSS/SCSS linting
- `npm run lint:js` - JavaScript linting
- `npm run plugin-zip` - Create distribution package

## 📁 File Structure

```
tax-calculator/
├── tax-calculator.php     # Main plugin file
├── includes/                         # PHP classes
│   ├── class-tax-calculator-shortcode.php
│   └── class-tax-calculator-elementor-widget.php
├── block/                           # Block editor files
│   ├── block.json                   # Block configuration
│   ├── index.js                     # Block registration
│   ├── edit.js                      # Editor component
│   ├── save.js                      # Save component
│   ├── render.php                   # Server-side rendering
│   ├── view.js                      # Frontend JavaScript
│   ├── style.scss                   # Frontend styles
│   └── editor.scss                  # Editor styles
├── assets/                          # Static assets
│   ├── css/                         # Stylesheets
│   └── js/                          # JavaScript files
├── build/                           # Compiled files
└── package.json                     # Dependencies and scripts
```

## 🌐 Browser Support

- **Desktop**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile**: iOS Safari, Chrome Mobile, Samsung Internet
- **Accessibility**: WCAG 2.1 AA compliant
- **JavaScript**: ES6+ with fallbacks

## 🎯 Use Cases

### For Website Owners
- **Tax Service Websites**: Provide instant tax calculations
- **Accounting Firms**: Client self-service tools
- **Financial Blogs**: Interactive content for readers
- **Government Portals**: Citizen services

### For Developers
- **Theme Integration**: Matches your site's design
- **Custom Styling**: CSS customization support
- **Multiple Implementations**: Choose the best integration method
- **Responsive Design**: Works on all devices

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## ⚠️ Disclaimer

This calculator is for **informational purposes only**. Tax calculations should be verified with:
- Official NBR (National Board of Revenue) guidelines
- Professional tax consultants
- Current tax laws and regulations

The developers are not responsible for any tax-related decisions made based on this calculator.

## 🆘 Support

- **GitHub Issues**: Report bugs and request features
- **Documentation**: Check this README for detailed usage
- **Community**: WordPress.org plugin support forum

## 📝 Changelog

### Version 1.0.0
- **Multiple Integration Options**: Shortcode, Block Editor, Elementor Widget
- **Dual Tax Regime Support**: New (2025-26+) and Previous (up to 2024-25) tax laws
- **Theme System**: Default, Dark, and Light themes
- **Responsive Design**: Mobile-first approach
- **Form Validation**: Real-time client-side validation
- **Investment Rebates**: Complete rebate A and B calculations
- **Bengali Currency**: Proper Taka (৳) formatting
- **Accessibility**: WCAG 2.1 AA compliance

---

**Made with ❤️ for the Bangladesh tax community**
