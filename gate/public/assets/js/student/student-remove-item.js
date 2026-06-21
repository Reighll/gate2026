document.addEventListener('DOMContentLoaded', function () {
    const unregisterModal = document.getElementById('unregisterModal');

    if (unregisterModal) {
        unregisterModal.addEventListener('show.bs.modal', function (event) {
            // The button that triggered the modal
            const button = event.relatedTarget;

            // Extract info from data-* attributes
            const itemName = button.getAttribute('data-item-name');
            const actionUrl = button.getAttribute('data-action-url');

            // Update the modal's content
            document.getElementById('modalItemName').textContent = itemName;
            document.getElementById('confirmUnregisterBtn').setAttribute('href', actionUrl);
        });
    }
});