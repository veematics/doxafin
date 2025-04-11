<x-app-layout>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Your chart initialization code here
        const ctx = document.getElementById('card-chart-new1');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3],
                    borderWidth: 1
                }]
            }
        });
    });
</script>
@endpush
<div class="row align-items-center mb-4">
    <div class="col">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">Dashboard</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">Dashboard</span></li>
            </ol>
        </nav>
    </div>
    <div class="col-auto">
        <x-date-filter />
    </div>
</div>
 <div class="row">
  Doxadigital Base App - Dashboard would be here for different purpose, come back later
 </div>

          @stack('scripts')
</x-app-layout>
