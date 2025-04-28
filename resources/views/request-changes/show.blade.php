<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold mb-6">Request Change Details</h1>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="card">
            <div class="card-body">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="font-semibold">Status</dt>
                        <dd>
                            <span class="badge badge-{{ $requestChange->status }}">
                                {{ ucfirst($requestChange->status) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="font-semibold">Created By</dt>
                        <dd>{{ $requestChange->creator->name }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold">Created At</dt>
                        <dd>{{ $requestChange->created_at->format('d-m-Y H:i') }}</dd>
                    </div>

                    @if($requestChange->approved_by)
                        <div>
                            <dt class="font-semibold">Approved By</dt>
                            <dd>{{ $requestChange->approver->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold">Approved At</dt>
                            <dd>{{ $requestChange->approved_at->format('d-m-Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Notes</h3>
                    <p class="whitespace-pre-wrap">{{ $requestChange->notes }}</p>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Changes</h3>
                    <pre class="bg-gray-100 p-4 rounded">{{ json_encode($requestChange->changes, JSON_PRETTY_PRINT) }}</pre>
                </div>

                @if($requestChange->status === 'pending' && $can_approve)
                    <div class="mt-6 flex gap-4">
                        <form action="{{ route('request-changes.approve', $requestChange) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <form action="{{ route('request-changes.reject', $requestChange) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                        <form action="{{ route('request-changes.request-revision', $requestChange) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">Request Revision</button>
                        </form>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('request-changes.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>