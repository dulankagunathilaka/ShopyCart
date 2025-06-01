document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll = document.getElementById('select-all');
    const checkoutBtn = document.getElementById('checkout-btn');
    const selectedTotalEl = document.getElementById('selected-total');
    const finalTotalEl = document.getElementById('final-total');
    const selectedItemsInput = document.getElementById('selected-items');
    const shipping = 250;

    function updateTotalsAndButton() {
        let total = 0;
        let selectedCount = 0;
        let selectedIds = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseFloat(cb.dataset.total);
                selectedIds.push(cb.value);
                selectedCount++;
            }
        });

        selectedTotalEl.textContent = `Rs.${total.toFixed(2)}`;
        finalTotalEl.textContent = selectedCount > 0 ? `Rs.${(total + shipping).toFixed(2)}` : 'Rs.0.00';
        checkoutBtn.disabled = selectedCount === 0;
        selectedItemsInput.value = JSON.stringify(selectedIds);
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotalsAndButton));

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateTotalsAndButton();
    });

    updateTotalsAndButton();
});
