@props([
    'featureId' => null
])

@php
$debugUser=1;
echo ('<h5>User</h5>User ID: '.session('user_id').'</br>
User Role:'.session('user_role').'<br/>
Role Name:'.session('role_name').'<br/>');
$debugPermission=1;
if($debugUser==1){
        
}

if($debugPermission==1){    
        $userId = auth()->id();
        $cacheKey = 'user_permissions_' . $userId;
        $permissions = Cache::get($cacheKey);
        $can_view = $permissions[$featureId][0]->can_view;
        $can_create = $permissions[$featureId][0]->can_create;
        $can_approve = $permissions[$featureId][0]->can_approve;
        $can_edit = $permissions[$featureId][0]->can_edit;
        $can_delete = $permissions[$featureId][0]->can_delete;
        echo("<h5>Permissions for FeatureID:".$featureId."</h5>");
        echo('can_view: '.$can_view.'<br/>');   
        echo('can_create: '.$can_create.'<br/>');
        echo('can_approve: '.$can_approve.'<br/>');
        echo('can_edit: '.$can_edit.'<br/>');
        echo('can_delete: '.$can_delete.'<br/>');
}
    @endphp

@php
 
    $userId = auth()->id();
    $cacheKey = 'user_permissions_' . $userId;
    $permissions = Cache::get($cacheKey);

    // Permission checks for Feature ID
    $can_view = isset($permissions[$featureId]) && $permissions[$featureId]->contains('view');
    $can_create = isset($permissions[$featureId]) && $permissions[$featureId]->contains('create');
    $can_approve = isset($permissions[$featureId]) && $permissions[$featureId]->contains('approve');
    $can_edit = isset($permissions[$featureId]) && $permissions[$featureId]->contains('edit');
    $can_delete = isset($permissions[$featureId]) && $permissions[$featureId]->contains('delete');
@endphp


