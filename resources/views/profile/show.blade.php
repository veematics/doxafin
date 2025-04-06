<x-app-layout>
    <div class="container-lg">
        <div class="row mb-4">
            <div class="col">
                <h2>Profile Settings</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Profile Information</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        <form id="passwordForm" method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Current Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="current_password" required>
                                    <div class="invalid-feedback">Current password is required</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">New Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" id="new_password" required minlength="8">
                                    <div class="invalid-feedback">Password must be at least 8 characters</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Confirm Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    <div class="invalid-feedback">Passwords do not match</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Profile Picture</div>
                    <div class="card-body text-center">
                        <img src="{{ auth()->user()->avatar ? asset('storage/images/avatars/' . auth()->user()->avatar) : asset('images/avatars/avatar-default.svg') }}" 
                             class="rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;" 
                             alt="Profile Picture">
                        <form id="avatarForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="file" class="form-control" name="avatar" accept=".jpg,.jpeg,.png,.webp" required>
                                <div class="form-text">Max size: 100KB. Allowed types: JPG, PNG, WEBP</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Picture</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('avatarForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const fileInput = this.querySelector('input[type="file"]');
            
            if (!fileInput.files || !fileInput.files[0]) {
                alert('Please select an image file');
                return;
            }

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {  // Updated to use form's action
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    // Remove Content-Type header to let browser set it with boundary
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok) {
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to update profile picture');
                }
            } catch (error) {
                alert(error.message);
            }
        });

        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (newPassword !== confirmPassword) {
                document.getElementById('password_confirmation').classList.add('is-invalid');
                return;
            }

            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok) {
                    // Show success toast using CoreUI
                    const toast = new coreui.Toast(document.createElement('div'));
                    toast._element.classList.add('toast', 'align-items-center', 'text-bg-success', 'border-0');
                    toast._element.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">Password updated successfully</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-coreui-dismiss="toast"></button>
                        </div>
                    `;
                    document.body.appendChild(toast._element);
                    toast.show();
                    
                    this.reset();
                } else {
                    throw new Error(data.error || 'Failed to update password');
                }
            } catch (error) {
                // Show error toast using CoreUI
                const toast = new coreui.Toast(document.createElement('div'));
                toast._element.classList.add('toast', 'align-items-center', 'text-bg-danger', 'border-0');
                toast._element.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">${error.message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-coreui-dismiss="toast"></button>
                    </div>
                `;
                document.body.appendChild(toast._element);
                toast.show();
            }
        });
    </script>
    @endpush
</x-app-layout>