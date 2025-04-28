document.addEventListener('DOMContentLoaded', function() {
    // Handle status filter changes
    document.querySelectorAll('.status-filter').forEach(filter => {
        filter.addEventListener('change', function() {
            const module = this.dataset.module;
            const status = this.value;
            const clientFilter = document.querySelector(`.client-filter[data-module="${module}"]`);
            filterModuleChanges(module, status, clientFilter.value);
        });
    });

    // Handle client filter changes
    document.querySelectorAll('.client-filter').forEach(filter => {
        filter.addEventListener('change', function() {
            const module = this.dataset.module;
            const client = this.value;
            const statusFilter = document.querySelector(`.status-filter[data-module="${module}"]`);
            filterModuleChanges(module, statusFilter.value, client);
        });
    });

    // Function to filter changes for a specific module
    function filterModuleChanges(module, status, client) {
        const url = `/request-changes/filter/${module}`;
        const params = new URLSearchParams({
            status: status,
            client: client
        });

        fetch(`${url}?${params}`)
            .then(response => response.json())
            .then(data => {
                updateModuleCard(module, data.changes);
            })
            .catch(error => console.error('Error:', error));
    }

    // Function to update the module card content
    function updateModuleCard(module, changes) {
        const cardBody = document.querySelector(`[data-module="${module}"] .card-body`);
        const contentDiv = cardBody.querySelector('.changes-content');

        if (changes.length === 0) {
            contentDiv.innerHTML = '<div class="text-center py-3">No changes found</div>';
            return;
        }

        let html = '';
        changes.forEach(change => {
            html += `
                <div class="border-bottom py-2">
                    <div class="d-flex justify-content-between">
                        <div>${change.notes}</div>
                        <span class="badge badge-${change.status}">${change.status.charAt(0).toUpperCase() + change.status.slice(1)}</span>
                    </div>
                    <small class="text-medium-emphasis">${change.created_at}</small>
                </div>
            `;
        });

        contentDiv.innerHTML = html;
    }
}));