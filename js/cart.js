document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll = document.getElementById('select-all');
    const checkoutBtn = document.getElementById('checkout-btn');
    const selectedTotalEl = document.getElementById('selected-total');
    const finalTotalEl = document.getElementById('final-total');
    const shipping = 250;

    function updateTotalsAndButton() {
        let total = 0;
        let selectedCount = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.total);
                selectedCount++;
            }
        });

        selectedTotalEl.textContent = `Rs.${total.toFixed(2)}`;
        finalTotalEl.textContent = selectedCount > 0 ?
            `Rs.${(total + shipping).toFixed(2)}` :
            'Rs.0.00';

        checkoutBtn.disabled = selectedCount === 0;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotalsAndButton));
    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateTotalsAndButton();
    });

    updateTotalsAndButton();
});
