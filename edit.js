
/**
 * Retrieves the translation of text.
 */
import { __ } from '@wordpress/i18n';

import { useBlockProps } from '@wordpress/block-editor';

import { useState } from '@wordpress/element';

import './editor.scss';

function getExemptionLimit({ gender, age, thirdGender, disabled, freedomFighter }) {
	// FY 2025-2026 Bangladesh
	if (thirdGender) return 525000;
	if (freedomFighter || disabled) return 500000;
	if (gender === 'female' || age >= 65) return 425000;
	return 375000;
}
function formatTaka(amount) {
	return '৳' + amount.toLocaleString('en-BD');
}
function calculateTaxSlabs(taxable) {
	const slabs = [
		{ upto: 375000, rate: 0 },
		{ upto: 675000, rate: 0.1 },
		{ upto: 1075000, rate: 0.15 },
		{ upto: 1575000, rate: 0.2 },
		{ upto: 3575000, rate: 0.25 },
	];
	const slabLimits = [375000, 300000, 400000, 500000, 2000000];
	let remaining = taxable;
	let lower = 0;
	let tax = 0;
	let breakdown = [];
	for (let i = 0; i < slabLimits.length && remaining > 0; ++i) {
		const thisSlabLimit = slabLimits[i];
		const taxableHere = Math.min(remaining, thisSlabLimit);
		const slabRate =
			i === 0 ? 0
			: i === 1 ? 0.1
			: i === 2 ? 0.15
			: i === 3 ? 0.2
			: 0.25;
		const slabStart = lower + 1;
		const slabEnd = lower + thisSlabLimit;
		const amountInThisSlab = taxableHere > 0 ? taxableHere : 0;
		const thisTax = slabRate * amountInThisSlab;
		breakdown.push({
			start: slabStart,
			end: slabEnd,
			rate: slabRate,
			amount: amountInThisSlab,
			tax: thisTax,
		});
		tax += thisTax;
		lower += thisSlabLimit;
		remaining -= amountInThisSlab;
	}
	if (remaining > 0) {
		const thisTax = 0.3 * remaining;
		breakdown.push({
			start: lower + 1,
			end: lower + remaining,
			rate: 0.3,
			amount: remaining,
			tax: thisTax,
		});
		tax += thisTax;
	}
	return { breakdown, totalTax: Math.round(tax) };
}
function calculateInvestmentRebate(taxable, investment, totalTax) {
	// Allowable investment: up to 25% of total income or actual, whichever is lower, capped at 15000000 (not in prompt, conservative cap removed)
	const investmentAllowed = Math.min(investment, taxable * 0.25);
	const rebateA = 0.03 * taxable;
	const rebateB = 0.15 * investmentAllowed;
	const rebateCap = 1000000;
	const rebate = Math.min(rebateA, rebateB, rebateCap);
	return {
		rebateA: Math.round(rebateA),
		rebateB: Math.round(rebateB),
		cap: rebateCap,
		allowableInvestment: Math.round(investmentAllowed),
		rebate: Math.min(Math.round(rebate), Math.round(totalTax))
	};
}
export default function Edit() {
	const blockProps = useBlockProps();

	const [inputs, setInputs] = useState({
		fullName: '',
		gender: '',
		age: '',
		thirdGender: false,
		freedomFighter: false,
		disabled: false,
		totalIncome: '',
		totalInvestment: '',
	});
	const [result, setResult] = useState(null);
	const [errors, setErrors] = useState({});

	const handleChange = (e) => {
		const { name, type, value, checked } = e.target;
		setInputs((prev) => ({
			...prev,
			[name]: type === 'checkbox' ? checked : value,
		}));
	};

	const handleSubmit = (e) => {
		e.preventDefault();
		let validation = {};
		if (!inputs.fullName) validation.fullName = 'Full Name is required';
		if (!inputs.gender) validation.gender = 'Gender required';
		if (inputs.age === '' || isNaN(inputs.age) || parseInt(inputs.age) < 0) validation.age = 'Enter valid age';
		if (!inputs.totalIncome || isNaN(inputs.totalIncome) || parseFloat(inputs.totalIncome) < 0) validation.totalIncome = 'Income required';
		if (inputs.totalInvestment === '' || isNaN(inputs.totalInvestment) || parseFloat(inputs.totalInvestment) < 0) validation.totalInvestment = 'Investment required';
		if (Object.keys(validation).length) {
			setErrors(validation);
			setResult(null);
			return;
		}
		setErrors({});
		const values = {
			...inputs,
			age: parseInt(inputs.age),
			totalIncome: parseFloat(inputs.totalIncome),
			totalInvestment: parseFloat(inputs.totalInvestment)
		};
		const exemption = getExemptionLimit({
			gender: values.gender,
			age: values.age,
			thirdGender: values.thirdGender,
			disabled: values.disabled,
			freedomFighter: values.freedomFighter
		});
		const taxableIncome = Math.max(values.totalIncome - exemption, 0);
		const { breakdown, totalTax } = calculateTaxSlabs(taxableIncome);
		const rebateData = calculateInvestmentRebate(taxableIncome, values.totalInvestment, totalTax);
		const finalTax = Math.max(0, totalTax - rebateData.rebate);

		setResult({
			exemption,
			taxableIncome,
			breakdown,
			totalTax,
			rebateData,
			finalTax
		});
	};

	return (
		<div { ...blockProps }>
			<form className="bd-tax-form" onSubmit={handleSubmit} style={{ maxWidth: '480px', marginBottom: '22px', padding: '10px', border: '1px solid #ddd', borderRadius: 8, background: '#f7fafd' }}>
				<h3>{__('Bangladesh Tax Calculator (2025-26)', 'bangladesh-tax-calculator-block-wp')}</h3>
				<div style={{ marginBottom: 10 }}>
					<label>
						{__('Full Name', 'bangladesh-tax-calculator-block-wp')}<br />
						<input name="fullName" type="text" value={inputs.fullName} onChange={handleChange} style={{ width: '100%' }} />
						{errors.fullName && <div className="bd-tax-error">{errors.fullName}</div>}
					</label>
				</div>
				<div style={{ marginBottom: 10 }}>
					<label>
						{__('Gender', 'bangladesh-tax-calculator-block-wp')}<br />
						<select name="gender" value={inputs.gender} onChange={handleChange} style={{ width: '100%' }}>
							<option value="" disabled>-- Select --</option>
							<option value="male">{__('Male', 'bangladesh-tax-calculator-block-wp')}</option>
							<option value="female">{__('Female', 'bangladesh-tax-calculator-block-wp')}</option>
						</select>
						{errors.gender && <div className="bd-tax-error">{errors.gender}</div>}
					</label>
				</div>
				<div style={{ marginBottom: 10 }}>
					<label>
						{__('Age', 'bangladesh-tax-calculator-block-wp')}<br />
						<input name="age" type="number" value={inputs.age} onChange={handleChange} style={{ width: '100%' }} min="0" />
						{errors.age && <div className="bd-tax-error">{errors.age}</div>}
					</label>
				</div>
				<div style={{ display: 'flex', gap: '16px', marginBottom: 10 }}>
					<label>
						<input name="thirdGender" type="checkbox" checked={inputs.thirdGender} onChange={handleChange} />
						{__('Is Third Gender', 'bangladesh-tax-calculator-block-wp')}
					</label>
					<label>
						<input name="freedomFighter" type="checkbox" checked={inputs.freedomFighter} onChange={handleChange} />
						{__('Is Freedom Fighter', 'bangladesh-tax-calculator-block-wp')}
					</label>
					<label>
						<input name="disabled" type="checkbox" checked={inputs.disabled} onChange={handleChange} />
						{__('Is Disabled', 'bangladesh-tax-calculator-block-wp')}
					</label>
				</div>
				<div style={{ marginBottom: 10 }}>
					<label>
						{__('Total Income (Yearly, Taka)', 'bangladesh-tax-calculator-block-wp')}<br />
						<input name="totalIncome" type="number" value={inputs.totalIncome} onChange={handleChange} min="0" style={{ width: '100%' }} />
						{errors.totalIncome && <div className="bd-tax-error">{errors.totalIncome}</div>}
					</label>
				</div>
				<div style={{ marginBottom: 10 }}>
					<label>
						{__('Total Investment (Yearly, Taka)', 'bangladesh-tax-calculator-block-wp')}<br />
						<input name="totalInvestment" type="number" value={inputs.totalInvestment} onChange={handleChange} min="0" style={{ width: '100%' }} />
						{errors.totalInvestment && <div className="bd-tax-error">{errors.totalInvestment}</div>}
					</label>
				</div>
				<div>
					<button type="submit">{__('Calculate Tax', 'bangladesh-tax-calculator-block-wp')}</button>
				</div>
			</form>
			{result && (
				<div className="bd-tax-result" style={{ background: 'white', border: '1px solid #ddd', borderRadius: 8, padding: '12px', maxWidth: '620px' }}>
					<h4>{__('Tax Calculation Result', 'bangladesh-tax-calculator-block-wp')}</h4>
					<table className="bd-tax-result-table">
						<tbody>
							<tr>
								<th align="left">{__('Total Income', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(parseFloat(inputs.totalIncome))}</td>
							</tr>
							<tr>
								<th align="left">{__('Exemption Limit', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.exemption)}</td>
							</tr>
							<tr>
								<th align="left">{__('Taxable Income', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.taxableIncome)}</td>
							</tr>
						</tbody>
					</table>
					<h5 style={{ marginTop: 18 }}>{__('Tax Calculation (Slab by Slab)', 'bangladesh-tax-calculator-block-wp')}</h5>
					<table className="bd-tax-slab-table" style={{ width: '100%' }}>
						<thead>
							<tr>
								<th>{__('Slab Range (৳)', 'bangladesh-tax-calculator-block-wp')}</th>
								<th>{__('Rate (%)', 'bangladesh-tax-calculator-block-wp')}</th>
								<th>{__('Taxable Amount', 'bangladesh-tax-calculator-block-wp')}</th>
								<th>{__('Tax', 'bangladesh-tax-calculator-block-wp')}</th>
							</tr>
						</thead>
						<tbody>
							{result.breakdown.map((row, idx) => (
								<tr key={idx}>
									<td>{formatTaka(row.start)} – {formatTaka(row.end)}</td>
									<td>{Math.round(row.rate * 100)}</td>
									<td>{formatTaka(row.amount)}</td>
									<td>{formatTaka(Math.round(row.tax))}</td>
								</tr>
							))}
						</tbody>
					</table>
					<table className="bd-tax-summary" style={{ marginTop: 18 }}>
						<tbody>
							<tr>
								<th align="left">{__('Total Tax (Before Rebate)', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.totalTax)}</td>
							</tr>
							<tr>
								<th align="left">{__('Allowable Investment Used', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.rebateData.allowableInvestment)}</td>
							</tr>
							<tr>
								<th align="left">{__('Rebate A (3% of taxable income)', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.rebateData.rebateA)}</td>
							</tr>
							<tr>
								<th align="left">{__('Rebate B (15% of applicable investment)', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.rebateData.rebateB)}</td>
							</tr>
							<tr>
								<th align="left">{__('Maximum Rebate Cap', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.rebateData.cap)}</td>
							</tr>
							<tr>
								<th align="left">{__('Applied Rebate', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.rebateData.rebate)}</td>
							</tr>
							<tr>
								<th align="left">{__('Final Tax After Rebate', 'bangladesh-tax-calculator-block-wp')}</th>
								<td>{formatTaka(result.finalTax)}</td>
							</tr>
						</tbody>
					</table>
					<div style={{ marginTop: 16, fontSize: '0.93em', color: '#666' }}>{__('The calculation is for informational purposes only. Check with an official source for compliance.', 'bangladesh-tax-calculator-block-wp')}</div>
				</div>
			)}
		</div>
	);
}
    