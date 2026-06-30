document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#enquiryForm');
    const packageButtons = document.querySelectorAll('[data-package-select]');
    const packageSelect = document.querySelector('#package_selected');

    if (form) {
        const nameInput = form.querySelector('#full_name');
        const phoneInput = form.querySelector('#phone_number');
        const billInput = form.querySelector('#monthly_bill');

        const patterns = {
            full_name: /^.{3,}$/u,
            phone_number: /^\d{10}$/,
            monthly_bill: /^\d+(\.\d{1,2})?$/
        };

        const setFieldValidity = (field, isValid, message = '') => {
            const feedback = field.closest('.mb-3').querySelector('.invalid-feedback');
            field.classList.toggle('is-invalid', !isValid);
            field.classList.toggle('is-valid', isValid);
            if (feedback && message) {
                feedback.textContent = message;
            }
        };

        const validateField = (field) => {
            const value = field.value.trim();
            let valid = true;
            let message = '';

            if (field.id === 'full_name') {
                valid = patterns.full_name.test(value);
                message = 'Please enter your full name.';
            } else if (field.id === 'phone_number') {
                valid = patterns.phone_number.test(value);
                message = 'Phone number must be exactly 10 digits.';
            } else if (field.id === 'monthly_bill') {
                valid = patterns.monthly_bill.test(value) && Number(value) > 0;
                message = 'Please enter a valid monthly electricity bill.';
            } else if (field.id === 'package_selected') {
                valid = value !== '';
                message = 'Please select a package.';
            }

            setFieldValidity(field, valid, message);
            return valid;
        };

        [nameInput, phoneInput, billInput, packageSelect].forEach((field) => {
            if (!field) return;
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                field.classList.remove('is-invalid');
            });
        });

        form.addEventListener('submit', (event) => {
            const inputs = [nameInput, phoneInput, billInput, packageSelect].filter(Boolean);
            const valid = inputs.every((input) => validateField(input));

            if (!valid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }

    packageButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (!packageSelect) return;
            packageSelect.value = button.dataset.packageSelect || '';
            packageSelect.dispatchEvent(new Event('change', { bubbles: true }));

            const formSection = document.querySelector('#enquiry');
            if (formSection) {
                formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});

