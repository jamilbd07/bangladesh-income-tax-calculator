
/**
 * Frontend JavaScript for Bangladesh Tax Calculator Block
 * Handles all form interactions and tax calculations
 */

// Tax calculation utility functions
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

function calculateTaxableIncome(grossIncome) {
    // Calculate deduction: Gross Income/3 or 450,000 whichever is lower
    const deduction = Math.min(grossIncome / 3, 450000);
    const taxableIncome = grossIncome - deduction;

    return {
        deduction: Math.round(deduction),
        taxableIncome: Math.max(Math.round(taxableIncome), 0)
    };
}

function calculateZeroTaxSlabLimit({ gender, above65, disabled, freedomFighter, taxYear }) {
    // Base 0% tax slab limits
    let baseLimit;
    if (taxYear === '2024-25') {
        baseLimit = 350000; // Base for 2024-25
    } else {
        baseLimit = 375000; // Base for 2025-26
    }

    // Apply bonuses based on conditions
    if (gender === 'female' || above65) {
        baseLimit += 50000;
    }
    if (gender === 'third_gender' || disabled) {
        baseLimit += 125000;
    }
    if (freedomFighter) {
        baseLimit += 150000;
    }

    return baseLimit;
}

function calculateTaxSlabs(taxable, taxYear, personalInfo) {
    let slabLimits, slabRates;

    // Calculate variable 0% tax slab limit
    const zeroTaxLimit = calculateZeroTaxSlabLimit(personalInfo);

    if (taxYear === '2024-25') {
        // FY 2024-2025 and before tax slabs
        slabLimits = [zeroTaxLimit, 100000, 400000, 500000, 500000, 2000000];
        slabRates = [0, 0.05, 0.10, 0.15, 0.20, 0.25];
    } else {
        // FY 2025-2026 and after tax slabs
        slabLimits = [zeroTaxLimit, 300000, 400000, 500000, 2000000];
        slabRates = [0, 0.10, 0.15, 0.20, 0.25];
    }

    let remaining = taxable;
    let lower = 0;
    let tax = 0;
    let breakdown = [];

    for (let i = 0; i < slabLimits.length && remaining > 0; ++i) {
        const thisSlabLimit = slabLimits[i];
        const taxableHere = Math.min(remaining, thisSlabLimit);
        const slabRate = slabRates[i];
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

    // Handle remaining amount above highest slab (30% rate)
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
    // Allowable investment: up to 25% of total income or actual, whichever is lower
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

// Form validation and error display functions
function showError(fieldName, message, form) {
    const field = form.querySelector(`[name="${fieldName}"]`);
    if (field && field.parentNode) {
        const errorDiv = field.parentNode.querySelector('.bd-tax-error');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
        field.classList.add('error');
    } else {
        // Fallback: log error if field structure is not as expected
        console.warn(`Could not show error for field: ${fieldName}. Field or parent node not found.`);
    }
}

function clearErrors(form) {
    if (!form) {
        console.warn('clearErrors: form is null or undefined');
        return;
    }

    const errorDivs = form.querySelectorAll('.bd-tax-error');
    errorDivs.forEach(div => {
        if (div) {
            div.style.display = 'none';
            div.textContent = '';
        }
    });

    const errorFields = form.querySelectorAll('.error');
    errorFields.forEach(field => {
        if (field) {
            field.classList.remove('error');
        }
    });
}

function validateForm(formData) {
    const errors = {};

    if (!formData.taxYear) {
        errors.taxYear = 'Tax Year is required';
    }

    if (!formData.gender) {
        errors.gender = 'Gender required';
    }

    if (!formData.grossIncome || isNaN(formData.grossIncome) || parseFloat(formData.grossIncome) < 0) {
        errors.grossIncome = 'Gross Income required';
    }

    if (formData.totalInvestment === '' || isNaN(formData.totalInvestment) || parseFloat(formData.totalInvestment) < 0) {
        errors.totalInvestment = 'Investment required';
    }

    // Paid tax is optional, but if provided, must be valid
    if (formData.paidTax !== '' && (isNaN(formData.paidTax) || parseFloat(formData.paidTax) < 0)) {
        errors.paidTax = 'Invalid paid tax amount';
    }

    return errors;
}

// Display results in the result container
function displayResults(result, formData, resultContainer) {
    // Helper function to safely update element text content
    function safeUpdateText(selector, value) {
        const element = resultContainer.querySelector(selector);
        if (element) {
            element.textContent = value;
        } else {
            console.warn(`Element not found: ${selector}`);
        }
    }

    // Update basic info
    safeUpdateText('.gross-income-display', formatTaka(parseFloat(formData.grossIncome)));
    safeUpdateText('.deduction-display', formatTaka(result.deduction));
    safeUpdateText('.taxable-income-display', formatTaka(result.taxableIncome));

    // Update slab breakdown
    const slabTableBody = resultContainer.querySelector('.slab-breakdown');
    if (slabTableBody) {
        slabTableBody.innerHTML = '';

        result.breakdown.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${formatTaka(row.start)} – ${formatTaka(row.end)}</td>
                <td>${Math.round(row.rate * 100)}</td>
                <td>${formatTaka(row.amount)}</td>
                <td>${formatTaka(Math.round(row.tax))}</td>
            `;
            slabTableBody.appendChild(tr);
        });
    }

    // Update summary
    safeUpdateText('.total-tax-display', formatTaka(result.totalTax));
    safeUpdateText('.allowable-investment-display', formatTaka(result.rebateData.allowableInvestment));
    safeUpdateText('.rebate-a-display', formatTaka(result.rebateData.rebateA));
    safeUpdateText('.rebate-b-display', formatTaka(result.rebateData.rebateB));
    safeUpdateText('.applied-rebate-display', formatTaka(result.rebateData.rebate));
    safeUpdateText('.final-tax-display', formatTaka(result.finalTax));
    safeUpdateText('.paid-tax-display', formatTaka(result.paidTax));
    safeUpdateText('.payable-tax-display', formatTaka(result.payableTax));

    // Show the result container with animation
    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Main form submission handler
function handleFormSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const resultContainer = form.parentNode.querySelector('.bd-tax-result');

    // Clear previous errors
    clearErrors(form);

    // Get form data
    const formData = new FormData(form);
    const data = {
        taxYear: formData.get('taxYear') || '',
        gender: formData.get('gender') || '',
        above65: formData.has('above65'),
        freedomFighter: formData.has('freedomFighter'),
        disabled: formData.has('disabled'),
        grossIncome: formData.get('grossIncome') || '',
        totalInvestment: formData.get('totalInvestment') || '',
        paidTax: formData.get('paidTax') || '0'
    };

    // Validate form
    const errors = validateForm(data);

    if (Object.keys(errors).length > 0) {
        // Show errors
        Object.keys(errors).forEach(fieldName => {
            showError(fieldName, errors[fieldName], form);
        });
        // Hide result container
        resultContainer.style.display = 'none';
        return;
    }

    // Convert numeric values
    const values = {
        ...data,
        grossIncome: parseFloat(data.grossIncome),
        totalInvestment: parseFloat(data.totalInvestment),
        paidTax: parseFloat(data.paidTax) || 0
    };

    // Calculate taxable income using new logic (no exemption limits)
    const taxableIncomeData = calculateTaxableIncome(values.grossIncome);

    // Prepare personal info for tax slab calculation
    const personalInfo = {
        gender: values.gender,
        above65: values.above65,
        disabled: values.disabled,
        freedomFighter: values.freedomFighter,
        taxYear: values.taxYear
    };

    const { breakdown, totalTax } = calculateTaxSlabs(taxableIncomeData.taxableIncome, values.taxYear, personalInfo);
    const rebateData = calculateInvestmentRebate(taxableIncomeData.taxableIncome, values.totalInvestment, totalTax);
    const finalTax = Math.max(0, totalTax - rebateData.rebate);
    const payableTax = finalTax - values.paidTax;

    const result = {
        deduction: taxableIncomeData.deduction,
        taxableIncome: taxableIncomeData.taxableIncome,
        breakdown,
        totalTax,
        rebateData,
        finalTax,
        paidTax: values.paidTax,
        payableTax: payableTax
    };

    // Display results
    displayResults(result, values, resultContainer);
}

// Initialize the calculator when DOM is ready
function initializeTaxCalculator() {
    // Find all tax calculator forms on the page
    const forms = document.querySelectorAll('.bd-tax-form');

    forms.forEach(form => {
        // Add event listener for form submission
        form.addEventListener('submit', handleFormSubmit);
    });
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTaxCalculator);
} else {
    initializeTaxCalculator();
}
