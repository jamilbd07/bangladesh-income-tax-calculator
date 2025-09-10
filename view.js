
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
    const errorDiv = field.parentNode.querySelector('.bd-tax-error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
}

function clearErrors(form) {
    const errorDivs = form.querySelectorAll('.bd-tax-error');
    errorDivs.forEach(div => {
        div.style.display = 'none';
        div.textContent = '';
    });
}

function validateForm(formData) {
    const errors = {};

    if (!formData.fullName.trim()) {
        errors.fullName = 'Full Name is required';
    }

    if (!formData.gender) {
        errors.gender = 'Gender required';
    }

    if (formData.age === '' || isNaN(formData.age) || parseInt(formData.age) < 0) {
        errors.age = 'Enter valid age';
    }

    if (!formData.totalIncome || isNaN(formData.totalIncome) || parseFloat(formData.totalIncome) < 0) {
        errors.totalIncome = 'Income required';
    }

    if (formData.totalInvestment === '' || isNaN(formData.totalInvestment) || parseFloat(formData.totalInvestment) < 0) {
        errors.totalInvestment = 'Investment required';
    }

    return errors;
}

// Display results in the result container
function displayResults(result, formData, resultContainer) {
    // Update basic info
    resultContainer.querySelector('.total-income-display').textContent = formatTaka(parseFloat(formData.totalIncome));
    resultContainer.querySelector('.exemption-display').textContent = formatTaka(result.exemption);
    resultContainer.querySelector('.taxable-income-display').textContent = formatTaka(result.taxableIncome);

    // Update slab breakdown
    const slabTableBody = resultContainer.querySelector('.slab-breakdown');
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

    // Update summary
    resultContainer.querySelector('.total-tax-display').textContent = formatTaka(result.totalTax);
    resultContainer.querySelector('.allowable-investment-display').textContent = formatTaka(result.rebateData.allowableInvestment);
    resultContainer.querySelector('.rebate-a-display').textContent = formatTaka(result.rebateData.rebateA);
    resultContainer.querySelector('.rebate-b-display').textContent = formatTaka(result.rebateData.rebateB);
    resultContainer.querySelector('.rebate-cap-display').textContent = formatTaka(result.rebateData.cap);
    resultContainer.querySelector('.applied-rebate-display').textContent = formatTaka(result.rebateData.rebate);
    resultContainer.querySelector('.final-tax-display').textContent = formatTaka(result.finalTax);

    // Show the result container
    resultContainer.style.display = 'block';
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
        fullName: formData.get('fullName') || '',
        gender: formData.get('gender') || '',
        age: formData.get('age') || '',
        thirdGender: formData.has('thirdGender'),
        freedomFighter: formData.has('freedomFighter'),
        disabled: formData.has('disabled'),
        totalIncome: formData.get('totalIncome') || '',
        totalInvestment: formData.get('totalInvestment') || ''
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
        age: parseInt(data.age),
        totalIncome: parseFloat(data.totalIncome),
        totalInvestment: parseFloat(data.totalInvestment)
    };

    // Calculate tax
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

    const result = {
        exemption,
        taxableIncome,
        breakdown,
        totalTax,
        rebateData,
        finalTax
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
