<x-app-layout>
    <div class="container-lg">
        <div class="card">
            <div class="card-header">
                <strong>Create New User</strong>
            </div>
            <div class="card-body">
                <!-- Update the route name to include the 'appsetting.' prefix -->
                <form action="{{ route('appsetting.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('appsetting.users.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>