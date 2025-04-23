<x-app-layout>
   @php
      if (session('role_name') !== 'SA') {
            abort(403, 'Playground access is forbidden for you. Your action logged for security assestment');
        }
   @endphp
   Debug Test: <br/>
   <a href="{{ route('playground.roles') }}">Cek roles and Permission</a><br/>
   <a href="{{ route('playground.memberroles') }}">Cek other roles and Permission</a><br/>
   <a href="{{ route('playground.select2') }}">Select2 Library</a><br/>
   <a href="{{ route('playground.ckeditor') }}">Ckeditor Test</a>

   <div class="mt-4">
      <h3 class="font-semibold text-lg">Session Data:</h3>
      <div class="mt-2">
         <p><strong>User ID:</strong> {{ session('user_id') }}</p>
         <p><strong>User Role:</strong> {{ session('user_role') }}</p>
         <p><strong>Role Name:</strong> {{ session('role_name') }}</p>
      </div>
   </div>
</x-app-layout>