
function handlePermanentCheckbox() {
    const permanentCheckbox = document.getElementById('suspension-permanent');
    const endInput = document.getElementById('suspension-end');
    if (!permanentCheckbox || !endInput) return;

    permanentCheckbox.addEventListener('change', (e: Event) => {
        const target = e.target as HTMLInputElement;
        console.log(target.checked);

        if (target.checked) {
            endInput.setAttribute('disabled', 'true');
        } else {
            endInput.removeAttribute('disabled');
        }
    })
}

handlePermanentCheckbox();
