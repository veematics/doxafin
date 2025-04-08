<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h1 class="display-4 mb-4">Coming Soon</h1>
                            <i class="cil-rocket text-primary" style="font-size: 4rem;"></i>
                            <p class="lead mt-4">This feature is currently under development.</p>
                            <p>We're working hard to bring you something amazing. Please check back later.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                                <i class="cil-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>