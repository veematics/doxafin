@if($userRole === 'SA' AND isset($userRole))  
<?php //SET AMD TO OR To DISPLAY TO OTHER USER?>
<div>
    <h3 class="font-bold text-lg">Debug Info Panel</h3>
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Auth Check: {{ Auth::check() ? 'Logged In' : 'Not Logged In' }}<br>
        User ID: {{ $user?->id ?? 'None' }}<br>
        Current Role: {{ $userRole ?? 'No Role' }}

    </p>
</div>
@endif
