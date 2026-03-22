/**
 * Global form loading state handler
 * Automatically adds loading states to all forms on submit
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle all forms
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const submitInput = form.querySelector('input[type="submit"]');
            const submitElement = submitButton || submitInput;
            
            if (submitElement && !submitElement.disabled) {
                // Disable submit button
                submitElement.disabled = true;
                
                // Add loading class if it exists
                if (submitElement.classList) {
                    submitElement.classList.add('opacity-50', 'cursor-not-allowed');
                }
                
                // If button has button-text span, update it
                const buttonText = submitElement.querySelector('.button-text');
                if (buttonText) {
                    submitElement.dataset.originalText = buttonText.textContent;
                    buttonText.textContent = 'Processing...';
                    
                    // Add spinner if not exists
                    if (!submitElement.querySelector('.animate-spin')) {
                        const spinner = document.createElement('svg');
                        spinner.className = 'animate-spin -ml-1 mr-2 h-4 w-4 text-white inline';
                        spinner.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                        spinner.setAttribute('fill', 'none');
                        spinner.setAttribute('viewBox', '0 0 24 24');
                        spinner.innerHTML = `
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        `;
                        submitElement.insertBefore(spinner, buttonText);
                    }
                } else if (submitElement.innerHTML && !submitElement.querySelector('.animate-spin')) {
                    // Fallback for buttons without button-text span
                    const originalText = submitElement.innerHTML;
                    submitElement.dataset.originalText = originalText;
                    submitElement.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    `;
                }
            }
        });
    });
    
    // Handle links with data-confirm attribute (for delete actions)
    const confirmLinks = document.querySelectorAll('a[data-confirm]');
    confirmLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
                return false;
            }
            
            // Add loading state
            this.classList.add('opacity-50', 'pointer-events-none');
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="animate-pulse">Loading...</span>';
        });
    });
});
