(function () {
    const copyButtons = document.querySelectorAll('[data-copy]');

    async function copyText(text, button) {
        const original = button.textContent;

        try {
            await navigator.clipboard.writeText(text);
            button.textContent = 'Copied';
            button.disabled = true;
            setTimeout(() => {
                button.textContent = original;
                button.disabled = false;
            }, 1400);
        } catch (error) {
            window.prompt('Copy this link manually:', text);
        }
    }

    copyButtons.forEach((button) => {
        button.addEventListener('click', () => {
            copyText(button.getAttribute('data-copy') || '', button);
        });
    });
})();
