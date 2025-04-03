<x-app-layout>
    Hello
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link">Logout</button>
    </form>
</x-app-layout>
