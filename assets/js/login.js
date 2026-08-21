document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    if (!form) return;

    const submitButton = form.querySelector('button[type="submit"]');
    let isSubmitting = false;

    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        isSubmitting = true;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.setAttribute('aria-disabled', 'true');
        }
    });
});
