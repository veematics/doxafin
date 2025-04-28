document.addEventListener('DOMContentLoaded', function() {
    const globalStatusFilter = document.querySelector('.global-status-filter');
    const globalClientFilter = document.querySelector('.global-client-filter');
    const changesContents = document.querySelectorAll('.changes-content');

    function filterChanges() {
        const selectedStatus = globalStatusFilter.value;
        const selectedClient = globalClientFilter.value;

        changesContents.forEach(content => {
            const changes = content.querySelectorAll('.border-bottom');
            let hasVisibleItems = false;

            changes.forEach(change => {
                const statusBadge = change.querySelector('.badge');
                const status = statusBadge.textContent.toLowerCase();
                const clientId = change.dataset.clientId;

                const statusMatch = !selectedStatus || status === selectedStatus;
                const clientMatch = !selectedClient || clientId === selectedClient;

                if (statusMatch && clientMatch) {
                    change.style.display = '';
                    hasVisibleItems = true;
                } else {
                    change.style.display = 'none';
                }
            });

            // Show/hide 'No changes found' message
            const noChangesMessage = content.querySelector('.text-center.py-3');
            if (noChangesMessage) {
                noChangesMessage.style.display = hasVisibleItems ? 'none' : 'block';
            }
        });
    }

    globalStatusFilter.addEventListener('change', filterChanges);
    globalClientFilter.addEventListener('change', filterChanges);
}));