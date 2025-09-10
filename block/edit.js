
/**
 * Retrieves the translation of text.
 */
import { __ } from '@wordpress/i18n';

import {
	useBlockProps,
	InspectorControls
} from '@wordpress/block-editor';

import {
	PanelBody,
	SelectControl
} from '@wordpress/components';

import { ServerSideRender } from '@wordpress/server-side-render';

import './editor.scss';


// Theme options for the block
const THEME_OPTIONS = [
	{ label: __('Default', 'bangladesh-tax-calculator'), value: 'default' },
	{ label: __('Dark', 'bangladesh-tax-calculator'), value: 'dark' },
	{ label: __('Light', 'bangladesh-tax-calculator'), value: 'light' },
];
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	const { theme = 'default' } = attributes;

	// Handle theme change
	const onThemeChange = (newTheme) => {
		setAttributes({ theme: newTheme });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Theme Settings', 'bangladesh-tax-calculator')}>
					<SelectControl
						label={__('Theme', 'bangladesh-tax-calculator')}
						value={theme}
						options={THEME_OPTIONS}
						onChange={onThemeChange}
						help={__('Choose the visual theme for the tax calculator.', 'bangladesh-tax-calculator')}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<div className={`bd-tax-calculator-wrapper tax-calc-${theme}`}>
					<div className="bd-tax-form" style={{
						padding: '20px',
						border: '1px solid #ddd',
						borderRadius: '8px',
						background: theme === 'dark' ? '#2c3e50' : theme === 'light' ? '#ffffff' : '#f8f9fa',
						color: theme === 'dark' ? '#ecf0f1' : '#333'
					}}>
						<div className="bd-tax-form-header">
							<h3>{__('Bangladesh Tax Calculator', 'bangladesh-tax-calculator')}</h3>
						</div>
						<div className="bd-tax-form-body">
							<p style={{
								textAlign: 'center',
								padding: '20px',
								background: theme === 'dark' ? '#34495e' : theme === 'light' ? '#f8f9fa' : '#e3f2fd',
								borderRadius: '6px',
								margin: '0',
								color: theme === 'dark' ? '#bdc3c7' : '#666'
							}}>
								{__('Tax Calculator Preview - Frontend functionality will be available on the published page.', 'bangladesh-tax-calculator')}
							</p>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
