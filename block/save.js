
/**
 * React hook that is used to mark the block wrapper element.
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function outputs the static HTML structure for the tax calculator.
 * All interactivity will be provided via view.js at runtime on the front-end.
 */
export default function save() {
	return (
		<div {...useBlockProps.save()}>
			<form className="bd-tax-form" style={{ maxWidth: '480px', marginBottom: '22px', padding: '10px', border: '1px solid #ddd', borderRadius: '8px', background: '#f7fafd' }}>
				<h3>Bangladesh Tax Calculator (2025-26)</h3>
				<div style={{ marginBottom: '10px' }}>
					<label>
						Full Name<br />
						<input name="fullName" type="text" style={{ width: '100%' }} />
						<div className="bd-tax-error" style={{ display: 'none', color: 'red', fontSize: '0.9em' }}></div>
					</label>
				</div>
				<div style={{ marginBottom: '10px' }}>
					<label>
						Gender<br />
						<select name="gender" style={{ width: '100%' }}>
							<option value="" disabled selected>-- Select --</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
						<div className="bd-tax-error" style={{ display: 'none', color: 'red', fontSize: '0.9em' }}></div>
					</label>
				</div>
				<div style={{ marginBottom: '10px' }}>
					<label>
						Age<br />
						<input name="age" type="number" style={{ width: '100%' }} min="0" />
						<div className="bd-tax-error" style={{ display: 'none', color: 'red', fontSize: '0.9em' }}></div>
					</label>
				</div>
				<div style={{ display: 'flex', gap: '16px', marginBottom: '10px', flexWrap: 'wrap' }}>
					<label>
						<input name="thirdGender" type="checkbox" />
						Is Third Gender
					</label>
					<label>
						<input name="freedomFighter" type="checkbox" />
						Is Freedom Fighter
					</label>
					<label>
						<input name="disabled" type="checkbox" />
						Is Disabled
					</label>
				</div>
				<div style={{ marginBottom: '10px' }}>
					<label>
						Total Income (Yearly, Taka)<br />
						<input name="totalIncome" type="number" min="0" style={{ width: '100%' }} />
						<div className="bd-tax-error" style={{ display: 'none', color: 'red', fontSize: '0.9em' }}></div>
					</label>
				</div>
				<div style={{ marginBottom: '10px' }}>
					<label>
						Total Investment (Yearly, Taka)<br />
						<input name="totalInvestment" type="number" min="0" style={{ width: '100%' }} />
						<div className="bd-tax-error" style={{ display: 'none', color: 'red', fontSize: '0.9em' }}></div>
					</label>
				</div>
				<div>
					<button type="submit">Calculate Tax</button>
				</div>
			</form>
			<div className="bd-tax-result" style={{ background: 'white', border: '1px solid #ddd', borderRadius: '8px', padding: '12px', maxWidth: '620px', display: 'none' }}>
				<h4>Tax Calculation Result</h4>
				<table className="bd-tax-result-table">
					<tbody>
						<tr>
							<th align="left">Total Income</th>
							<td className="total-income-display"></td>
						</tr>
						<tr>
							<th align="left">Exemption Limit</th>
							<td className="exemption-display"></td>
						</tr>
						<tr>
							<th align="left">Taxable Income</th>
							<td className="taxable-income-display"></td>
						</tr>
					</tbody>
				</table>
				<h5 style={{ marginTop: '18px' }}>Tax Calculation (Slab by Slab)</h5>
				<table className="bd-tax-slab-table" style={{ width: '100%' }}>
					<thead>
						<tr>
							<th>Slab Range (৳)</th>
							<th>Rate (%)</th>
							<th>Taxable Amount</th>
							<th>Tax</th>
						</tr>
					</thead>
					<tbody className="slab-breakdown">
					</tbody>
				</table>
				<table className="bd-tax-summary" style={{ marginTop: '18px' }}>
					<tbody>
						<tr>
							<th align="left">Total Tax (Before Rebate)</th>
							<td className="total-tax-display"></td>
						</tr>
						<tr>
							<th align="left">Allowable Investment Used</th>
							<td className="allowable-investment-display"></td>
						</tr>
						<tr>
							<th align="left">Rebate A (3% of taxable income)</th>
							<td className="rebate-a-display"></td>
						</tr>
						<tr>
							<th align="left">Rebate B (15% of applicable investment)</th>
							<td className="rebate-b-display"></td>
						</tr>
						<tr>
							<th align="left">Maximum Rebate Cap</th>
							<td className="rebate-cap-display"></td>
						</tr>
						<tr>
							<th align="left">Applied Rebate</th>
							<td className="applied-rebate-display"></td>
						</tr>
						<tr>
							<th align="left">Final Tax After Rebate</th>
							<td className="final-tax-display"></td>
						</tr>
					</tbody>
				</table>
				<div style={{ marginTop: '16px', fontSize: '0.93em', color: '#666' }}>The calculation is for informational purposes only. Check with an official source for compliance.</div>
			</div>
		</div>
	);
}
