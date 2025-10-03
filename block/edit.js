
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
	SelectControl,
	TextControl
} from '@wordpress/components';

import { ServerSideRender } from '@wordpress/server-side-render';

import './editor.scss';


// Theme options for the block
const THEME_OPTIONS = [
	{ label: __('Default', 'bangladesh-income-tax-calculator'), value: 'default' },
	{ label: __('Dark', 'bangladesh-income-tax-calculator'), value: 'dark' },
	{ label: __('Light', 'bangladesh-income-tax-calculator'), value: 'light' },
];
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	const { theme = 'default', title = '' } = attributes;

	// Handle theme change
	const onThemeChange = (newTheme) => {
		setAttributes({ theme: newTheme });
	};

	const onTitleChange = (newTitle) => {
		setAttributes({ title: newTitle });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Settings', 'bangladesh-income-tax-calculator')}>
					<TextControl
						label={__('Title', 'bangladesh-income-tax-calculator')}
						value={title}
						onChange={onTitleChange}
						help={__('Leave empty if you don\'t want to show the title.', 'bangladesh-income-tax-calculator')}
					/>
					<SelectControl
						label={__('Theme', 'bangladesh-income-tax-calculator')}
						value={theme}
						options={THEME_OPTIONS}
						onChange={onThemeChange}
						help={__('Choose the visual theme for the tax calculator.', 'bangladesh-income-tax-calculator')}
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
							<h3>{title || 'Tax Calculator (Preview)'}</h3>
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
								{__('Tax Calculator Preview - Frontend functionality will be available on the published page.', 'bangladesh-income-tax-calculator')}
							</p>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
