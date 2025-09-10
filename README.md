# Bangladesh Tax Calculator - WordPress Block

A user-friendly WordPress Gutenberg block for calculating income tax in Bangladesh according to FY 2025-2026 tax rules and regulations as per NBR (National Board of Revenue) guidelines.

## Features

- **Complete Tax Calculation**: Supports all standard tax slabs for FY 2025-2026
- **Dynamic Exemption Limits**: Automatically calculates exemption based on:
  - Gender (Male/Female)
  - Age (Senior citizen benefits for 65+)
  - Third Gender status
  - Disability status
  - Freedom Fighter status
- **Investment Rebates**: Calculates rebate A and B with proper caps
- **Progressive Tax Slabs**: Accurate slab-wise tax calculation
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Real-time Validation**: Client-side form validation with error messages
- **Bengali Currency Formatting**: Displays amounts in Bangladeshi Taka (৳)

## Tax Rules Implemented (FY 2025-2026)

### Exemption Limits
- **Third Gender**: ৳5,25,000
- **Freedom Fighter/Disabled**: ৳5,00,000
- **Female/Senior Citizen (65+)**: ৳4,25,000
- **General Male**: ৳3,75,000

### Tax Slabs
- First ৳3,75,000: 0%
- Next ৳3,00,000 (৳3,75,001 - ৳6,75,000): 10%
- Next ৳4,00,000 (৳6,75,001 - ৳10,75,000): 15%
- Next ৳5,00,000 (৳10,75,001 - ৳15,75,000): 20%
- Next ৳20,00,000 (৳15,75,001 - ৳35,75,000): 25%
- Above ৳35,75,000: 30%

### Investment Rebates
- **Rebate A**: 3% of taxable income
- **Rebate B**: 15% of allowable investment (max 25% of total income)
- **Maximum Rebate Cap**: ৳10,00,000

## Installation

1. Download or clone this repository
2. Upload the plugin folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. The block will be available in the Gutenberg editor under the "Widgets" category

## Development

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
- `npm start` - Start development server with hot reload
- `npm run build` - Build production files
- `npm run format` - Format code using WordPress standards
- `npm run lint:css` - Lint CSS/SCSS files
- `npm run lint:js` - Lint JavaScript files
- `npm run plugin-zip` - Create distribution zip file

## File Structure

```
bangladesh-tax-calculator/
├── bangladesh-tax-calculator.php  # Main plugin file
├── block.json                     # Block configuration
├── index.js                       # Block registration
├── edit.js                        # Editor component
├── save.js                        # Save component (static HTML)
├── view.js                        # Frontend JavaScript
├── style.scss                     # Frontend styles
├── editor.scss                    # Editor styles
├── render.php                     # Server-side rendering (if needed)
├── build/                         # Compiled files
├── package.json                   # Dependencies and scripts
└── README.md                      # This file
```

## Usage

1. **In Editor**: Add the "Bangladesh Tax Calculator" block to any post or page
2. **Frontend**: Users can fill out the form with their tax information and get instant calculations
3. **Customization**: The block inherits your theme's styling and can be further customized with CSS

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Disclaimer

This calculator is for informational purposes only. Tax calculations should be verified with official NBR guidelines and professional tax consultants. The developers are not responsible for any tax-related decisions made based on this calculator.

## Support

For support, please open an issue on GitHub or contact the development team.

## Changelog

### Version 0.1.0
- Initial release
- Complete tax calculation for FY 2025-2026
- Responsive design
- Form validation
- Investment rebate calculations
