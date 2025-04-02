<x-guest-layout>
    
    <div class="card-header">
        <h4>{{ __('Login') }}</h4>
    </div>
    
    <div class="card-body p-4">
        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-info mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label class="form-label" for="email">{{ __('Email') }}</label>
                <input class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label" for="password">{{ __('Password') }}</label>
                <input class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="remember_me" 
                           name="remember">
                    <label class="form-check-label" for="remember_me">
                        {{ __('Remember me') }}
                    </label>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-8">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary px-4" type="submit">
                        {{ __('Log in') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>