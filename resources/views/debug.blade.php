<x-app-layout>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Feature Access Cache Debug</strong>
                        </div>
                        <div class="card-body">
                            @php
                                $userId = auth()->id();
                                $cacheKey = 'user_permissions_' . $userId;
                                $permissions = Cache::get($cacheKey);
                            @endphp

                            <h5>Cache Key: {{ $cacheKey }}</h5>
                            
                            @if($permissions)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Feature ID</th>
                                                <th>Can View</th>
                                                <th>Can Create</th>
                                                <th>Can Edit</th>
                                                <th>Can Delete</th>
                                                <th>Can Approve</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permissions as $featureId => $perms)
                                                <tr>
                                                    <td>{{ $featureId }}</td>
                                                    <td>{{ $perms->first()->can_view }}</td>
                                                    <td>{{ $perms->first()->can_create ? '✓' : '✗' }}</td>
                                                    <td>{{ $perms->first()->can_edit ? '✓' : '✗' }}</td>
                                                    <td>{{ $perms->first()->can_delete ? '✓' : '✗' }}</td>
                                                    <td>{{ $perms->first()->can_approve ? '✓' : '✗' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No cache data found for current user.
                                </div>
                            @endif

                            <div class="mt-3">
                                <form action="{{ route('debug.rebuild-cache') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Rebuild Cache</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>